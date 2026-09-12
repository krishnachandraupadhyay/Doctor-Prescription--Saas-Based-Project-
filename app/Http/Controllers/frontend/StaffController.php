<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\presciption_data;
use App\Models\Doctor;

class StaffController extends Controller
{
    /**
     * Staff dashboard.
     */
    public function index()
    {
        $member = Auth::guard('member')->user();

        if (!$member || $member->role !== 'staff') {
            abort(403, 'Unauthorized access.');
        }

        // Find assigned doctor for this staff member (either by doctor_id or created_by)
        $doctor = null;
        if (!empty($member->doctor_id)) {
            $doctor = Doctor::find($member->doctor_id);
        }
        if (!$doctor && !empty($member->created_by)) {
            $doctor = Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->orWhere('Doctor_Emp_id', $member->created_by)
                ->first();
        }

        $doctorId = $doctor ? $doctor->id : null;

        if ($doctorId) {
            $todayPatientsList = Patient::where('doctor_id', $doctorId)
                ->where(function($q) {
                    $q->whereDate('created_at', today())
                      ->orWhereDate('updated_at', today())
                      ->orWhereHas('presciption_data', function($p) {
                          $p->whereDate('created_at', today());
                      });
                })
                ->latest('updated_at')
                ->get();
            $todayPatientsCount = $todayPatientsList->count();
            $totalPatientsCount = Patient::where('doctor_id', $doctorId)->count();
            // Take only top 5 patients for dashboard index
            $totalPatientsList = Patient::where('doctor_id', $doctorId)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $todayPatientsList = Patient::where(function($q) {
                    $q->whereDate('created_at', today())
                      ->orWhereDate('updated_at', today())
                      ->orWhereHas('presciption_data', function($p) {
                          $p->whereDate('created_at', today());
                      });
                })
                ->latest('updated_at')
                ->get();
            $todayPatientsCount = $todayPatientsList->count();
            $totalPatientsCount = Patient::count();
            $totalPatientsList = Patient::latest()->take(5)->get();
        }

        return view('frontend.Staff.index', compact('member', 'doctor', 'todayPatientsCount', 'totalPatientsCount', 'todayPatientsList', 'totalPatientsList'));
    }

