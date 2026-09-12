<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;

class onboardingController extends Controller
{
    public function Onboardingindex()
    {
        $users = User::where('role', 'onboarding')->where('isdeleted', 0)->latest()->paginate(10);
        return view('backend.Admin.onboarding', compact('users'));
    }

    /**
     * List of softly deleted onboarding members (isdeleted = 1).
     */
    public function deletedonboarding()
    {
        $users = User::where('role', 'onboarding')->where('isdeleted', 1)->latest()->paginate(10);
        return view('backend.Admin.deletedonboarding', compact('users'));
    }

    /**
     * Landing page shown after an "onboarding" role user logs in.
     * Renders resources/views/middleend/Onboarding/index.blade.php
     */
    public function dashboard()
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
        $allowedCreators = array_values(array_unique(array_merge($superAdminIdentifiers, $userIdentifiers)));

        // Base doctor query for superadmin and current onboarding member added doctors
        $doctorQuery = Doctor::where(function($q) use ($allowedCreators) {
            $q->whereIn('created_by', $allowedCreators)
              ->orWhereNull('created_by');
        })->where('isdeleted', 0);

        $totalDoctors        = (clone $doctorQuery)->count();
        $activeDoctors       = (clone $doctorQuery)->where('status', 1)->count();
        $inactiveDoctors     = $totalDoctors - $activeDoctors;
        $verifiedDoctors     = (clone $doctorQuery)->where('verified', 1)->count();
        $unverifiedDoctors   = (clone $doctorQuery)->where('verified', 0)->count();
        $fixedCount          = (clone $doctorQuery)->where('prescription_type', 'fixed')->count();
        $customizeCount      = (clone $doctorQuery)->where('prescription_type', 'customize')->count();
        $notSetCount         = (clone $doctorQuery)->whereNull('prescription_type')->count();

        // Patients & Medicines
        $totalPatients       = \App\Models\Patient::count();
        $todayPatients       = \App\Models\Patient::whereDate('created_at', today())->count();
        $thisMonthPatients   = \App\Models\Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $totalMedicines      = \App\Models\Medicine::count();

        // Doctors with relations
        $doctors             = (clone $doctorQuery)->with('paymentCategories')->latest()->get();
        $recentDoctors       = (clone $doctorQuery)->latest()->take(6)->get();

        // Today Patients List
        $todayPatientList    = \App\Models\Patient::whereDate('created_at', today())->latest()->take(10)->get();

