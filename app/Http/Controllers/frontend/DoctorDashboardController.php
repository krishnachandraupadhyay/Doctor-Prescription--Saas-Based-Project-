<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\doctor_document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\patient;
use App\Models\medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator; 

class DoctorDashboardController extends Controller
{
    //
    public function dashboard()
    {
        $doctorId = Auth::guard('doctor')->id();
        $doctor = Auth::guard('doctor')->user();
        $doctorname = $doctor ? $doctor->name : '';

        // Receptionist/staff names associated with this doctor (by doctor_id or created_by)
        $staffMembers = \App\Models\Member::where(function($q) use ($doctorId, $doctorname, $doctor) {
            $q->where('doctor_id', $doctorId);
            if (!empty($doctorname)) {
                $q->orWhere('created_by', $doctorname);
            }
        })
        ->where(function($q) {
            $q->where('isdeleted', 0)->orWhereNull('isdeleted');
        })
        ->get();

        $staffNames = $staffMembers->pluck('name')->toArray();

        $doctorPatientScope = function($q) use ($doctorId, $staffNames) {
            $q->where('doctor_id', $doctorId);
            if (!empty($staffNames)) {
                $q->orWhereIn('created_by', $staffNames);
            }
        };

        $totalMembers = $staffMembers->count();
        $totalPatients = Patient::where($doctorPatientScope)->count();

        $todayPatients = Patient::where($doctorPatientScope)
                            ->where(function($q) {
                                $q->whereDate('created_at', today())
                                  ->orWhereDate('updated_at', today())
                                  ->orWhereHas('presciption_data', function($p) {
                                      $p->whereDate('created_at', today())
                                        ->orWhereDate('updated_at', today());
                                  });
                            })
                            ->count();

        $todayPatientList = Patient::where($doctorPatientScope)
                            ->where(function($q) {
                                $q->whereDate('created_at', today())
                                  ->orWhereDate('updated_at', today())
                                  ->orWhereHas('presciption_data', function($p) {
                                      $p->whereDate('created_at', today())
                                        ->orWhereDate('updated_at', today());
                                  });
                            })
                            ->latest('updated_at')
                            ->get();

        $totalMedicine = medicine::count();

        // Additional Analytics for Valex Dashboard
        $thisMonthPatients = Patient::where($doctorPatientScope)
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $thisWeekPatients = Patient::where($doctorPatientScope)
                                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                ->count();

        $completedToday = Patient::where($doctorPatientScope)
                                ->where(function($q) {
                                    $q->whereDate('created_at', today())
                                      ->orWhereDate('updated_at', today())
                                      ->orWhereHas('presciption_data', function($p) {
                                          $p->whereDate('created_at', today())
                                            ->orWhereDate('updated_at', today());
                                      });
                                })
                                ->where('status', 'completed')
                                ->count();

        $waitingToday = $todayPatients - $completedToday;

        // 7 Days Weekly Patient Trend for ApexChart
        $weeklyChartDays = [];
        $weeklyChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyChartDays[] = $date->format('D (d M)');
            $count = Patient::where($doctorPatientScope)
                        ->whereDate('created_at', $date->toDateString())
                        ->count();
            $weeklyChartData[] = $count;
        }