    /**
     * All Patients list for Staff with Search & Physical Exam action.
     */
    public function allPatients(Request $request)
    {
        $member = Auth::guard('member')->user();

        if (!$member || $member->role !== 'staff') {
            abort(403, 'Unauthorized access.');
        }

        $doctor = null;
        if (!empty($member->doctor_id)) {
            $doctor = Doctor::find($member->doctor_id);
        }
        if (!$doctor && !empty($member->created_by)) {
            $doctor = Doctor::where('name', $member->created_by)
                ->orWhere('id', $member->created_by)
                ->orWhere('Doctor_Emp_id', $member->created_by)
                ->first();
        }

        $doctorId = $doctor ? $doctor->id : null;

        $query = Patient::query();
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'LIKE', "%{$search}%")
                  ->orWhere('registration', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhere('aaddhar_num', 'LIKE', "%{$search}%");
            });
        }

        $allPatients = $query->latest()->paginate(10)->withQueryString();

        return view('frontend.Staff.all_patients', compact('member', 'doctor', 'allPatients'));
    }

    /**
     * Physical Examination form for a patient.
     */
    public function physicalExam($id)
    {
        $member = Auth::guard('member')->user();
        if (!$member || $member->role !== 'staff') {
            abort(403, 'Unauthorized access.');
        }

        $patient = Patient::findOrFail($id);

        $existingVitals = presciption_data::where('patient_id', $patient->id)
            ->whereDate('created_at', today())
            ->whereNotNull('blood_pressure')
            ->latest()
            ->first();

        // If no vitals for today, check latest historical vitals for view mode
        if (!$existingVitals) {
            $existingVitals = presciption_data::where('patient_id', $patient->id)
                ->whereNotNull('blood_pressure')
                ->latest()
                ->first();
        }

        $isEditable = $patient->can_staff_add_vitals_today;

        $sugarFasting = null;
        $sugarPp = null;
        $sugarRandom = null;

        if ($existingVitals && $existingVitals->sugar) {
            $parts = explode(',', $existingVitals->sugar);
            foreach ($parts as $part) {
                if (str_contains($part, 'FBS:')) {
                    $sugarFasting = str_replace('FBS:', '', $part);
                } elseif (str_contains($part, 'PBS:') || str_contains($part, 'PPBS:')) {
                    $sugarPp = str_replace(['PBS:', 'PPBS:'], '', $part);
                } elseif (str_contains($part, 'RBS:')) {
                    $sugarRandom = str_replace('RBS:', '', $part);
                }
            }
        }

        return view('frontend.Staff.physical_exam', compact('patient', 'member', 'existingVitals', 'isEditable', 'sugarFasting', 'sugarPp', 'sugarRandom'));
    }

    /**
     * Store Physical Examination data into presciption_data table.
     */
    public function storePhysicalExam(Request $request, $id)
    {
        $member = Auth::guard('member')->user();
        if (!$member || $member->role !== 'staff') {
            abort(403, 'Unauthorized access.');
        }

        $patient = Patient::findOrFail($id);

        if (!$patient->can_staff_add_vitals_today) {
            return redirect()->back()->with('error', 'Physical examination is in View-Only mode. Receptionist must register or re-register patient for today\'s visit to allow editing.');
        }

        $request->validate([
            'bp'            => 'required|string|max:20',
            'pr'            => 'required|string|max:20',
            'temperature'   => 'required|string|max:20',
            'blood_group'   => 'nullable|string|max:10',
            'spo2'          => 'nullable|string|max:20',
            'weight'        => 'nullable|string|max:20',
            'height'        => 'nullable|string|max:20',
            'sugar'         => 'nullable|string|max:100',
            'sugar_fasting' => 'nullable|string|max:50',
            'sugar_pp'      => 'nullable|string|max:50',
            'sugar_random'  => 'nullable|string|max:50',
        ]);

        $patient = Patient::findOrFail($id);

        // Build combined sugar string if FBS/PBS/RBS provided
        $sugarValues = [];
        if ($request->filled('sugar_fasting')) {
            $sugarValues[] = 'FBS:' . $request->sugar_fasting;
        }
        if ($request->filled('sugar_pp')) {
            $sugarValues[] = 'PBS:' . $request->sugar_pp;
        }
        if ($request->filled('sugar_random')) {
            $sugarValues[] = 'RBS:' . $request->sugar_random;
        }
        $sugar = count($sugarValues) ? implode(',', $sugarValues) : ($request->sugar ?? null);

        $doctorId = $patient->doctor_id;
        if (!$doctorId && !empty($member->created_by)) {
            $doc = \App\Models\Doctor::where('name', $member->created_by)->first();
            $doctorId = $doc ? $doc->id : 1;
        }

        // Check if an existing unprescribed vitals record exists FOR TODAY
        $existingRecord = presciption_data::where('patient_id', $patient->id)
            ->whereDate('created_at', today())
            ->whereNull('symptoms')
            ->whereNull('medicine_id')
            ->latest()
            ->first();

        $vitalsPayload = [
            'patient_id'          => $patient->id,
            'Doctor_Emp_id'       => $doctorId,
            'payment_category_id' => $patient->payment_category_id,
            'blood_pressure'      => $request->bp,
            'pulse_rate'          => $request->pr,
            'temperature'         => $request->temperature,
            'spo2'                => $request->spo2,
            'weight'              => $request->weight,
            'height'              => $request->height,
            'blood_groups'        => $request->blood_group,
            'sugar'               => $sugar,
        ];

        if ($existingRecord) {
            $existingRecord->update($vitalsPayload);
        } else {
            presciption_data::create($vitalsPayload);
        }

        // Touch patient so updated_at becomes current timestamp and patient appears in Today's list
        $patient->touch();

        return redirect()->route('staff.dashboard')
            ->with('success', 'Physical Examination saved for ' . $patient->patient_name . '! Doctor will complete the prescription.');
    }
}