        // 7 Days Trend Data for ApexCharts
        $weeklyChartDays     = [];
        $weeklyDoctorData    = [];
        $weeklyPatientData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyChartDays[] = $date->format('D (d M)');
            $weeklyDoctorData[] = (clone $doctorQuery)->whereDate('created_at', $date->toDateString())->count();
            $weeklyPatientData[] = \App\Models\Patient::whereDate('created_at', $date->toDateString())->count();
        }

        // 6 Months Trend Data for ApexCharts
        $monthlyChartLabels  = [];
        $monthlyDoctorData   = [];
        $monthlyPatientData  = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlyChartLabels[] = $monthDate->format('M Y');
            $monthlyDoctorData[] = (clone $doctorQuery)->whereMonth('created_at', $monthDate->month)->whereYear('created_at', $monthDate->year)->count();
            $monthlyPatientData[] = \App\Models\Patient::whereMonth('created_at', $monthDate->month)->whereYear('created_at', $monthDate->year)->count();
        }

        return view('middleend.Onboarding.index', compact(
            'totalDoctors', 'activeDoctors', 'inactiveDoctors', 'verifiedDoctors', 'unverifiedDoctors',
            'fixedCount', 'customizeCount', 'notSetCount', 'doctors', 'recentDoctors',
            'totalPatients', 'todayPatients', 'thisMonthPatients', 'totalMedicines', 'todayPatientList',
            'weeklyChartDays', 'weeklyDoctorData', 'weeklyPatientData',
            'monthlyChartLabels', 'monthlyDoctorData', 'monthlyPatientData'
        ));
    }

    /**
     * Add one or multiple onboarding users.
     * member_id auto-generates like OB-0001, OB-0002 ...
     */
    public function Onboardingstore(Request $request)
    {
        // 1) Basic validation for every row
        $request->validate([
            'name'                    => 'required|array|min:1',
            'name.*'                  => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email'                   => 'required|array|min:1',
            'email.*'                 => 'required|email|distinct|unique:users,email',
            'phone'                   => 'required|array|min:1',
            'phone.*'                 => 'required|digits:10|distinct',
            'password'                => 'required|array|min:1',
            'password.*'              => ['required', 'string', \App\Models\SystemSetting::minPasswordRule()],
            'password_confirmation'   => 'required|array|min:1',
            'password_confirmation.*' => 'required|string',
        ], [
            'name.*.regex'            => 'Name must contain letters only (numbers are not allowed).',
            'phone.*.digits'          => 'Phone number must be exactly 10 digits.',
        ]);

        $names     = $request->name;
        $emails    = $request->email;
        $phones    = $request->phone;
        $passwords = $request->password;
        $confirms  = $request->password_confirmation;

        // 2) Password === Confirm Password check for every row
        foreach ($names as $i => $name) {
            if (($passwords[$i] ?? null) !== ($confirms[$i] ?? null)) {
                return back()
                    ->with('error', 'Password and Confirm Password do not match for row ' . ($i + 1) . '.')
                    ->withInput();
            }
        }

        // 3) Insert — wrapped in a transaction so member_id numbering stays
        //    correct even if multiple rows are being added at once.
        DB::transaction(function () use ($names, $emails, $phones, $passwords) {

            $nextNumber = $this->getNextMemberNumber();

            foreach ($names as $i => $name) {

                User::create([
                    'member_id'  => $this->formatMemberId($nextNumber),
                    'name'       => $name,
                    'email'      => $emails[$i],
                    'phone'      => $phones[$i],
                    'password'   => $passwords[$i], // model's 'hashed' cast auto-hashes this
                    'role'       => 'onboarding',
                    'status'     => 1, // 1 = active
                    'isdeleted'  => 0,
                    'created_by' => 'Super Admin',
                    'updated_by' => 'Super Admin',
                ]);

                $nextNumber++;
            }
        });

        return back()->with('success', 'User(s) added successfully.');
    }

    /**
     * Update a single onboarding user.
     * Password fields optional — blank means "don't change password".
     */
    public function updateonboarding(Request $request, $id)
    {
        $user = User::where('role', 'onboarding')->findOrFail($id);

        $request->validate([
            'name'  => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|digits:10|unique:users,phone,' . $user->id,
        ], [
            'name.regex'   => 'Name must contain letters only (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if ($request->filled('password')) {

            $request->validate([
                'password'               => ['required', 'string', \App\Models\SystemSetting::minPasswordRule()],
                'password_confirmation'  => 'required|string',
            ]);

            if ($request->password !== $request->password_confirmation) {
                return back()
                    ->with('error', 'Password and Confirm Password do not match.')
                    ->withInput();
            }

            $user->password = $request->password; // 'hashed' cast auto-hashes this
        }

        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->updated_by = 'Super Admin';

        $user->save();

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Toggle a user's active/inactive status.
     * status: 1 = active, 0 = inactive (deactivated).
     */
    public function deactivate($id)
    {
        $user = User::where('role', 'onboarding')->findOrFail($id);

        $user->status     = $user->status == 1 ? 0 : 1;
        $user->updated_by = 'Super Admin';
        $user->save();

        $message = $user->status == 1
            ? 'User activated successfully.'
            : 'User deactivated successfully.';

        return back()->with('success', $message);
    }

    /**
     * Soft delete an onboarding user (isdeleted = 1).
     */
    public function destroy($id)
    {
        $user = User::where('role', 'onboarding')->findOrFail($id);
        $user->isdeleted  = 1;
        $user->updated_by = 'Super Admin';
        $user->save();

        return back()->with('success', 'Onboarding member moved to deleted list successfully.');
    }

    /**
     * Restore a soft-deleted onboarding user (isdeleted = 0).
     */
    public function restore($id)
    {
        $user = User::where('role', 'onboarding')->findOrFail($id);
        $user->isdeleted  = 0;
        $user->updated_by = 'Super Admin';
        $user->save();

        return back()->with('success', 'Onboarding member restored successfully.');
    }

    /**
     * Figure out the next sequential number for member_id generation,
     * based on the highest existing onboarding member_id (OB-0007 -> 7).
     */
    private function getNextMemberNumber(): int
    {
        $lastUser = User::where('role', 'onboarding')
            ->where('member_id', 'like', 'OB-%')
            ->orderByRaw('CAST(SUBSTRING(member_id, 4) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        if (!$lastUser || !$lastUser->member_id) {
            return 1;
        }

        $lastNumber = (int) substr($lastUser->member_id, 3); // "OB-0007" -> "0007" -> 7
        return $lastNumber + 1;
    }

    /**
     * Format a number into "OB-0001" style member_id.
     */
    private function formatMemberId(int $number): string
    {
        return 'OB-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}