        // 6 Months Patient Trend for ApexChart
        $monthlyChartLabels = [];
        $monthlyChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlyChartLabels[] = $monthDate->format('M Y');
            $count = Patient::where($doctorPatientScope)
                        ->whereMonth('created_at', $monthDate->month)
                        ->whereYear('created_at', $monthDate->year)
                        ->count();
            $monthlyChartData[] = $count;
        }

        // Recent Patients (Last 5 registered patients across all days)
        $recentPatients = Patient::where($doctorPatientScope)
                            ->orderBy('id', 'desc')
                            ->take(5)
                            ->get();

        // Top Chief Complaints / Symptoms
        $recentSymptoms = \App\Models\symptoms::take(6)->get();

        return view('frontend.Doctordashboard.index', compact(
            'totalPatients', 'todayPatients', 'todayPatientList', 'totalMedicine', 'totalMembers',
            'thisMonthPatients', 'thisWeekPatients', 'completedToday', 'waitingToday',
            'weeklyChartDays', 'weeklyChartData', 'monthlyChartLabels', 'monthlyChartData',
            'recentPatients', 'recentSymptoms'
        ));
    }

    public function viewProfile()
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Clinic documents (Photo, Header, Footer, Stamp, Signature)
        $clinicDoc = \App\Models\doctor_clinic_document::where('doctor_id', $doctor->id)
            ->when($doctor->clinic_id, function($q) use ($doctor) {
                $q->orWhere('clinic_id', $doctor->clinic_id);
            })->first();

        // General documents from doctors_document table
        $documents = \App\Models\doctor_document::where('doctor_id', $doctor->id)->get();

        // Statistics
        $totalPatients = \App\Models\Patient::where('doctor_id', $doctor->id)->count();
        $totalPrescriptions = \App\Models\presciption_data::where('Doctor_Emp_id', $doctor->id)->distinct('patient_id')->count('patient_id');

        // Correction Requests History
        $correctionRequests = \App\Models\DoctorCorrectionRequest::where('doctor_id', $doctor->id)->latest()->get();

        // Mark unread notifications as read when viewing profile
        \App\Models\DoctorCorrectionRequest::where('doctor_id', $doctor->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('frontend.profile.viewprofile', compact('doctor', 'clinicDoc', 'documents', 'totalPatients', 'totalPrescriptions', 'correctionRequests'));
    }

    public function markNotificationsRead(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            return response()->json(['success' => false], 401);
        }

        if ($request->filled('id')) {
            \App\Models\DoctorCorrectionRequest::where('doctor_id', $doctor->id)
                ->where('id', $request->id)
                ->update(['is_read' => true]);
        } else {
            \App\Models\DoctorCorrectionRequest::where('doctor_id', $doctor->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function storeCorrectionRequest(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $request->validate([
            'field_name'      => 'required|string|max:100',
            'current_value'   => 'nullable|string|max:1000',
            'requested_value' => 'required|string|max:1000',
            'reason'          => 'required|string|max:1000',
        ]);

        \App\Models\DoctorCorrectionRequest::create([
            'doctor_id'       => $doctor->id,
            'clinic_id'       => $doctor->clinic_id,
            'field_name'      => $request->field_name,
            'current_value'   => $request->current_value,
            'requested_value' => $request->requested_value,
            'reason'          => $request->reason,
            'status'          => 'pending',
        ]);

        return redirect()->back()->with('success', 'Profile correction request submitted to Clinic Administration successfully!');
    }

    public function editProfile(){
        return redirect()->route('frontend.profile.view')->with('error', 'Doctor profiles cannot be edited directly. Please contact Clinic Administration for profile changes.');
    }
    public function updateProfile(Request $request){
        return redirect()->route('frontend.profile.view')->with('error', 'Doctor profiles cannot be edited directly. Please contact Clinic Administration for profile changes.');
    }
    public function display(){
        return view('frontend.Doctordashboard.Updatedocument');
    }
     public function insert(Request $request): RedirectResponse
{
    $doctor = Auth::guard('doctor')->user();

    $validator = Validator::make($request->all(), [
        'documents.aadhar.number'        => ['required', 'digits:12'],
        'documents.aadhar.file'          => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],

        'documents.license.number'       => ['required', 'string', 'max:100'],
        'documents.license.expiry_date'  => ['nullable', 'date'],
        'documents.license.file'         => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],

        'documents.registration.number'      => ['required', 'string', 'max:100'],
        'documents.registration.issue_date'  => ['nullable', 'date'],
        'documents.registration.file'        => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],

        'documents.degree.number' => ['nullable', 'string', 'max:150'],
        'documents.degree.file'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],

        'documents.photo.file' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:3072'],
    ], [
        'documents.aadhar.number.required'      => 'Aadhar number is required.',
        'documents.aadhar.number.digits'        => 'Aadhar number must be exactly 12 digits.',
        'documents.aadhar.file.required'        => 'Please upload your Aadhar card.',
        'documents.license.number.required'     => 'License number is required.',
        'documents.license.file.required'       => 'Please upload your medical license.',
        'documents.registration.number.required'=> 'Registration number is required.',
        'documents.registration.file.required'  => 'Please upload your clinic registration document.',
        'documents.degree.file.required'        => 'Please upload your degree certificate.',
        'documents.photo.file.required'         => 'Please upload your photo.',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    $documents = $request->input('documents');

    // public/upload/documents/{doctor_id}/
    $destination = public_path('upload/documents/' . $doctor->id);

    foreach ($documents as $key => $doc) {
        $file = $request->file("documents.$key.file");

        if (! $file) {
            continue;
        }

        $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        // relative path saved in DB, resolved with asset() in blade
        $relativePath = 'upload/documents/' . $doctor->id . '/' . $filename;

        doctor_document::updateOrCreate(
            [
                'doctor_id'     => $doctor->id,
                'document_type' => $doc['type'],
            ],
            [
                'document_name'      => $doc['name'],
                'document_number'    => $doc['number'] ?? null,
                'document_file'      => $relativePath,
                'issue_date'         => $doc['issue_date'] ?? null,
                'expiry_date'        => $doc['expiry_date'] ?? null,
                'document_step'      => $doc['step'],
                'document_completed' => 1,
            ]
        );
    }

    if (Schema::hasColumn('doctors', 'document_completed')) {
        $doctor->forceFill([
            'document_completed' => 1,
        ])->save();
    }

    return redirect()
        ->route('nodashboard')
        ->with('success', 'Documents uploaded successfully. They are now pending admin verification.');
}
    public function changepassword()
    {
        return view('frontend.Doctordashboard.changepassword');
    }

    public function updatePassword(Request $request)
    {
        $currentPassword = $request->current_password ?? $request->old_password;

        $request->validate([
            'current_password' => 'required_without:old_password',
            'new_password'     => ['required', \App\Models\SystemSetting::minPasswordRule(), 'confirmed'],
        ]);

        $doctor = Auth::guard('doctor')->user();

        if (!$doctor || !Hash::check($currentPassword, $doctor->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $doctor->password = Hash::make($request->new_password);
        $doctor->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    
}
