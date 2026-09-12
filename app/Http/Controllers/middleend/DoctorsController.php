<?php

namespace App\Http\Controllers\middleend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\patient;
use App\Models\doctor_clinic_document;
use App\Models\doctor_document;
use Illuminate\Support\Facades\File;    
use App\models\PaymentCategory;
use App\Models\Clinic;

class DoctorsController extends Controller
{
    //
    public function create(Request $request){
        $allowedCreators = $this->getAllowedCreators();
        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        $query = Doctor::with('clinic')->where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('Doctor_Emp_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_name', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%")
                  ->orWhere('created_by', 'LIKE', "%{$search}%");
            });
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return view('middleend.Onboarding.doctors', compact('doctors', 'clinics'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email'     => 'required|email|unique:doctors,email',
            'password'  => 'required|string|max:16|min:8',
            'clinic_id' => 'required|exists:clinics,id',
        ], [
            'name.regex'        => 'Doctor name must contain letters only (numbers are not allowed).',
            'clinic_id.required'=> 'Please select a clinic from the list.',
        ]);
        
        $selectedClinic = Clinic::findOrFail($request->clinic_id);
        $clinicName = $selectedClinic->name;
        $clinicAddress = $selectedClinic->address;
        
        $doctorId = Doctor::generateDoctorId($clinicName);
        
        $userid = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';

        Doctor::create([
            'Doctor_Emp_id' => $doctorId,
            'clinic_id'     => $request->clinic_id,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => bcrypt($request->password),
            'clinic_name'   => $clinicName ?? 'General Clinic',
            'clinic_address'=> $clinicAddress,
            'status'        => true,
            'isdeleted'     => 0,
            'created_by'    => $userid,
            'updated_by'    => $userid,
        ]);

