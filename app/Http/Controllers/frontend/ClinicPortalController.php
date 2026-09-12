<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Member;
use App\Models\PaymentCategory;
use App\Models\doctor_clinic_document;
use App\Models\DoctorCorrectionRequest;

class ClinicPortalController extends Controller
{
    /**
     * Get the authenticated clinic model.
     */
    private function getClinic()
    {
        return Auth::guard('clinic')->user();
    }

    /**
     * Clinic Dashboard
     */
    public function dashboard()
    {
        $clinic = $this->getClinic();

        $doctors = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->where('isdeleted', 0)->get();

        $totalDoctors = $doctors->count();
        $doctorIds = $doctors->pluck('id')->toArray();

        $totalPatients = Patient::whereIn('doctor_id', $doctorIds)->count();
        $todayPatients = Patient::whereIn('doctor_id', $doctorIds)->whereDate('created_at', today())->count();

        $totalStaff = Member::whereIn('doctor_id', $doctorIds)->where('isdeleted', 0)->count();

        $recentDoctors = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->where('isdeleted', 0)->latest()->take(5)->get();

        $recentPatients = Patient::whereIn('doctor_id', $doctorIds)->latest()->take(5)->get();

        return view('clinic.dashboard', compact(
            'clinic',
            'totalDoctors',
            'totalPatients',
            'todayPatients',
            'totalStaff',
            'recentDoctors',
            'recentPatients'
        ));
    }

