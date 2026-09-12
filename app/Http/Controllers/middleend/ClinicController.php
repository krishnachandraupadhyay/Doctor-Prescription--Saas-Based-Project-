<?php

namespace App\Http\Controllers\middleend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;

class ClinicController extends Controller
{
    /**
     * Get allowed creator identifiers.
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

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || in_array($user->name, ['Super Admin', 'Admin', 'superadmin', 'admin']))) {
            $query = Clinic::where('isdeleted', 0);
        } else {
            $allowedCreators = $this->getAllowedCreators();
            $query = Clinic::where(function($q) use ($allowedCreators) {
                $q->whereIn('created_by', $allowedCreators)
                  ->orWhereNull('created_by');
            })->where('isdeleted', 0);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_code', 'LIKE', "%{$search}%");
            });
        }

        $clinics = $query->latest()->paginate(10)->withQueryString();

        return view('middleend.Onboarding.manageclinics', compact('clinics'));
    }

    public function permissions(Request $request)
    {
        $user = auth()->user();

        $baseQuery = Clinic::where('isdeleted', 0);
        if (!($user && ($user->role === 'admin' || in_array($user->name, ['Super Admin', 'Admin', 'superadmin', 'admin'])))) {
            $allowedCreators = $this->getAllowedCreators();
            $baseQuery->where(function($q) use ($allowedCreators) {
                $q->whereIn('created_by', $allowedCreators)
                  ->orWhereNull('created_by');
            });
        }

        $allClinicsList = (clone $baseQuery)->orderBy('name')->get(['id', 'name', 'clinic_id', 'clinic_code']);

        $query = clone $baseQuery;

        if ($request->filled('clinic_id')) {
            $query->where('id', $request->clinic_id);
        } elseif ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_code', 'LIKE', "%{$search}%");
            });
        }

        $clinics = $query->latest()->paginate(15)->withQueryString();

        $totalClinics = Clinic::where('isdeleted', 0)->count();
        $verifiedCount = Clinic::where('isdeleted', 0)->where('verified', 1)->count();
        $staffModuleCount = Clinic::where('isdeleted', 0)->where('has_member', 1)->count();
        $paymentModuleCount = Clinic::where('isdeleted', 0)->where('has_payment_category', 1)->count();
        $deletedStaffCount = Clinic::where('isdeleted', 0)->where('has_deleted_staff', 1)->count();

        return view('middleend.Onboarding.clinic_permissions', compact(
            'clinics',
            'allClinicsList',
            'totalClinics',
            'verifiedCount',
            'staffModuleCount',
            'paymentModuleCount',
            'deletedStaffCount'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:clinics,email',
            'password'    => ['required', 'string', \App\Models\SystemSetting::minPasswordRule()],
            'phone'       => 'required|digits:10',
            'address'     => 'required|string|max:500',
            'clinic_code' => 'nullable|string|max:50',
        ], [
            'name.required'   => 'Clinic Name is required.',
            'email.required'  => 'Clinic Email is required.',
            'email.unique'    => 'This Clinic Email is already registered.',
            'phone.required'  => 'Phone number is required.',
            'phone.digits'    => 'Phone number must be exactly 10 digits.',
            'address.required'=> 'Clinic Address is required.',
        ]);

        $lastClinic = Clinic::latest('id')->first();

        if ($lastClinic && $lastClinic->clinic_id) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastClinic->clinic_id);
            $clinicId = 'CLN-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $clinicId = 'CLN-0001';
        }

        $user = auth()->user();
        $creator = ($user && $user->role === 'admin') 
            ? ($user->name ?? 'Super Admin') 
            : ($user->member_id ?? $user->name ?? 'Onboarding');

        $clinic = Clinic::create([
            'clinic_id'   => $clinicId,
            'name'        => $validatedData['name'],
            'email'       => $validatedData['email'],
            'password'    => Hash::make($validatedData['password']),
            'phone'       => $validatedData['phone'],
            'address'     => $validatedData['address'],
            'clinic_code' => $validatedData['clinic_code'] ?? null,
            'status'      => true,
            'isdeleted'   => 0,
            'created_by'  => $creator,
            'updated_by'  => $creator,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Clinic added successfully!',
                'clinic'  => $clinic,
            ]);
        }

        return redirect()->back()->with('success', 'Clinic added successfully!');
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::where('isdeleted', 0)->findOrFail($id);

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:clinics,email,' . $clinic->id,
            'phone'       => 'required|digits:10',
            'address'     => 'required|string|max:500',
            'clinic_code' => 'nullable|string|max:50',
            'password'    => ['nullable', 'string', \App\Models\SystemSetting::minPasswordRule()],
        ], [
            'name.required'   => 'Clinic Name is required.',
            'email.required'  => 'Clinic Email is required.',
            'phone.required'  => 'Phone number is required.',
            'phone.digits'    => 'Phone number must be exactly 10 digits.',
            'address.required'=> 'Clinic Address is required.',
        ]);

        $user = auth()->user();
        $updater = ($user && $user->role === 'admin')
            ? ($user->name ?? 'Super Admin')
            : ($user->member_id ?? $user->name ?? 'Onboarding');

        $updateData = [
            'name'        => $validatedData['name'],
            'email'       => $validatedData['email'],
            'phone'       => $validatedData['phone'],
            'address'     => $validatedData['address'],
            'clinic_code' => $validatedData['clinic_code'] ?? null,
            'updated_by'  => $updater,
        ];

        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $clinic->update($updateData);

        return redirect()->back()->with('success', 'Clinic updated successfully!');
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        $user = auth()->user();
        $updater = ($user && $user->role === 'admin')
            ? ($user->name ?? 'Super Admin')
            : ($user->member_id ?? $user->name ?? 'Onboarding');

        $clinic->update([
            'isdeleted'  => 1,
            'updated_by' => $updater,
        ]);

        return redirect()->back()->with('success', 'Clinic deleted successfully!');
    }

    public function toggleMemberAccess($id)
    {
        $clinic = Clinic::findOrFail($id);

        $clinic->has_member = $clinic->has_member ? 0 : 1;
        $clinic->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $clinic->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'    => true,
                'has_member' => (bool) $clinic->has_member,
                'message'    => $clinic->has_member 
                    ? 'Staff module is now VISIBLE for ' . $clinic->name . '.' 
                    : 'Staff module is now HIDDEN for ' . $clinic->name . '.',
            ]);
        }

        return redirect()->back()->with('success', 'Clinic staff module status updated.');
    }

    public function togglePaymentCategoryAccess($id)
    {
        $clinic = Clinic::findOrFail($id);

        $clinic->has_payment_category = $clinic->has_payment_category ? 0 : 1;
        $clinic->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $clinic->save();

        // Also sync to all doctors under this clinic
        \App\Models\Doctor::where('clinic_id', $clinic->id)->update(['has_payment_category' => $clinic->has_payment_category]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'              => true,
                'has_payment_category' => (bool) $clinic->has_payment_category,
                'message'              => $clinic->has_payment_category 
                    ? 'Payment category is now VISIBLE for ' . $clinic->name . '.' 
                    : 'Payment category is now HIDDEN for ' . $clinic->name . '.',
            ]);
        }

        return redirect()->back()->with('success', 'Clinic payment category status updated.');
    }

    public function toggleDeletedStaffAccess($id)
    {
        $clinic = Clinic::findOrFail($id);

        $clinic->has_deleted_staff = $clinic->has_deleted_staff ? 0 : 1;
        $clinic->updated_by = auth()->user()->member_id ?? auth()->user()->name ?? 'Onboarding';
        $clinic->save();

        // Also sync to all doctors under this clinic
        \App\Models\Doctor::where('clinic_id', $clinic->id)->update(['has_deleted_staff' => $clinic->has_deleted_staff]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'           => true,
                'has_deleted_staff' => (bool) $clinic->has_deleted_staff,
                'message'           => $clinic->has_deleted_staff 
                    ? 'Deleted staff is now VISIBLE for ' . $clinic->name . '.' 
                    : 'Deleted staff is now HIDDEN for ' . $clinic->name . '.',
            ]);
        }

        return redirect()->back()->with('success', 'Clinic deleted staff status updated.');
    }

    public function verify($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->verified = $clinic->verified ? 0 : 1;

        $user = auth()->user();
        $verifier = ($user && $user->role === 'admin')
            ? ($user->name ?? 'Super Admin')
            : ($user->member_id ?? $user->name ?? 'Onboarding');

        $clinic->verified_by = $clinic->verified ? $verifier : null;
        $clinic->verified_at = $clinic->verified ? now() : null;
        $clinic->updated_by  = $verifier;
        $clinic->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'     => true,
                'verified'    => (bool) $clinic->verified,
                'verified_by' => $clinic->verified_by,
                'message'     => $clinic->verified 
                    ? "Clinic {$clinic->name} is now VERIFIED by {$verifier}." 
                    : "Clinic {$clinic->name} is now UNVERIFIED.",
            ]);
        }

        $verifyText = $clinic->verified ? 'verified' : 'unverified';
        return redirect()->back()->with('success', "Clinic {$clinic->name} is now {$verifyText}.");
    }

    public function toggleStatus($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->status = $clinic->status ? 0 : 1;

        $user = auth()->user();
        $updater = ($user && $user->role === 'admin')
            ? ($user->name ?? 'Super Admin')
            : ($user->member_id ?? $user->name ?? 'Onboarding');

        $clinic->updated_by = $updater;
        $clinic->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => (bool) $clinic->status,
                'message' => $clinic->status 
                    ? "Clinic {$clinic->name} is now ACTIVATED." 
                    : "Clinic {$clinic->name} is now DEACTIVATED.",
            ]);
        }

        $statusText = $clinic->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Clinic {$clinic->name} is now {$statusText}.");
    }
}
