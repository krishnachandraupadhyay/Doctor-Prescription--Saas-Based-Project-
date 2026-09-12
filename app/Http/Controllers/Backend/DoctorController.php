<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\company_name;
use App\Models\medicine_categorie;
use Illuminate\Http\Request;
use App\Models\presciption_data;
use App\Models\doctor_clinic_document;
use App\Models\doctor_document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\PaymentCategory;
use App\Models\Clinic;

class DoctorController extends Controller
{
    public function create()
    {
        $clinicUser = Auth::guard('clinic')->user();
        $clinics = Clinic::where('isdeleted', 0)->latest()->get();

        if ($clinicUser) {
            $doctors = Doctor::where(function($q) use ($clinicUser) {
                $q->where('clinic_id', $clinicUser->id)
                  ->orWhere('clinic_name', $clinicUser->name);
            })->where('isdeleted', 0)->latest()->get();
        } else {
            $doctors = Doctor::where('isdeleted', 0)->latest()->get();
        }

        return view('backend.Admin.adddoctor', compact('doctors', 'clinics', 'clinicUser'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|max:16|min:8',
            'clinic_id' => 'nullable|exists:clinics,id',
            'clinic_name' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'specialization' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'Doctor name must contain letters only (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        $clinicUser = Auth::guard('clinic')->user();
        $clinicId = $request->clinic_id;
        if (empty($clinicId) && $clinicUser) {
            $clinicId = $clinicUser->id;
        }

        $clinicName = $request->clinic_name;
        $clinicAddress = null;

        if ($clinicId) {
            $clinic = Clinic::find($clinicId);
            if ($clinic) {
                $clinicName = $clinic->name;
                $clinicAddress = $clinic->address;
            }
        }

        if (empty($clinicName) && $clinicUser) {
            $clinicName = $clinicUser->name;
            $clinicAddress = $clinicUser->address;
        }

        if (empty($clinicName)) {
            $clinicName = 'General Clinic';
        }

        $creatorName = $clinicUser ? $clinicUser->name : (Auth::user()->name ?? 'Super Admin');

        $doctorId = Doctor::generateDoctorId($clinicName);

        Doctor::create([
            'Doctor_Emp_id' => $doctorId,
            'clinic_id' => $clinicId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'password' => bcrypt($request->password),
            'clinic_name' => $clinicName,
            'status' => true,
            'isdeleted' => 0,
            'created_by' => $creatorName,
            'updated_by' => $creatorName
        ]);

        return redirect()->back()->with('success', 'Doctor added successfully!');
    }
    public function showDoctors()
    {
        $clinicUser = Auth::guard('clinic')->user();
        $clinics = Clinic::where('isdeleted', 0)->latest()->get();

        if ($clinicUser) {
            $doctors = Doctor::where(function($q) use ($clinicUser) {
                $q->where('clinic_id', $clinicUser->id)
                  ->orWhere('clinic_name', $clinicUser->name);
            })->where('isdeleted', 0)->latest()->get();
        } else {
            $doctors = Doctor::where('isdeleted', 0)->latest()->get();
        }

        return view('backend.Admin.adddoctor', compact('doctors', 'clinics', 'clinicUser'));
    }
    public function view($id)
    {
        $doctor = Doctor::with(['photoDocument', 'clinicDocuments'])->findOrFail($id);

        $totalPatients = Patient::where('doctor_id', $id)->count();

        $totalPrescriptions = presciption_data::where('Doctor_Emp_id', $id)
            ->orWhere('Doctor_Emp_id', $doctor->Doctor_Emp_id)
            ->distinct('patient_id')
            ->count();

        if ($totalPrescriptions == 0) {
            $totalPrescriptions = presciption_data::where('Doctor_Emp_id', $id)
                ->orWhere('Doctor_Emp_id', $doctor->Doctor_Emp_id)
                ->count();
        }

        $clinicDoc = doctor_clinic_document::where('doctor_id', $id)->first();
        $generalDocs = doctor_document::where('doctor_id', $id)->get();
        $paymentCategories = PaymentCategory::where(function($q) use ($doctor, $id) {
            $q->where('doctor_id', $id);
            if ($doctor && $doctor->clinic_id) {
                $q->orWhere('clinic_id', $doctor->clinic_id);
            }
        })->get();

        // Fetch staff and receptionist members added by / associated with this doctor
        $members = \App\Models\Member::where(function($q) use ($doctor, $id) {
            $q->where('created_by', $doctor->name)
              ->orWhere('created_by', $doctor->Doctor_Emp_id)
              ->orWhere('created_by', (string)$id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'doctor_id')) {
                $q->orWhere('doctor_id', $id);
            }
        })->latest()->get();

        $documentsList = [];

        // 1. Clinic Documents (Photo, Doctor Signature, Clinic Stamp, Header, Footer)
        if ($clinicDoc) {
            if ($clinicDoc->photo) {
                $documentsList[] = [
                    'title' => 'Doctor Profile Photo',
                    'type' => 'Clinic Asset',
                    'number' => 'N/A',
                    'issue_date' => null,
                    'expiry_date' => null,
                    'file' => $clinicDoc->photo,
                    'status' => 'Uploaded'
                ];
            }
            if ($clinicDoc->doctor_sign) {
                $documentsList[] = [
                    'title' => 'Doctor Signature',
                    'type' => 'Clinic Asset',
                    'number' => 'N/A',
                    'issue_date' => null,
                    'expiry_date' => null,
                    'file' => $clinicDoc->doctor_sign,
                    'status' => 'Uploaded'
                ];
            }
            if ($clinicDoc->clinic_stamp) {
                $documentsList[] = [
                    'title' => 'Clinic Stamp',
                    'type' => 'Clinic Asset',
                    'number' => 'N/A',
                    'issue_date' => null,
                    'expiry_date' => null,
                    'file' => $clinicDoc->clinic_stamp,
                    'status' => 'Uploaded'
                ];
            }
            if ($clinicDoc->header) {
                $documentsList[] = [
                    'title' => 'Prescription Header',
                    'type' => 'Clinic Header/Footer',
                    'number' => 'N/A',
                    'issue_date' => null,
                    'expiry_date' => null,
                    'file' => $clinicDoc->header,
                    'status' => 'Uploaded'
                ];
            }
            if ($clinicDoc->footer) {
                $documentsList[] = [
                    'title' => 'Prescription Footer',
                    'type' => 'Clinic Header/Footer',
                    'number' => 'N/A',
                    'issue_date' => null,
                    'expiry_date' => null,
                    'file' => $clinicDoc->footer,
                    'status' => 'Uploaded'
                ];
            }
        }

        // Direct Doctor table profile uploads fallback
        if ($doctor->logo && !collect($documentsList)->pluck('title')->contains('Doctor Profile Photo')) {
            $documentsList[] = [
                'title' => 'Doctor Profile Logo/Photo',
                'type' => 'Profile Upload',
                'number' => 'N/A',
                'issue_date' => null,
                'expiry_date' => null,
                'file' => 'upload/logo/' . $doctor->logo,
                'status' => 'Uploaded'
            ];
        }
        if ($doctor->signature && !collect($documentsList)->pluck('title')->contains('Doctor Signature')) {
            $documentsList[] = [
                'title' => 'Doctor Signature',
                'type' => 'Profile Upload',
                'number' => 'N/A',
                'issue_date' => null,
                'expiry_date' => null,
                'file' => 'upload/signature/' . $doctor->signature,
                'status' => 'Uploaded'
            ];
        }
        if ($doctor->clinic_stamp && !collect($documentsList)->pluck('title')->contains('Clinic Stamp')) {
            $documentsList[] = [
                'title' => 'Clinic Stamp',
                'type' => 'Profile Upload',
                'number' => 'N/A',
                'issue_date' => null,
                'expiry_date' => null,
                'file' => 'upload/clinic_stamp/' . $doctor->clinic_stamp,
                'status' => 'Uploaded'
            ];
        }

        // 2. General Uploaded Documents (Aadhar, License, Degree, Registration, etc.)
        foreach ($generalDocs as $doc) {
            $documentsList[] = [
                'title' => $doc->document_name ?: ucfirst($doc->document_type),
                'type' => ucfirst($doc->document_type) . ' Document',
                'number' => $doc->document_number ?: ($doctor->registration_number ?? $doctor->license_number ?? 'N/A'),
                'issue_date' => $doc->issue_date,
                'expiry_date' => $doc->expiry_date,
                'file' => $doc->document_file,
                'status' => $doc->document_completed ? 'Completed' : 'Pending'
            ];
        }

        $totalRevenue = Patient::where('patients.doctor_id', $id)
            ->join('payment_categories', 'patients.payment_category_id', '=', 'payment_categories.id')
            ->sum('payment_categories.price') ?? 0;

        return view('backend.Admin.viewdoctor', compact(
            'doctor', 
            'totalPatients', 
            'totalPrescriptions', 
            'totalRevenue',
            'clinicDoc', 
            'generalDocs',
            'paymentCategories',
            'documentsList',
            'members'
        ));
    }
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $clinics = Clinic::where('isdeleted', 0)->latest()->get();
        return view('backend.Admin.editdoctor', compact('doctor', 'clinics'));
    }
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'clinic_id' => 'nullable|exists:clinics,id',
            'clinic_name' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'specialization' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'Doctor name must contain letters only (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        $clinicId = $request->clinic_id ?? $doctor->clinic_id;
        $clinicName = $request->clinic_name ?? $doctor->clinic_name;
        $clinicAddress = $doctor->clinic_address;