        return redirect()->back()->with('success', 'Doctor added successfully!');
    }

    /**
     * Get allowed creator identifiers: Super Admin + the logged-in onboarding user.
     */
    private function getAllowedCreators(): array
    {
        $user = auth()->user();
        $adminNames = \App\Models\User::where('role', 'admin')->pluck('name')->toArray();
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $superAdminIdentifiers = array_merge($adminNames, $adminIds, ['Super Admin', 'Admin', 'superadmin', 'admin']);

        $userIdentifiers = [];
        if ($user) {
            if (!empty($user->member_id)) {
                $userIdentifiers[] = $user->member_id;
            }
            if (!empty($user->name)) {
                $userIdentifiers[] = $user->name;
            }
            $userIdentifiers[] = (string)$user->id;
            $userIdentifiers[] = 'Onboarding';
        }

        return array_values(array_unique(array_merge($superAdminIdentifiers, $userIdentifiers)));
    }

    public function showdoctor(Request $request)
    {
        $allowedCreators = $this->getAllowedCreators();

        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        $query = Doctor::with('clinic')->where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0);

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('Doctor_Emp_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_name', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%")
                  ->orWhere('created_by', 'LIKE', "%{$search}%");
            });
        }

        $doctors = $query->latest()->paginate(10)->withQueryString();

        return view('middleend.Onboarding.managedoctor', compact('doctors', 'clinics'));
    }

    public function sidebarPermissions(Request $request)
    {
        return redirect()->route('clinics.index');
    }

    public function viewdoctor($id)
    {
        $patientCount = Patient::where('doctor_id', $id)->count();
        $doctor = Doctor::with(['doctor_clinic_documents', 'photoDocument'])->findOrFail($id);
        
        $clinicDoc = doctor_clinic_document::where('doctor_id', $id)
            ->when($doctor->clinic_id, function($q) use ($doctor) {
                $q->orWhere('clinic_id', $doctor->clinic_id);
            })->first();
        $generalDocs = doctor_document::where('doctor_id', $id)->get();
        $paymentCategories = PaymentCategory::where('doctor_id', $id)->get();

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
        if (isset($generalDocs)) {
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
        }

        $members = \App\Models\Member::where(function($q) use ($doctor, $id) {
            $q->where('created_by', $doctor->name)
              ->orWhere('created_by', $doctor->Doctor_Emp_id)
              ->orWhere('created_by', (string)$id);
            if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'doctor_id')) {
                $q->orWhere('doctor_id', $id);
            }
        })->latest()->get();

        return view('middleend.Onboarding.viewdoctor', compact('doctor', 'patientCount', 'members', 'clinicDoc', 'paymentCategories', 'documentsList'));
    }

    public function editdoctors($id)
    {
        $doctor = Doctor::where('isdeleted', 0)->findOrFail($id);
        $allowedCreators = $this->getAllowedCreators();
        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        return view('middleend.Onboarding.editdoctor', compact('doctor', 'clinics'));
    }

    public function updatedoctors(Request $request, $id)
    {
        $doctor = Doctor::where('isdeleted', 0)->findOrFail($id);

        $validatedData = $request->validate([
            'name'             => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email'            => 'required|email|unique:doctors,email,' . $doctor->id,
            'clinic_id'        => 'nullable|exists:clinics,id',
            'clinic_name'      => 'nullable|string|max:255',
            'phone'            => 'nullable|digits:10',
            'specialisation'   => 'nullable|string|max:255',
            'experience'       => 'nullable|string|max:255',
            'qualification'    => 'nullable|string|max:255',
            'clinic_address'   => 'nullable|string|max:500',
        ], [
            'name.regex'   => 'Doctor name must contain letters only (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        $clinicName = $validatedData['clinic_name'] ?? $doctor->clinic_name;
        $clinicAddress = $validatedData['clinic_address'] ?? $doctor->clinic_address;

        if (!empty($request->clinic_id)) {
            $selectedClinic = Clinic::find($request->clinic_id);
            if ($selectedClinic) {
                $clinicName = $selectedClinic->name;
                $clinicAddress = $selectedClinic->address;
            }
        }

        $doctor->update([
            'name'           => $validatedData['name'],
            'email'          => $validatedData['email'],
            'clinic_id'      => $request->clinic_id ?? $doctor->clinic_id,
            'clinic_name'    => $clinicName,
            'phone'          => $validatedData['phone'] ?? $doctor->phone,
            'specialization' => $validatedData['specialisation'] ?? $doctor->specialization,
            'Experience'     => $validatedData['experience'] ?? $doctor->Experience,
            'qualification'  => $validatedData['qualification'] ?? $doctor->qualification,
            'clinic_address' => $clinicAddress,
            'updated_by'     => auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding',
        ]);

        return redirect()->route('/manage.doctor')->with('success', 'Doctor details updated successfully!');
    }

    public function prescrdesign()
    {
        $allowedCreators = $this->getAllowedCreators();

        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        $documents = doctor_clinic_document::whereNotNull('clinic_id')->get()->keyBy('clinic_id');

        return view('middleend.Onboarding.presciptiondesign', compact('clinics', 'documents'));
    }

    public function prescriptiondocstore(Request $request)
    {
        $validated = $request->validate([
            'clinic_id'    => 'required|exists:clinics,id',
            'photo'        => 'nullable|image|max:2048',
            'logo'         => 'nullable|image|max:2048',
            'sign'         => 'nullable|image|max:2048',
            'stamp'        => 'nullable|image|max:2048',
            'clinic_stamp' => 'nullable|image|max:2048',
            'header'       => 'nullable|image|max:4096',
            'footer'       => 'nullable|image|max:4096',
        ]);

        $memberId = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';

        $clinicId = $validated['clinic_id'];
        $existing = doctor_clinic_document::where('clinic_id', $clinicId)->first();

        $data = [
            'clinic_id'  => $clinicId,
            'member_id'  => $memberId,
            'status'     => true,
            'updated_by' => $memberId,
        ];

        if (! $existing) {
            $data['created_by'] = $memberId;
        }

        // Har file field: nayi file aayi to purani delete karke naya path save karo
        foreach (['photo', 'logo', 'sign', 'stamp', 'clinic_stamp', 'header', 'footer'] as $field) {
            if ($request->hasFile($field)) {
                $column = in_array($field, ['photo', 'logo']) ? 'photo' : ($field === 'sign' ? 'doctor_sign' : (in_array($field, ['stamp', 'clinic_stamp']) ? 'clinic_stamp' : $field));

                if ($existing && $existing->{$column} && File::exists(public_path($existing->{$column}))) {
                    File::delete(public_path($existing->{$column}));
                }

                $folder = 'doctor_asset';
                $filename = uniqid() . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->move(public_path($folder), $filename);

                $data[$column] = $folder . '/' . $filename;
            }
        }

        doctor_clinic_document::updateOrCreate(['clinic_id' => $clinicId], $data);

        return redirect()->back()->with('success', 'Clinic branding assets uploaded successfully!');
    }

    public function showPrescriptionType(Request $request, $id = null)
    {
        $allowedCreators = $this->getAllowedCreators();

        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        $selectedClinicId = $id ?? $request->query('clinic_id');

        return view('middleend.Onboarding.prescriptiontype', compact('clinics', 'selectedClinicId'));
    }

    public function updatePrescriptionType(Request $request)
    {
        $request->validate([
            'clinic_id'          => 'required|exists:clinics,id',
            'prescription_type'  => 'required|in:fixed,customize',
        ]);

        $clinic = Clinic::where('isdeleted', 0)->findOrFail($request->clinic_id);

        $clinic->update([
            'prescription_type' => $request->prescription_type,
            'updated_by'        => auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding',
        ]);

        // Sync to doctors under this clinic
        Doctor::where('clinic_id', $clinic->id)->update([
            'prescription_type' => $request->prescription_type
        ]);

        return redirect()->back()->with('success', 'Clinic prescription type updated successfully.');
    }

    public function PaymentMethod()
    {
        $allowedCreators = $this->getAllowedCreators();

        $clinics = Clinic::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0)->latest()->get();

        $paymentCategories = PaymentCategory::with(['clinic', 'doctor'])
            ->where(function($q) use ($allowedCreators) {
                $q->whereIn('created_by', $allowedCreators)
                  ->orWhereNull('created_by');
            })
            ->latest()
            ->get();

        return view('middleend.Onboarding.paymentmethod', compact('clinics', 'paymentCategories'));
    }

    public function paymentstore(Request $request)
    {
        $request->validate([
            'category_name'      => 'required|array|min:1',
            'category_name.*'    => 'required|string|max:255',
            'clinic_id'          => 'required|array|min:1',
            'clinic_id.*'        => 'required|exists:clinics,id',
            'category_status'     => 'required|array',
            'category_status.*'   => 'required|in:0,1',
        ]);

        $createdBy = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';

        foreach ($request->category_name as $index => $name) {
            if (trim($name) === '') {
                continue;
            }

            PaymentCategory::create([
                'name'       => $name,
                'clinic_id'  => $request->clinic_id[$index] ?? null,
                'status'     => $request->category_status[$index] ?? 1,
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);
        }

        return redirect()->back()->with('success', 'Payment category(ies) added successfully.');
    }

    public function paymentCategoryUpdate(Request $request, PaymentCategory $paymentCategory)
    {
        $request->validate([
            'category_name'   => 'required|string|max:255',
            'clinic_id'       => 'required|exists:clinics,id',
            'category_status' => 'required|in:0,1',
        ]);

        $paymentCategory->update([
            'name'       => $request->category_name,
            'clinic_id'  => $request->clinic_id,
            'status'     => $request->category_status,
            'updated_by' => auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding',
        ]);

        return redirect()->back()->with('success', 'Payment category updated successfully.');
    }

    public function toggleStatus($id)
    {
        $category = PaymentCategory::findOrFail($id);

        $category->status = $category->status == 1 ? 0 : 1;
        $category->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $category->save();

        return response()->json([
            'success' => true,
            'status'  => $category->status,
        ]);
    }

    public function toggleMemberAccess($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->has_member = $doctor->has_member ? 0 : 1;
        $doctor->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $doctor->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'    => true,
                'has_member' => (bool) $doctor->has_member,
                'message'    => $doctor->has_member 
                    ? 'Member module is now VISIBLE in Dr. ' . $doctor->name . '\'s sidebar.' 
                    : 'Member module is now HIDDEN from Dr. ' . $doctor->name . '\'s sidebar.',
            ]);
        }

        $statusMsg = $doctor->has_member ? 'visible in doctor\'s sidebar' : 'hidden from doctor\'s sidebar';
        return redirect()->back()->with('success', 'Doctor member menu is now ' . $statusMsg . '.');
    }

    public function togglePaymentCategoryAccess($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->has_payment_category = $doctor->has_payment_category ? 0 : 1;
        $doctor->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $doctor->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'              => true,
                'has_payment_category' => (bool) $doctor->has_payment_category,
                'message'              => $doctor->has_payment_category 
                    ? 'Payment Category is now VISIBLE in Dr. ' . $doctor->name . '\'s sidebar.' 
                    : 'Payment Category is now HIDDEN from Dr. ' . $doctor->name . '\'s sidebar.',
            ]);
        }

        $statusMsg = $doctor->has_payment_category ? 'visible in doctor\'s sidebar' : 'hidden from doctor\'s sidebar';
        return redirect()->back()->with('success', 'Payment Category menu is now ' . $statusMsg . '.');
    }

    public function toggleDeletedStaffAccess($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->has_deleted_staff = $doctor->has_deleted_staff ? 0 : 1;
        $doctor->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $doctor->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'           => true,
                'has_deleted_staff' => (bool) $doctor->has_deleted_staff,
                'message'           => $doctor->has_deleted_staff 
                    ? 'Deleted Staff menu is now VISIBLE in Dr. ' . $doctor->name . '\'s sidebar.' 
                    : 'Deleted Staff menu is now HIDDEN from Dr. ' . $doctor->name . '\'s sidebar.',
            ]);
        }

        $statusMsg = $doctor->has_deleted_staff ? 'visible in doctor\'s sidebar' : 'hidden from doctor\'s sidebar';
        return redirect()->back()->with('success', 'Deleted Staff menu is now ' . $statusMsg . '.');
    }

}