    /**
     * Doctors under this clinic
     */
    public function doctors(Request $request)
    {
        $clinic = $this->getClinic();

        $query = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->where('isdeleted', 0);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('Doctor_Emp_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%");
            });
        }

        $doctors = $query->with('doctor_clinic_documents')->latest()->paginate(10)->withQueryString();

        return view('clinic.doctors', compact('clinic', 'doctors'));
    }

    /**
     * Upload Doctor Photo & Signature from Clinic Portal
     */
    public function uploadDoctorDocuments(Request $request, $id)
    {
        $clinic = $this->getClinic();

        $doctor = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->findOrFail($id);

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sign'  => 'nullable|file|mimes:png|max:2048',
        ], [
            'photo.image' => 'Doctor photo must be a valid image file (jpeg, png, jpg, webp).',
            'photo.max'   => 'Doctor photo cannot exceed 2MB in size.',
            'sign.mimes'  => 'Doctor signature must be a PNG file (.png format only).',
            'sign.max'    => 'Doctor signature cannot exceed 2MB in size.',
        ]);

        if (!$request->hasFile('photo') && !$request->hasFile('sign')) {
            return redirect()->back()->with('error', 'Please select at least a photo or signature file to upload.');
        }

        $docDoc = doctor_clinic_document::where('doctor_id', $doctor->id)->first();
        if (!$docDoc) {
            $docDoc = new doctor_clinic_document();
            $docDoc->doctor_id  = $doctor->id;
            $docDoc->clinic_id  = $clinic->id;
            $docDoc->status     = true;
            $docDoc->created_by = $clinic->name;
        }

        $folder = 'doctor_asset';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        // Upload Doctor Photo
        if ($request->hasFile('photo')) {
            if ($docDoc->photo && file_exists(public_path($docDoc->photo))) {
                @unlink(public_path($docDoc->photo));
            }
            $photoExt  = $request->file('photo')->getClientOriginalExtension();
            $photoName = 'doc_photo_' . $doctor->id . '_' . time() . '.' . $photoExt;
            $request->file('photo')->move(public_path($folder), $photoName);
            $docDoc->photo = $folder . '/' . $photoName;
        }

        // Upload Doctor Signature
        if ($request->hasFile('sign')) {
            if ($docDoc->doctor_sign && file_exists(public_path($docDoc->doctor_sign))) {
                @unlink(public_path($docDoc->doctor_sign));
            }
            $signExt  = $request->file('sign')->getClientOriginalExtension();
            $signName = 'doc_sign_' . $doctor->id . '_' . time() . '.' . $signExt;
            $request->file('sign')->move(public_path($folder), $signName);
            $docDoc->doctor_sign = $folder . '/' . $signName;
        }

        $docDoc->updated_by = $clinic->name;
        $docDoc->save();

        return redirect()->back()->with('success', "Photo and Signature for Dr. {$doctor->name} uploaded successfully!");
    }

    /**
     * Store a new doctor under this clinic
     */
    public function storeDoctor(Request $request)
    {
        $clinic = $this->getClinic();

        $request->validate([
            'name'           => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email'          => 'required|email|unique:doctors,email',
            'password'       => 'required|string|min:8|max:16',
            'phone'          => 'nullable|digits:10',
            'specialization' => 'nullable|string|max:255',
        ], [
            'name.regex'     => 'Doctor name must contain letters only.',
            'phone.digits'   => 'Phone number must be exactly 10 digits.',
        ]);

        $doctorId = Doctor::generateDoctorId($clinic->name);

        Doctor::create([
            'Doctor_Emp_id'  => $doctorId,
            'clinic_id'      => $clinic->id,
            'clinic_name'    => $clinic->name,
            'clinic_address' => $clinic->address,
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'specialization' => $request->specialization,
            'password'       => bcrypt($request->password),
            'status'         => true,
            'verified'       => 1,
            'isdeleted'      => 0,
            'created_by'     => $clinic->name,
            'updated_by'     => $clinic->name,
        ]);

        return redirect()->back()->with('success', "Doctor Dr. {$request->name} added successfully!");
    }

    /**
     * Toggle Doctor Active/Inactive Status
     */
    public function toggleDoctorStatus($id)
    {
        $clinic = $this->getClinic();
        $doctor = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->findOrFail($id);

        $doctor->status = !$doctor->status;
        $doctor->updated_by = $clinic->name;
        $doctor->save();

        $statusText = $doctor->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Dr. {$doctor->name} is now {$statusText}.");
    }

    /**
     * Toggle Doctor Verification
     */
    public function verifyDoctor($id)
    {
        $clinic = $this->getClinic();
        $doctor = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->findOrFail($id);

        $doctor->verified = !$doctor->verified;
        $doctor->updated_by = $clinic->name;
        $doctor->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'  => true,
                'verified' => (bool) $doctor->verified,
                'message'  => $doctor->verified 
                    ? "Dr. {$doctor->name} is now VERIFIED." 
                    : "Dr. {$doctor->name} is now UNVERIFIED.",
            ]);
        }

        $verifyText = $doctor->verified ? 'verified' : 'unverified';
        return redirect()->back()->with('success', "Dr. {$doctor->name} is now {$verifyText}.");
    }

    /**
     * Soft delete Doctor
     */
    public function destroyDoctor($id)
    {
        $clinic = $this->getClinic();
        $doctor = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->findOrFail($id);

        $doctor->isdeleted = 1;
        $doctor->updated_by = $clinic->name;
        $doctor->save();

        return redirect()->back()->with('success', "Dr. {$doctor->name} has been removed.");
    }

    /**
     * Patients under this clinic
     */
    public function patients(Request $request)
    {
        $clinic = $this->getClinic();

        $doctors = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($clinic->name)) {
                $q->orWhere(function($sub) use ($clinic) {
                    $sub->whereNull('clinic_id')
                        ->where('clinic_name', $clinic->name);
                });
            }
        })->where('isdeleted', 0)->get();

        $doctorIds = $doctors->pluck('id')->toArray();

        $query = Patient::whereIn('doctor_id', $doctorIds);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('patient_id', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('clinic.patients', compact('clinic', 'patients'));
    }

    /**
     * Clinic Profile View
     */
    public function profile()
    {
        $clinic = $this->getClinic();
        $clinicDocument = doctor_clinic_document::where('clinic_id', $clinic->id)->first();
        return view('clinic.profile', compact('clinic', 'clinicDocument'));
    }

    /**
     * Update Clinic Profile
     */
    public function updateProfile(Request $request)
    {
        $clinic = $this->getClinic();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|digits:10',
            'address'     => 'required|string|max:500',
            'clinic_code' => 'nullable|string|max:50',
        ], [
            'name.required'   => 'Clinic Name is required.',
            'phone.required'  => 'Phone number is required.',
            'phone.digits'    => 'Phone number must be exactly 10 digits.',
            'address.required'=> 'Address is required.',
        ]);

        $clinic->update([
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'address'     => $validated['address'],
            'clinic_code' => $validated['clinic_code'] ?? null,
            'updated_by'  => $clinic->name ?? 'Clinic Portal',
        ]);

        return redirect()->back()->with('success', 'Clinic profile updated successfully!');
    }

    /**
     * Update Clinic Password
     */
    public function updatePassword(Request $request)
    {
        $clinic = $this->getClinic();

        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $clinic->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password does not match.']);
        }

        $clinic->update([
            'password'   => Hash::make($request->password),
            'updated_by' => $clinic->name ?? 'Clinic Portal',
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    /**
     * View Payment Categories assigned to this Clinic
     */
    public function paymentCategories()
    {
        $clinic = $this->getClinic();

        if (empty($clinic->has_payment_category)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Payment Category module is currently disabled for your clinic by the onboarding team.');
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $paymentCategories = PaymentCategory::where(function($q) use ($clinic, $doctorIds) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($doctorIds)) {
                $q->orWhereIn('doctor_id', $doctorIds);
            }
        })->latest()->get();

        return view('clinic.payment_categories', compact('clinic', 'paymentCategories'));
    }

    /**
     * Update Payment Category Price for this Clinic
     */
    public function updatePrice(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:payment_categories,id',
            'price'       => 'required|numeric|min:0',
        ]);

        $clinic = $this->getClinic();

        if (empty($clinic->has_payment_category)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Payment Category module is disabled.');
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $category = PaymentCategory::where('id', $request->category_id)
            ->where(function($q) use ($clinic, $doctorIds) {
                $q->where('clinic_id', $clinic->id);
                if (!empty($doctorIds)) {
                    $q->orWhereIn('doctor_id', $doctorIds);
                }
            })
            ->firstOrFail();

        $category->update([
            'price'      => $request->price,
            'updated_by' => $clinic->name ?? 'Clinic Portal',
        ]);

        return redirect()->back()->with('success', 'Price for "' . $category->name . '" updated successfully!');
    }

    /**
     * View Revisit / Follow-up Fee Rule configuration
     */
    public function revisitRule()
    {
        $clinic = $this->getClinic();
        return view('clinic.revisit_rule', compact('clinic'));
    }

    /**
     * Update Revisit / Follow-up Fee Rule for this Clinic
     */
    public function updateRevisitRule(Request $request)
    {
        $clinic = $this->getClinic();

        $request->validate([
            'has_revisit_rule'         => 'required|in:0,1',
            'revisit_validity_days'    => 'nullable|integer|min:1|max:365',
            'revisit_fee_type'         => 'required|in:free,paid',
            'revisit_discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $hasRule = (bool) $request->input('has_revisit_rule', 0);
        $validityDays = $hasRule ? ($request->input('revisit_validity_days') ?? 7) : ($clinic->revisit_validity_days ?? 7);
        $feeType = $request->input('revisit_fee_type', 'free');
        $discountPercent = $feeType === 'free' ? 100 : ($request->input('revisit_discount_percent') ?? 0);

        $clinic->update([
            'has_revisit_rule'         => $hasRule,
            'revisit_validity_days'    => $validityDays,
            'revisit_fee_type'         => $feeType,
            'revisit_discount_percent' => $discountPercent,
            'updated_by'               => $clinic->name ?? 'Clinic Portal',
        ]);

        $statusText = $hasRule
            ? 'Patient Revisit Rule updated successfully! (Validity: ' . $validityDays . ' days, Policy: ' . ($feeType === 'free' ? 'Free Follow-up / ₹0 Fees' : 'Standard / Custom Fees') . ').'
            : 'Patient Revisit Rule has been DISABLED. Standard consultation charges apply to all visits.';

        return redirect()->back()->with('success', $statusText);
    }

    /**
     * View Clinic Staff Members
     */
    public function staff(Request $request)
    {
        $clinic = $this->getClinic();

        if (empty($clinic->has_member)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Staff management is currently disabled for your clinic by the onboarding team.');
        }

        $doctors = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id)
              ->orWhere('clinic_name', $clinic->name);
        })->where('isdeleted', 0)->get();

        $doctorIds = $doctors->pluck('id')->toArray();

        $members = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name)
              ->orWhere('created_by', (string)$clinic->id);
        })
        ->where(function($q) {
            $q->where('isdeleted', 0)
              ->orWhereNull('isdeleted');
        })
        ->with('doctor')
        ->latest()
        ->get();

        return view('clinic.staff', compact('clinic', 'members', 'doctors'));
    }

    /**
     * Store new Staff Member
     */
    public function storeStaff(Request $request)
    {
        $clinic = $this->getClinic();

        if (empty($clinic->has_member)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Staff module is disabled.');
        }

        $request->validate([
            'name'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email'     => 'required|email|unique:members,email',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:receptionist,staff',
            'doctor_id' => 'nullable|exists:doctors,id',
        ], [
            'name.regex' => 'Member name must contain letters only.',
        ]);

        // Generate Member ID
        $today = Carbon::now()->format('ymd');
        $lastMember = Member::where('member_id', 'LIKE', 'MEM' . $today . '%')
            ->orderByDesc('member_id')
            ->first();

        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_id, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        $memberId = 'MEM' . $today . $nextNumber;

        // If no doctor_id provided, pick first active doctor under this clinic
        $doctorId = $request->doctor_id;
        if (!$doctorId) {
            $firstDoc = Doctor::where('clinic_id', $clinic->id)->where('isdeleted', 0)->first();
            $doctorId = $firstDoc ? $firstDoc->id : null;
        }

        Member::create([
            'doctor_id'  => $doctorId,
            'member_id'  => $memberId,
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'status'     => 'active',
            'isdeleted'  => 0,
            'created_by' => $clinic->name,
            'updated_by' => $clinic->name,
        ]);

        return redirect()->back()->with('success', "Staff member {$request->name} added successfully!");
    }

    /**
     * Update Staff Member
     */
    public function updateStaff(Request $request, $id)
    {
        $clinic = $this->getClinic();

        if (empty($clinic->has_member)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Staff module is disabled.');
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $member = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })->findOrFail($id);

        $request->validate([
            'name'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'role'      => 'required|in:receptionist,staff',
            'doctor_id' => 'nullable|exists:doctors,id',
        ], [
            'name.regex' => 'Member name must contain letters only.',
        ]);

        $data = [
            'name'       => $request->name,
            'role'       => $request->role,
            'updated_by' => $clinic->name,
        ];

        if ($request->filled('doctor_id')) {
            $data['doctor_id'] = $request->doctor_id;
        }

        $member->update($data);

        return redirect()->route('clinic.staff')->with('success', "Staff member {$member->name} updated successfully!");
    }

    /**
     * Toggle Staff Active/Inactive status
     */
    public function toggleStaffStatus($id)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $member = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })->findOrFail($id);

        $member->status = $member->status === 'active' ? 'inactive' : 'active';
        $member->updated_by = $clinic->name;
        $member->save();

        return response()->json([
            'success' => true,
            'status'  => $member->status,
        ]);
    }

    /**
     * Toggle Staff Verification by Clinic
     */
    public function verifyStaff($id)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $member = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })->findOrFail($id);

        $member->verified = !$member->verified;
        $member->verified_by = $member->verified ? $clinic->name : null;
        $member->verified_at = $member->verified ? now() : null;
        $member->updated_by = $clinic->name;
        $member->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'     => true,
                'verified'    => (bool) $member->verified,
                'verified_by' => $member->verified_by,
                'message'     => $member->verified 
                    ? "Staff member {$member->name} is now VERIFIED by Clinic." 
                    : "Staff member {$member->name} is now UNVERIFIED.",
            ]);
        }

        $verifyText = $member->verified ? 'verified' : 'unverified';
        return redirect()->back()->with('success', "Staff member {$member->name} is now {$verifyText}.");
    }

    /**
     * Soft delete Staff Member
     */
    public function destroyStaff($id)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $member = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })->findOrFail($id);

        $member->isdeleted = 1;
        $member->updated_by = $clinic->name;
        $member->save();

        return redirect()->back()->with('success', "Staff member {$member->name} moved to deleted staff list.");
    }

    /**
     * View Deleted Staff
     */
    public function deletedStaff()
    {
        $clinic = $this->getClinic();

        if (empty($clinic->has_deleted_staff)) {
            return redirect()->route('clinic.dashboard')->with('error', 'Deleted staff module is currently disabled for your clinic by the onboarding team.');
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $deletedMembers = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })
        ->where('isdeleted', 1)
        ->with('doctor')
        ->latest()
        ->get();

        return view('clinic.deleted_staff', compact('clinic', 'deletedMembers'));
    }

    /**
     * Restore Deleted Staff Member
     */
    public function restoreStaff($id)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        $member = Member::where(function($q) use ($doctorIds, $clinic) {
            if (!empty($doctorIds)) {
                $q->whereIn('doctor_id', $doctorIds);
            }
            $q->orWhere('created_by', $clinic->name);
        })->findOrFail($id);

        $member->isdeleted = 0;
        $member->updated_by = $clinic->name;
        $member->save();

        return redirect()->back()->with('success', "Staff member {$member->name} restored successfully to active team.");
    }

    /**
     * View Selected Prescription Design & Uploaded Clinic Branding Data
     */
    public function prescriptionDesign()
    {
        $clinic = $this->getClinic();

        // Fetch Branding assets uploaded for this clinic
        $document = doctor_clinic_document::where('clinic_id', $clinic->id)->first();

        // Fetch doctors belonging to this clinic (with their individual signs/assets if any)
        $doctors = Doctor::where(function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id)
              ->orWhere('clinic_name', $clinic->name);
        })
        ->where('isdeleted', 0)
        ->with('doctor_clinic_documents')
        ->get();

        return view('clinic.prescription_design', compact('clinic', 'document', 'doctors'));
    }

    /**
     * View Profile Correction Requests raised by Clinic Doctors
     */
    public function correctionRequests(Request $request)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)
            ->orWhere('clinic_name', $clinic->name)
            ->pluck('id')
            ->toArray();

        $baseQuery = DoctorCorrectionRequest::where(function($q) use ($clinic, $doctorIds) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($doctorIds)) {
                $q->orWhereIn('doctor_id', $doctorIds);
            }
        })->with('doctor');

        // Statistics counters
        $totalCount    = (clone $baseQuery)->count();
        $pendingCount  = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'rejected')->count();

        // Status Filter
        $status = $request->get('status', 'all');
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $baseQuery->where('status', $status);
        }

        $requests = $baseQuery->latest()->get();

        return view('clinic.correction_requests', compact(
            'clinic',
            'requests',
            'status',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve a Doctor Profile Correction Request
     */
    public function approveCorrectionRequest($id, Request $request)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)
            ->orWhere('clinic_name', $clinic->name)
            ->pluck('id')
            ->toArray();

        $correctionReq = DoctorCorrectionRequest::where(function($q) use ($clinic, $doctorIds) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($doctorIds)) {
                $q->orWhereIn('doctor_id', $doctorIds);
            }
        })->with('doctor')->findOrFail($id);

        $clinicName = $clinic->name ?? 'Clinic Admin';
        $adminNotes = $request->input('admin_notes', 'Approved by ' . $clinicName);

        $correctionReq->update([
            'status'      => 'approved',
            'admin_notes' => $adminNotes,
            'reviewed_by' => $clinicName,
            'reviewed_at' => Carbon::now(),
        ]);

        // Auto-update doctor table if target field is a standard column
        if ($correctionReq->doctor && $correctionReq->field_name && $correctionReq->field_name !== 'other') {
            $fieldName = $correctionReq->field_name;
            $doctor = $correctionReq->doctor;

            // Map field if necessary (Email excluded: Superadmin only)
            $allowedFields = [
                'name',
                'phone',
                'specialization',
                'qualification',
                'registration_number',
                'experience',
                'clinic_name',
                'clinic_address',
                'license_number',
            ];

            if (in_array($fieldName, $allowedFields)) {
                $doctor->$fieldName = $correctionReq->requested_value;
                $doctor->updated_by = $clinic->name;
                $doctor->save();
            }
        }

        return redirect()->back()->with('success', "Request #{$correctionReq->id} approved and Doctor Dr. {$correctionReq->doctor->name}'s profile updated successfully!");
    }

    /**
     * Reject a Doctor Profile Correction Request
     */
    public function rejectCorrectionRequest($id, Request $request)
    {
        $clinic = $this->getClinic();

        $doctorIds = Doctor::where('clinic_id', $clinic->id)
            ->orWhere('clinic_name', $clinic->name)
            ->pluck('id')
            ->toArray();

        $correctionReq = DoctorCorrectionRequest::where(function($q) use ($clinic, $doctorIds) {
            $q->where('clinic_id', $clinic->id);
            if (!empty($doctorIds)) {
                $q->orWhereIn('doctor_id', $doctorIds);
            }
        })->with('doctor')->findOrFail($id);

        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Please provide a reason or note for rejecting this request.',
        ]);

        $correctionReq->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => $clinic->name,
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('info', "Correction request #{$correctionReq->id} has been rejected.");
    }
}