        if ($request->filled('clinic_id')) {
            $clinic = Clinic::find($request->clinic_id);
            if ($clinic) {
                $clinicName = $clinic->name;
                $clinicAddress = $clinic->address;
            }
        }

        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'clinic_id' => $clinicId,
            'clinic_name' => $clinicName,
            'clinic_address' => $clinicAddress,
            'phone' => $request->phone ?? $doctor->phone,
            'specialization' => $request->specialization ?? $doctor->specialization,
            'updated_by' => Auth::user()->name ?? 'Super Admin'
        ]);

        return redirect()->back()->with('success', 'Doctor updated successfully!');
    } 
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->isdeleted = 1;
        $updater = Auth::guard('clinic')->user() ? Auth::guard('clinic')->user()->name : (Auth::user()->name ?? 'Super Admin');
        $doctor->updated_by = $updater;
        $doctor->save();

        return redirect()->back()->with('success', 'Doctor moved to deleted list successfully.');
    }
    public function verify($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->verified = $doctor->verified ? 0 : 1;
        $updater = Auth::guard('clinic')->user() ? Auth::guard('clinic')->user()->name : (Auth::user()->name ?? 'Super Admin');
        $doctor->updated_by = $updater;
        $doctor->save();

        $verifyText = $doctor->verified ? 'verified' : 'unverified';
        return redirect()->back()->with('success', "Doctor Dr. {$doctor->name} is now {$verifyText}.");
    }

    public function toggleStatus($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = $doctor->status ? 0 : 1;
        $updater = Auth::guard('clinic')->user() ? Auth::guard('clinic')->user()->name : (Auth::user()->name ?? 'Super Admin');
        $doctor->updated_by = $updater;
        $doctor->save();

        $statusText = $doctor->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Doctor Dr. {$doctor->name} has been {$statusText} successfully.");
    }
    public function deleteddoctor(){
         $doctors = Doctor::where('isdeleted', 1)
                ->orderBy('id', 'desc')
                ->get();

          return view('backend.Admin.deleteddoctor', compact('doctors'));
    }
    
    public function doctor()
   {
    return $this->belongsTo(Doctor::class, 'doctor_id');
   }
   public function patientdetail(){
        $symptoms=presciption_data::all();
        $patients=Patient::with('doctor')
                ->orderBy('id', 'desc')
                ->get();
        return view('backend.Admin.patientdetail', compact('patients','symptoms'));
    }
    public function medicinedetails(Request $request)
    {
        $query = Medicine::with(['company', 'category'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('medicine_name', 'LIKE', "%{$search}%")
                  ->orWhere('generic_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function ($c) use ($search) {
                      $c->where('company_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('category', function ($cat) use ($search) {
                      $cat->where('category_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $medicines = $query->paginate(15)->withQueryString();

        return view('backend.Admin.medicinedetails', compact('medicines'));
    }
    public function promotedoctor($id){
        $doctor = Doctor::findOrFail($id);
        $doctor->isdeleted = 0;
        $doctor->updated_by = Auth::user()->name ?? 'Super Admin';
        $doctor->save();

        return redirect()->back()->with('success', 'Doctor restored to active management successfully.');

    }
    public function doctorlist(){
        $doctor=Doctor::all();
        return view('backend.Admin.changepassworddoctor',compact('doctor'));
    }
    public function updatePassword(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|exists:doctors,id',
        'password' => 'required|min:6|confirmed', // confirmed => password_confirmation field check karega
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // User dhoondo (kyunki ab hum $doctor->id bhej rahe hain)
    $user = Doctor::find($request->employee_id);

    if (!$user) {
        return redirect()->back()
            ->with('error', 'Doctor not found.')
            ->withInput();
    }

    // Password update karo
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('Doctor.changePassword')
        ->with('success', 'Password changed successfully.');
}
    public function clinicDocuments()
    {
        return redirect()->route('nodashboard')->with('error', 'Clinic documents are managed exclusively by Clinic Administration.');
    }
public function paymentcategory(){
    $doctor = Auth::guard('doctor')->user();
    if ($doctor && isset($doctor->has_payment_category) && !$doctor->has_payment_category) {
        return redirect()->route('nodashboard')->with('error', 'Payment Category module is disabled for your account by onboarding team.');
    }

    $doctorId = Auth::guard('doctor')->id();
    $paymentCategories = PaymentCategory::where(function($q) use ($doctor, $doctorId) {
        $q->where('doctor_id', $doctorId);
        if ($doctor && $doctor->clinic_id) {
            $q->orWhere('clinic_id', $doctor->clinic_id);
        }
    })->get();

    return view('frontend.Doctordashboard.paymentcategory', compact('paymentCategories'));
}

public function updatePrice(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:payment_categories,id',
        'price'        => 'required|numeric|min:0',
    ]);

    $doctor = Auth::guard('doctor')->user();
    $doctorId = Auth::guard('doctor')->id();

    // security check — sirf apni hi category ka price update kar sake
    $category = PaymentCategory::where('id', $request->category_id)
                    ->where(function($q) use ($doctor, $doctorId) {
                        $q->where('doctor_id', $doctorId);
                        if ($doctor && $doctor->clinic_id) {
                            $q->orWhere('clinic_id', $doctor->clinic_id);
                        }
                    })
                    ->firstOrFail();

    $category->update([
        'price' => $request->price,
    ]);

    return redirect()->back()->with('success', 'Price updated successfully!');
}
public function paymentcategorys(){
    $doctor=Doctor::all();
    $paymentCategories = PaymentCategory::with(['doctor', 'clinic'])->get();
    return view('backend.Admin.paymentcategorys', compact('paymentCategories','doctor'));
}

    /**
     * Superadmin view patient details — displays who added the patient, assigned doctor, total visits, vitals, medicines, tests, etc.
     */
    public function viewPatient($id)
    {
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

        return view('backend.Admin.viewpatient', compact('patient'));
    }
}