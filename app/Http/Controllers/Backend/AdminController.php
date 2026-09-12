<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\company_name;
use App\Models\medicine_categorie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;   
use App\Models\PaymentCategory;
use App\Models\Onboarding;

class AdminController extends Controller
{
    public function index()
    {
        $totalDoctors    = \App\Models\Doctor::where('isdeleted', 0)->count();
        $activeDoctors   = \App\Models\Doctor::where('isdeleted', 0)->where('status', 1)->count();
        $inactiveDoctors = $totalDoctors - $activeDoctors;
        $totalPatients   = \App\Models\Patient::count();
        $todayPatients   = \App\Models\Patient::whereDate('created_at', today())->count();
        $thisMonthPatients = \App\Models\Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $totalMedicines  = \App\Models\Medicine::count();
        $totalOnboarding = \App\Models\User::where('role', 'Onboarding')->where('isdeleted', 0)->count();

        $todayPatientList = \App\Models\Patient::whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();

        // Table: Recent Doctors
        $recentDoctors = \App\Models\Doctor::where('isdeleted', 0)->latest()->take(6)->get();

        // 7 Days Trend Data for ApexCharts (Optimized: 2 bulk queries instead of 14)
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $weeklyDoctors = \App\Models\Doctor::where('isdeleted', 0)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date_str, COUNT(*) as aggregate')
            ->groupBy('date_str')
            ->pluck('aggregate', 'date_str');

        $weeklyPatients = \App\Models\Patient::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date_str, COUNT(*) as aggregate')
            ->groupBy('date_str')
            ->pluck('aggregate', 'date_str');

        $weeklyChartDays = [];
        $weeklyDoctorData = [];
        $weeklyPatientData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->toDateString();
            $weeklyChartDays[] = $date->format('D (d M)');
            $weeklyDoctorData[] = (int) ($weeklyDoctors[$dateKey] ?? 0);
            $weeklyPatientData[] = (int) ($weeklyPatients[$dateKey] ?? 0);
        }

        // 6 Months Trend Data for ApexCharts (Optimized: 2 bulk queries instead of 12)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyDoctors = \App\Models\Doctor::where('isdeleted', 0)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as aggregate")
            ->groupBy('ym')
            ->pluck('aggregate', 'ym');

        $monthlyPatients = \App\Models\Patient::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as aggregate")
            ->groupBy('ym')
            ->pluck('aggregate', 'ym');

