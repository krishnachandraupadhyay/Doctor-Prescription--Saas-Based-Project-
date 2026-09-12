<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class ReceptionistController extends Controller
{
    /**
     * Receptionist dashboard.
     */
    public function index()
    {
        $member = Auth::guard('member')->user();

        // Sirf receptionist role wale hi is page ko access kar sakte hain
        if (!$member || $member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        // Assigned doctor dhundho (created_by = doctor name)
        $doctor = null;
        if (!empty($member->created_by)) {
            $doctor = \App\Models\Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->first();
        }

        // Doctor ke base par patient filter
        $doctorFilter = $doctor ? ['doctor_id' => $doctor->id] : [];

        $todayPatientsCount   = Patient::where($doctorFilter)
            ->where(function($q) {
                $q->whereDate('created_at', today())
                  ->orWhereDate('updated_at', today())
                  ->orWhereHas('presciption_data', function($p) {
                      $p->whereDate('created_at', today())
                        ->orWhereDate('updated_at', today());
                  });
            })
            ->count();
        $totalPatientsCount   = Patient::where($doctorFilter)->count();
        $thisMonthPatients    = Patient::where($doctorFilter)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $completedTodayCount  = Patient::where($doctorFilter)
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
        $waitingTodayCount    = $todayPatientsCount - $completedTodayCount;

        $todayPatientsList = Patient::with(['paymentCategory', 'doctor'])
            ->where($doctorFilter)
            ->where(function($q) {
                $q->whereDate('created_at', today())
                  ->orWhereDate('updated_at', today())
                  ->orWhereHas('presciption_data', function($p) {
                      $p->whereDate('created_at', today())
                        ->orWhereDate('updated_at', today());
                  });
            })
            ->latest('updated_at')
            ->take(10)
            ->get();

        $recentPatients = Patient::with(['paymentCategory', 'doctor'])
            ->where($doctorFilter)
            ->latest()
            ->take(5)
            ->get();

        // 7 Days Trend Data for ApexCharts
        $weeklyChartDays = [];
        $weeklyPatientData = [];
        $weeklyCompletedData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyChartDays[]   = $date->format('D (d M)');
            $weeklyPatientData[] = Patient::where($doctorFilter)->whereDate('created_at', $date->toDateString())->count();
            $weeklyCompletedData[] = Patient::where($doctorFilter)->whereDate('created_at', $date->toDateString())->where('status', 'completed')->count();
        }

        // 6 Months Trend Data for ApexCharts
        $monthlyChartLabels = [];
        $monthlyPatientData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlyChartLabels[] = $monthDate->format('M Y');
            $monthlyPatientData[] = Patient::where($doctorFilter)->whereMonth('created_at', $monthDate->month)->whereYear('created_at', $monthDate->year)->count();
        }

        return view('frontend.Receptionist.index', compact(
            'member', 'doctor', 'todayPatientsCount', 'totalPatientsCount',
            'completedTodayCount', 'waitingTodayCount', 'thisMonthPatients',
            'todayPatientsList', 'recentPatients',
            'weeklyChartDays', 'weeklyPatientData', 'weeklyCompletedData',
            'monthlyChartLabels', 'monthlyPatientData'
        ));
    }


    public function patients()
    {
        $member = Auth::guard('member')->user();

        // Sirf receptionist role wale hi is page ko access kar sakte hain
        if ($member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        // Locate assigned doctor
        $doctor = null;
        if (!empty($member->doctor_id)) {
            $doctor = \App\Models\Doctor::find($member->doctor_id);
        }
        if (!$doctor && !empty($member->created_by)) {
            $doctor = \App\Models\Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->orWhere('Doctor_Emp_id', $member->created_by)
                ->first();
        }

        // Active payment categories added by onboarding member for this doctor / clinic
        $paymentCategories = collect();
        if ($doctor) {
            $paymentCategories = \App\Models\PaymentCategory::where(function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
                if ($doctor->clinic_id) {
                    $q->orWhere('clinic_id', $doctor->clinic_id);
                }
            })
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        } else {
            $paymentCategories = \App\Models\PaymentCategory::where('status', 1)
                ->orderBy('name')
                ->get();
        }

        $clinic = null;
        if ($doctor && $doctor->clinic_id) {
            $clinic = \App\Models\Clinic::find($doctor->clinic_id);
        } elseif (!empty($member->clinic_id)) {
            $clinic = \App\Models\Clinic::find($member->clinic_id);
        }

        $doctors = \App\Models\Doctor::where('status', 1)->orderBy('name')->get();

        return view('frontend.Receptionist.patients', compact('member', 'doctor', 'clinic', 'paymentCategories', 'doctors'));
    }

    public function storePatient(Request $request)
    {
        $member = Auth::guard('member')->user();

        // Sirf receptionist role wale hi is action ko perform kar sakte hain
        if ($member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'guardian_type'         => 'nullable|string|in:father,husband',
            'guardian_name'         => ['nullable', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'age_year'              => 'nullable|integer|min:0|max:120',
            'age_month'             => 'nullable|integer|min:0|max:11',
            'mobile'                => 'nullable|digits:10',
            'gender'                => 'required|string|in:male,female,other',
            'aadhaar'               => 'required|digits:12',
            'address'               => 'required|string',
            'doctor_id'             => 'nullable|integer',
            'payment_category_id'   => 'required|exists:payment_categories,id',
        ], [
            'name.regex'                   => 'Patient name must contain letters only (numbers are not allowed).',
            'guardian_name.regex'          => 'Guardian name must contain letters only (numbers are not allowed).',
            'mobile.digits'                => 'Mobile number must be exactly 10 digits.',
            'payment_category_id.required' => 'Please select a Payment Category configured for this doctor.',
            'payment_category_id.exists'   => 'The selected Payment Category is invalid.',
        ]);

        $ageMonth = $request->filled('age_month') ? $validated['age_month'] : 0;
        $ageYear  = $request->filled('age_year') ? $validated['age_year'] : 0;

        $today = Carbon::now()->format('ymd');

        $lastRecord = Patient::whereDate('created_at', Carbon::today())
            ->latest('id')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->registration, -5);
            $newNumber  = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        $registrationNumber = $today . $newNumber;

        $lastPatient = Patient::where('patient_id', 'LIKE', 'PAT' . $today . '%')
            ->orderByDesc('patient_id')
            ->first();

        if ($lastPatient) {
            $lastNumber = (int) substr($lastPatient->patient_id, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        $patientId = 'PAT' . $today . $nextNumber;

        $doctor = null;
        if (!empty($member->doctor_id)) {
            $doctor = \App\Models\Doctor::find($member->doctor_id);
        }
        if (!$doctor && !empty($member->created_by)) {
            $doctor = \App\Models\Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->orWhere('Doctor_Emp_id', $member->created_by)
                ->first();
        }

        $doctor_id = $doctor ? $doctor->id : ($request->input('doctor_id') ?? 1);
        $member_id = $member->member_id ?? $member->id;

        Patient::create([
            'doctor_id'            => $doctor_id,
            'member_id'            => $member_id,
            'payment_category_id'  => $validated['payment_category_id'],
            'patient_id'           => $patientId,
            'registration'         => $registrationNumber,
            'patient_name'         => $validated['name'],
            'guardian_type'        => $validated['guardian_type'] ?? null,
            'husband_father_name'  => $validated['guardian_name'] ?? null,
            'age_year'             => $ageYear,
            'age_month'            => $ageMonth,
            'mobile'               => $validated['mobile'] ?? null,
            'gender'               => $validated['gender'],
            'Registration_date'    => now(),
            'address'              => $validated['address'],
            'aaddhar_num'          => $validated['aadhaar'],
            'status'               => true,
            'created_by'           => $member->name,
            'updated_by'           => $member->name,
        ]);

        return redirect()->route('receptionist.dashboard')->with('success', 'Patient registered successfully with selected payment category.');
    }

    /**
     * Fetch active payment categories by doctor for dynamic UI updates.
     */
    public function getDoctorPaymentCategories($doctorId)
    {
        $doctor = \App\Models\Doctor::find($doctorId);
        $categories = \App\Models\PaymentCategory::where(function($q) use ($doctor, $doctorId) {
                $q->where('doctor_id', $doctorId);
                if ($doctor && $doctor->clinic_id) {
                    $q->orWhere('clinic_id', $doctor->clinic_id);
                }
            })
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'status']);

        return response()->json([
            'success'    => true,
            'categories' => $categories,
        ]);
    }

    /**
     * All patients list for the assigned doctor — with search & pagination.
     */
    public function allPatients(Request $request)
    {
        $member = Auth::guard('member')->user();
        if (!$member || $member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        // Find assigned doctor
        $doctor = null;
        if (!empty($member->doctor_id)) {
            $doctor = \App\Models\Doctor::find($member->doctor_id);
        }
        if (!$doctor && !empty($member->created_by)) {
            $doctor = \App\Models\Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->orWhere('Doctor_Emp_id', $member->created_by)
                ->first();
        }

        // Active payment categories for modal selection
        $paymentCategories = collect();
        if ($doctor) {
            $paymentCategories = \App\Models\PaymentCategory::where(function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
                if ($doctor->clinic_id) {
                    $q->orWhere('clinic_id', $doctor->clinic_id);
                }
            })
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        } else {
            $paymentCategories = \App\Models\PaymentCategory::where('status', 1)
                ->orderBy('name')
                ->get();
        }

        $search = $request->input('search', '');

        $query = \App\Models\Patient::with(['paymentCategory', 'doctor'])
            ->latest();

        // Filter by doctor
        if ($doctor) {
            $query->where('doctor_id', $doctor->id);
        }

        // Search by name, mobile, patient_id
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhere('patient_id', 'LIKE', "%{$search}%")
                  ->orWhere('registration', 'LIKE', "%{$search}%");
            });
        }

        $clinic = null;
        if ($doctor && $doctor->clinic_id) {
            $clinic = \App\Models\Clinic::find($doctor->clinic_id);
        } elseif (!empty($member->clinic_id)) {
            $clinic = \App\Models\Clinic::find($member->clinic_id);
        }

        $patients = $query->paginate(10)->withQueryString();

        return view('frontend.Receptionist.all_patients', compact('member', 'doctor', 'clinic', 'patients', 'search', 'paymentCategories'));
    }

    /**
     * Re-register an existing patient for today's visit with selected Payment Category.
     * Retains original registration ID and sends to staff physical exam.
     */
    public function reRegisterForToday(Request $request, $id)
    {
        $member = Auth::guard('member')->user();
        if (!$member || $member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'payment_category_id' => 'required|exists:payment_categories,id',
        ], [
            'payment_category_id.required' => 'Please select a payment category for the doctor.',
        ]);

        $patient = \App\Models\Patient::findOrFail($id);

        // Update patient's payment category, reset status to waiting for today's visit & touch updated_at
        $patient->payment_category_id = $request->payment_category_id;
        $patient->status = 'waiting';
        $patient->updated_at = now();
        $patient->save();

        // Check if already has an unprescribed vitals record today
        $todayVitals = \App\Models\presciption_data::where('patient_id', $patient->id)
            ->whereDate('created_at', Carbon::today())
            ->whereNull('symptoms')
            ->whereNull('medicine_id')
            ->first();

        if ($todayVitals) {
            $todayVitals->update([
                'payment_category_id' => $request->payment_category_id,
            ]);
        } else {
            \App\Models\presciption_data::create([
                'patient_id'          => $patient->id,
                'Doctor_Emp_id'       => $patient->doctor_id,
                'payment_category_id' => $request->payment_category_id,
            ]);
        }

        return redirect()->route('receptionist.dashboard')
            ->with('success', $patient->patient_name . ' (Reg No: ' . ($patient->registration ?? $patient->patient_id) . ') has been registered for today\'s visit. The original registration number is retained and the patient has been added to the staff queue for physical examination.');
    }

    /**
     * View complete date-wise medical history and past consultation records of a patient for Receptionist.
     */
    public function viewHistory($id)
    {
        $member = Auth::guard('member')->user();
        if (!$member || $member->role !== 'receptionist') {
            abort(403, 'Unauthorized access.');
        }

        $patient = Patient::with([
            'doctor',
            'paymentCategory',
            'presciption_data' => function ($q) {
                $q->orderByDesc('created_at');
            },
            'presciption_data.medicine',
            'presciption_data.dosageName',
            'presciption_data.unitName',
            'presciption_data.intervalName',
            'presciption_data.durationName',
            'presciption_data.paymentCategory',
        ])->findOrFail($id);

        return view('frontend.Receptionist.viewhistory', compact('member', 'patient'));
    }
}

