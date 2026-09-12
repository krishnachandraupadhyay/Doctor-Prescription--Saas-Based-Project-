<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Member;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::guard('doctor')->check()) {
            return redirect()->route('nodashboard');
        }
        if (Auth::guard('clinic')->check()) {
            return redirect()->route('clinic.dashboard');
        }
        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
            if ($member && $member->role === 'receptionist') {
                return redirect()->route('receptionist.dashboard');
            }
            if ($member && $member->role === 'staff') {
                return redirect()->route('staff.dashboard');
            }
        }
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->role === 'onboarding') {
                return redirect()->route('onboarding.dashboard');
            }
            return redirect()->route('Admindashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $password = $request->password;

        // 1. User (Admin / Onboarding) check
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {

            // Deleted check
            if ($user->isdeleted) {
                return back()->with('error', 'Your account has been deleted. Please contact the administrator.');
            }

            // Active/status check — inactive user login nahi kar sakta
            if (! $user->status) {
                return back()->with('error', 'Your account is inactive. Please contact the administrator.');
            }

            Auth::login($user);

            // Role ke hisaab se redirect
            if ($user->role === 'onboarding') {
                return redirect()->route('onboarding.dashboard');
            }

            return redirect()->route('Admindashboard');
        }

        // 2. Clinic check
        $clinic = Clinic::where('email', $email)->first();

        if ($clinic && Hash::check($password, $clinic->password)) {

            if ($clinic->isdeleted) {
                return back()->with('error', 'Your clinic account has been deleted. Please contact the administrator.');
            }

            if (! $clinic->status) {
                return back()->with('error', 'Your clinic account is inactive. Please contact the administrator.');
            }

            // Verified check — unverified clinic login nahi kar sakta
            if (! $clinic->verified) {
                return back()->with('error', 'Your clinic account is pending verification. It must be verified by Super Admin or an Onboarding Member before you can log in.');
            }

            // Subscription check — bina active subscription ke clinic login nahi kar sakta
            $clinicSubscription = $clinic->currentSubscription();
            if (!$clinicSubscription || !$clinicSubscription->isOperable()) {
                $status = $clinicSubscription ? $clinicSubscription->status : 'none';
                if ($status === 'expired' || ($clinicSubscription && $clinicSubscription->isExpired())) {
                    $endDate = \Carbon\Carbon::parse($clinicSubscription->end_date)->format('d M Y');
                    return back()->with('error', "Your clinic's subscription expired on {$endDate}. Please renew your subscription to access the portal.");
                }
                if ($status === 'suspended') {
                    return back()->with('error', "Your clinic's subscription has been suspended. Please contact the administrator.");
                }
                return back()->with('error', "Your clinic does not have an active subscription plan. Please contact the administrator to activate a plan.");
            }

            Auth::guard('clinic')->login($clinic);
            return redirect()->route('clinic.dashboard');
        }

        // 3. Doctor check
        $doctor = Doctor::where('email', $email)->first();

        if ($doctor && Hash::check($password, $doctor->password)) {

            // Deleted check — deleted doctor login nahi kar sakta
            if ($doctor->isdeleted) {
                return back()->with('error', 'Your doctor account has been deleted. Please contact the administrator.');
            }

            // Parent Clinic Verification Check — jab tak clinic verify nahi tab tak doctor login nahi kar sakta
            $parentClinic = null;
            if ($doctor->clinic_id) {
                $parentClinic = Clinic::find($doctor->clinic_id);
            } elseif (!empty($doctor->clinic_name)) {
                $parentClinic = Clinic::where('name', $doctor->clinic_name)->first();
            }

            if ($parentClinic && ! $parentClinic->verified) {
                return back()->with('error', 'Your clinic (' . $parentClinic->name . ') is pending verification by Super Admin / Onboarding. Doctor login is blocked until your clinic is verified.');
            }

            // Active/status check — inactive doctor login nahi kar sakta
            if (! $doctor->status) {
                return back()->with('error', 'Your account is inactive. Please contact your clinic administrator.');
            }

            // Doctor Verification check — unverified doctor login nahi kar sakta jab tak clinic verify na kare
            if (! $doctor->verified) {
                return back()->with('error', 'Your doctor account is pending verification by your clinic. Please contact your clinic admin to verify your account.');
            }

            // Parent Clinic Subscription check — doctor apne clinic ke subscription par depend karta hai
            if (!$parentClinic) {
                return back()->with('error', 'Your doctor account is not linked to any registered clinic. Please contact the administrator.');
            }

            $clinicSubscription = $parentClinic->currentSubscription();
            if (!$clinicSubscription || !$clinicSubscription->isOperable()) {
                $status = $clinicSubscription ? $clinicSubscription->status : 'none';
                if ($status === 'expired' || ($clinicSubscription && $clinicSubscription->isExpired())) {
                    $endDate = \Carbon\Carbon::parse($clinicSubscription->end_date)->format('d M Y');
                    return back()->with('error', "Access Denied: Your clinic's ({$parentClinic->name}) subscription expired on {$endDate}. Doctor login is disabled until the clinic subscription is renewed.");
                }
                if ($status === 'suspended') {
                    return back()->with('error', "Access Denied: Your clinic's ({$parentClinic->name}) subscription is suspended. Please contact your clinic administrator.");
                }
                return back()->with('error', "Access Denied: Your clinic ({$parentClinic->name}) does not have an active subscription plan.");
            }

            Auth::guard('doctor')->login($doctor);
            return redirect()->route('nodashboard');
        }

        // 4. Member (Receptionist / Staff) check
        $member = Member::where('email', $email)->first();

        if ($member && Hash::check($password, $member->password)) {

            // Deleted check — softly deleted member login nahi kar sakta
            if ($member->isdeleted) {
                return back()->with('error', 'Your account has been deleted. Please contact your administrator.');
            }

            // Parent Clinic Verification Check — jab tak clinic verify nahi tab tak staff login nahi kar sakta
            $parentClinic = $member->clinic();

            if ($parentClinic && ! $parentClinic->verified) {
                return back()->with('error', 'Your clinic (' . $parentClinic->name . ') is pending verification by Super Admin / Onboarding. Staff login is blocked until your clinic is verified.');
            }

            // Active/status check — inactive member login nahi kar sakta
            if ($member->status !== 'active') {
                return back()->with('error', 'Your account is inactive. Please contact your clinic administrator.');
            }

            // Member Verification check — unverified staff login nahi kar sakta jab tak clinic verify na kare
            if (! $member->verified) {
                return back()->with('error', 'Your staff account is pending verification by your clinic. Please contact your clinic admin to verify your account.');
            }

            // Parent Clinic Subscription check — staff apne clinic ke subscription par depend karta hai
            if (!$parentClinic) {
                return back()->with('error', 'Your staff account is not linked to any registered clinic. Please contact the administrator.');
            }

            $clinicSubscription = $parentClinic->currentSubscription();
            if (!$clinicSubscription || !$clinicSubscription->isOperable()) {
                $status = $clinicSubscription ? $clinicSubscription->status : 'none';
                if ($status === 'expired' || ($clinicSubscription && $clinicSubscription->isExpired())) {
                    $endDate = \Carbon\Carbon::parse($clinicSubscription->end_date)->format('d M Y');
                    return back()->with('error', "Access Denied: Your clinic's ({$parentClinic->name}) subscription expired on {$endDate}. Staff login is disabled until the clinic subscription is renewed.");
                }
                if ($status === 'suspended') {
                    return back()->with('error', "Access Denied: Your clinic's ({$parentClinic->name}) subscription is suspended. Please contact your clinic administrator.");
                }
                return back()->with('error', "Access Denied: Your clinic ({$parentClinic->name}) does not have an active subscription plan.");
            }

            Auth::guard('member')->login($member);

            // Role ke hisaab se redirect
            if ($member->role === 'receptionist') {
                return redirect()->route('receptionist.dashboard');
            }

            if ($member->role === 'staff') {
                return redirect()->route('staff.dashboard');
            }

            Auth::guard('member')->logout();
            return back()->with('error', 'No dashboard assigned for your role. Please contact the administrator.');
        }

        return back()->with('error', 'Invalid Email or Password');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('doctor')->logout();
        Auth::guard('member')->logout();
        Auth::guard('clinic')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, post-check=0, pre-check=0',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Sun, 02 Jan 1990 00:00:00 GMT',
        ]);
    }
}