        $monthlyChartLabels = [];
        $monthlyDoctorData = [];
        $monthlyPatientData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $ymKey = $monthDate->format('Y-m');
            $monthlyChartLabels[] = $monthDate->format('M Y');
            $monthlyDoctorData[] = (int) ($monthlyDoctors[$ymKey] ?? 0);
            $monthlyPatientData[] = (int) ($monthlyPatients[$ymKey] ?? 0);
        }

        // 1. Onboarding Team Performance (Member-wise Doctor Additions, in-memory aggregation)
        $onboardingMembers = \App\Models\User::where('role', 'Onboarding')
            ->where('isdeleted', 0)
            ->get();

        $doctorsGrouped = \App\Models\Doctor::where('isdeleted', 0)
            ->select('created_by', 'status')
            ->get();

        $onboardingPerformance = $onboardingMembers->map(function ($member) use ($doctorsGrouped) {
            $matching = $doctorsGrouped->filter(function ($doc) use ($member) {
                return ($member->member_id && $doc->created_by == $member->member_id)
                    || ($member->name && $doc->created_by == $member->name)
                    || ($member->id && $doc->created_by == $member->id);
            });

            return (object) [
                'member' => $member,
                'total_doctors' => $matching->count(),
                'active_doctors' => $matching->where('status', 1)->count(),
            ];
        })->sortByDesc('total_doctors')->values()->take(5);

        // 2. Top Performing Doctors (Top 5 aggregated by patient count and revenue)
        $topDoctorStats = \App\Models\Patient::select('doctor_id')
            ->selectRaw('COUNT(patients.id) as patients_count')
            ->whereNotNull('doctor_id')
            ->groupBy('doctor_id')
            ->orderByDesc('patients_count')
            ->take(5)
            ->get();

        $doctorIds = $topDoctorStats->pluck('doctor_id')->filter();
        $doctorsMap = \App\Models\Doctor::whereIn('id', $doctorIds)->get()->keyBy('id');

        $revenues = \App\Models\Patient::whereIn('patients.doctor_id', $doctorIds)
            ->join('payment_categories', 'patients.payment_category_id', '=', 'payment_categories.id')
            ->select('patients.doctor_id')
            ->selectRaw('COALESCE(SUM(payment_categories.price), 0) as total_revenue')
            ->groupBy('patients.doctor_id')
            ->pluck('total_revenue', 'doctor_id');

        $topDoctors = $topDoctorStats->filter(function($item) use ($doctorsMap) {
            return isset($doctorsMap[$item->doctor_id]);
        })->map(function($item) use ($doctorsMap, $revenues) {
            return (object) [
                'doctor' => $doctorsMap[$item->doctor_id],
                'patients_count' => (int) $item->patients_count,
                'total_revenue' => (float) ($revenues[$item->doctor_id] ?? 0),
            ];
        })->values();

        // 3. Top Revenue Categories
        $topRevenueCategories = \App\Models\PaymentCategory::select('payment_categories.id', 'payment_categories.name', 'payment_categories.price')
            ->selectRaw('COUNT(patients.id) as total_patients, COALESCE(SUM(payment_categories.price), 0) as total_revenue')
            ->join('patients', 'patients.payment_category_id', '=', 'payment_categories.id')
            ->groupBy('payment_categories.id', 'payment_categories.name', 'payment_categories.price')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        return view('backend.Admin.index', compact(
            'totalDoctors', 'activeDoctors', 'inactiveDoctors', 'totalPatients',
            'todayPatients', 'thisMonthPatients', 'totalMedicines', 'totalOnboarding',
            'todayPatientList', 'recentDoctors', 'weeklyChartDays', 'weeklyDoctorData',
            'weeklyPatientData', 'monthlyChartLabels', 'monthlyDoctorData', 'monthlyPatientData',
            'onboardingPerformance', 'topDoctors', 'topRevenueCategories'
        ));
    }

    /**
     * View all Doctor Profile Correction Requests across clinics for Superadmin
     */
    public function doctorCorrectionRequests(Request $request)
    {
        $baseQuery = \App\Models\DoctorCorrectionRequest::with(['doctor', 'clinic']);

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

        // Search Filter (Doctor Name, Clinic Name, Field, Notes)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $baseQuery->where(function($q) use ($search) {
                $q->where('field_name', 'LIKE', "%{$search}%")
                  ->orWhere('requested_value', 'LIKE', "%{$search}%")
                  ->orWhere('current_value', 'LIKE', "%{$search}%")
                  ->orWhere('reason', 'LIKE', "%{$search}%")
                  ->orWhere('admin_notes', 'LIKE', "%{$search}%")
                  ->orWhereHas('doctor', function($d) use ($search) {
                      $d->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('Doctor_Emp_id', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('clinic', function($c) use ($search) {
                      $c->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('clinic_id', 'LIKE', "%{$search}%");
                  });
            });
        }

        $requests = $baseQuery->latest()->paginate(15)->withQueryString();

        return view('backend.doctor_requests.index', compact(
            'requests',
            'status',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Superadmin Approve Correction Request
     */
    public function approveDoctorCorrectionRequest($id, Request $request)
    {
        $admin = Auth::user();
        $adminName = $admin->name ?? 'Super Admin';

        $correctionReq = \App\Models\DoctorCorrectionRequest::with(['doctor', 'clinic'])->findOrFail($id);

        $adminNotes = $request->input('admin_notes', 'Approved by Super Admin (' . $adminName . ')');

        $correctionReq->update([
            'status'      => 'approved',
            'admin_notes' => $adminNotes,
            'reviewed_by' => 'Super Admin: ' . $adminName,
            'reviewed_at' => \Carbon\Carbon::now(),
        ]);

        // Auto-update doctor table if target field is a standard column
        if ($correctionReq->doctor && $correctionReq->field_name && $correctionReq->field_name !== 'other') {
            $fieldName = $correctionReq->field_name;
            $doctor = $correctionReq->doctor;

            $allowedFields = [
                'name',
                'phone',
                'email',
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
                $doctor->updated_by = 'Super Admin: ' . $adminName;
                $doctor->save();
            }
        }

        return redirect()->back()->with('success', "Request #{$correctionReq->id} approved by Super Admin and Dr. {$correctionReq->doctor->name}'s profile updated successfully!");
    }

    /**
     * Superadmin Reject Correction Request
     */
    public function rejectDoctorCorrectionRequest($id, Request $request)
    {
        $admin = Auth::user();
        $adminName = $admin->name ?? 'Super Admin';

        $correctionReq = \App\Models\DoctorCorrectionRequest::with(['doctor', 'clinic'])->findOrFail($id);

        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Please provide a reason or note for rejecting this request.',
        ]);

        $correctionReq->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => 'Super Admin: ' . $adminName,
            'reviewed_at' => \Carbon\Carbon::now(),
        ]);

        return redirect()->back()->with('info', "Correction request #{$correctionReq->id} has been rejected by Super Admin.");
    }

    public function changePassword($id)
    {
        return view('backend.Admin.changepassword', compact('id'));
    }
    public function updatePassword(Request $request, $id)
    {
        $newPwdRules = \App\Models\SystemSetting::passwordValidationRules();
        $newPwdRules[] = 'confirmed';

        $minLen = \App\Models\SystemSetting::get('min_password_length', 8);

        $request->validate([
            'current_password' => 'required',
            'new_password' => $newPwdRules,
        ], [
            'new_password.min' => "The new password must be at least {$minLen} characters long.",
            'new_password.regex' => 'The new password must meet system complexity requirements (uppercase, numbers, or special characters).',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('changePassword', Auth::id())->with('success', 'Password updated successfully');
    }
   
    //
}
