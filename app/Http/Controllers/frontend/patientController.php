<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\dosage_name;
use App\Models\duration_name;
use App\Models\interval_name;
use App\Models\medicine_categorie;
use App\Models\unit_name;
use App\Models\Medicine;
use App\Models\Suggestion;
use App\Models\presciption_data;
use App\Models\symptoms;
use App\Models\diagnosis_test as DiagnosisTest;


class patientController extends Controller
{
    //
     public function index(){
        $doctor = Auth::guard('doctor')->user();
        $doctorId = $doctor ? $doctor->id : null;
        $paymentCategories = \App\Models\PaymentCategory::where(function($q) use ($doctor, $doctorId) {
            $q->where('doctor_id', $doctorId);
            if ($doctor && $doctor->clinic_id) {
                $q->orWhere('clinic_id', $doctor->clinic_id);
            }
        })
        ->where('status', 1)
        ->orderBy('name')
        ->get();
        return view("frontend.Doctordashboard.Addpatient", compact('paymentCategories'));
    }
    public function store(Request $request){
        $id=Auth::guard('doctor')->user()->id;
        
        $validatadata = $request->validate([
            'full_name'           => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'guardian_type'       => 'nullable|string|max:25',
            'guardian_name'       => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'age_year'            => 'nullable|integer|min:0|max:120',
            'age_month'           => 'nullable|integer|min:0|max:11',
            'mobile'              => 'nullable|digits:10',
            'gender'              => 'required|string|min:3|max:15',
            'aadhar'              => 'required|digits:12',
            'address'             => 'nullable|string|max:500',
            'payment_category_id' => 'nullable|exists:payment_categories,id',
        ], [
            'full_name.regex'     => 'Full name must contain letters only (numbers are not allowed).',
            'guardian_name.regex' => 'Guardian name must contain letters only (numbers are not allowed).',
            'mobile.digits'       => 'Mobile number must be exactly 10 digits.',
        ]);
        $ageMonth = $request->filled('age_month') ? $request->age_month : 0;
        $ageYear = $request->filled('age_year') ? $request->age_year : 0;

        $today = Carbon::now()->format('ymd'); // 20260713
        $lastRecord = Patient::whereDate('created_at', Carbon::today())
         ->latest('id')
         ->first();

        if ($lastRecord) {
              $lastNumber = (int) substr($lastRecord->registration, -5);
              $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
           $newNumber = '00001';
        }
        $registrationNumber = $today . $newNumber;
        $lastPatient = Patient::where('patient_id', 'LIKE', 'PAT' . $today . '%')
           ->orderByDesc('patient_id')
           ->first();

        if ($lastPatient) {
    // Last 5 digits nikalo
          $lastNumber = (int) substr($lastPatient->patient_id, -5);
           $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
       // Aaj ka pehla patient
            $nextNumber = '00001';
             }

          $patientId = 'PAT' . $today . $nextNumber;

        $doctorName = Auth::guard('doctor')->user()->name ?? 'Doctor';

        $patient = Patient::create([
            'doctor_id'           => $id,
            'member_id'           => null,
            'payment_category_id' => $request->payment_category_id,
            'patient_id'          => $patientId,
            'registration'        => $registrationNumber,
            'patient_name'        => $request->full_name,
            'guardian_type'       => $request->guardian_type,
            'husband_father_name' => $request->guardian_name,
            'age_year'            => $ageYear,
            'age_month'           => $ageMonth,
            'mobile'              => $request->mobile,
            'gender'              => $request->gender,
            'Registration_date'   => now(),
            'address'             => $request->address,
            'aaddhar_num'         => $request->aadhar,
            'status'              => true,
            'created_by'          => $doctorName,
            'updated_by'          => $doctorName,
        ]);

        return redirect()->route('addsymptoms', $patient->id)->with('success', 'Patient registered successfully. Proceeding to consultation & prescription.');
    }
    public function show(){
        $patients = Patient::with('paymentCategory')->get();
        return view('frontend.Doctordashboard.Addpatientdetails', compact('patients'));
    }
    public function edit($id){ 
        $patient = Patient::with('paymentCategory')->findOrFail($id);
        $doctor = Auth::guard('doctor')->user();
        $doctorId = $doctor ? $doctor->id : null;
        $paymentCategories = \App\Models\PaymentCategory::where(function($q) use ($doctor, $doctorId) {
            $q->where('doctor_id', $doctorId);
            if ($doctor && $doctor->clinic_id) {
                $q->orWhere('clinic_id', $doctor->clinic_id);
            }
        })
        ->where('status', 1)
        ->orderBy('name')
        ->get();
        return view('frontend.Doctordashboard.editpatient', compact('patient', 'paymentCategories'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'full_name'           => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'guardian_type'       => 'nullable|string|max:50',
            'guardian_name'       => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'age_year'            => 'nullable|integer|min:0|max:150',
            'age_month'           => 'nullable|integer|min:0|max:11',
            'mobile'              => 'nullable|digits:10',
            'gender'              => 'required|string|max:20',
            'dob'                 => 'nullable|string|max:20',
            'aadhar'              => 'nullable|string|max:20',
            'address'             => 'nullable|string|max:255',
            'payment_category_id' => 'nullable|exists:payment_categories,id',
        ], [
            'full_name.regex'     => 'Full name must contain letters only (numbers are not allowed).',
            'guardian_name.regex' => 'Guardian name must contain letters only (numbers are not allowed).',
            'mobile.digits'       => 'Mobile number must be exactly 10 digits.',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update([
            'patient_name'        => $request->full_name,
            'payment_category_id' => $request->payment_category_id,
            'guardian_type'       => $request->guardian_type,
            'husband_father_name' => $request->guardian_name,
            'age_year'            => $request->age_year,
            'age_month'           => $request->age_month,
            'mobile'              => $request->mobile,
            'gender'              => $request->gender,
            'dob'                 => $request->dob,
            'address'             => $request->address,
            'aaddhar_num'         => $request->aadhar,
        ]);

        return redirect()->route('symptoms')->with('success', 'Patient record updated successfully.');
    }
    public function view($id){
        $patient = Patient::with('paymentCategory')->findOrFail($id);
        return view('frontend.Doctordashboard.viewpatient', compact('patient'));
    }

 public function symptomsview(Request $request)
{
    $doctor = Auth::guard('doctor')->user();
    $doctorId = $doctor->id;
    $doctorName = $doctor->name;

    // Get all receptionist/staff names created by this doctor
    $staffNames = \App\Models\Member::where('created_by', $doctorName)
        ->pluck('name')
        ->toArray();

    // Query patients assigned to this doctor OR registered by this doctor's receptionists
    $baseQuery = Patient::where(function($q) use ($doctorId, $staffNames) {
        $q->where('doctor_id', $doctorId);
        if (!empty($staffNames)) {
            $q->orWhereIn('created_by', $staffNames);
        }
    });

    // Patient ID dropdown ke liye — is doctor aur iske receptionists ke patients
    $patientIds = (clone $baseQuery)
        ->whereNotNull('patient_id')
        ->orderBy('patient_id')
        ->pluck('patient_id');

    $query = clone $baseQuery;

    // ---- Patient ID wise filter ----
    if ($request->filled('patient_id')) {
        $query->where('patient_id', $request->patient_id);
    }

    // ---- Registration No wise filter ----
    if ($request->filled('registration')) {
        $query->where('registration', 'like', '%' . $request->registration . '%');
    }

    // ---- Patient Name wise filter ----
    if ($request->filled('patient_name')) {
        $query->where('patient_name', 'like', '%' . $request->patient_name . '%');
    }

    // ---- Date range wise filter ----
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereDate('Registration_date', '>=', $request->from_date)
              ->whereDate('Registration_date', '<=', $request->to_date);
    } elseif ($request->filled('from_date')) {
        $query->whereDate('Registration_date', '>=', $request->from_date);
    } elseif ($request->filled('to_date')) {
        $query->whereDate('Registration_date', '<=', $request->to_date);
    }

    // ---- Pagination (10 per page) ----
    $pat = $query->with(['paymentCategory', 'presciption_data'])->latest()->paginate(10);
    return view('frontend.Doctordashboard.Symptoms', compact('pat', 'patientIds'));
}
    public function symptomsadd($id){
        $medicine=Medicine::all();
        $dosage=dosage_name::all();
        $unit=unit_name::all();
        $interval=interval_name::all();
        $duration=duration_name::all();
        $pati = Patient::with('paymentCategory')->findOrFail($id);
        $suggestion=Suggestion::all();
        $symptoms = symptoms::orderBy('symptom_name')->get();
        $diagnosisTests = DiagnosisTest::orderBy('test_name')->get();

        // Staff ne pehle physical exam bhara hai to woh vitals le lo (vitals-only record)
        $existingVitals = presciption_data::where('patient_id', $pati->id)
            ->whereNull('symptoms')
            ->whereNull('medicine_id')
            ->latest()
            ->first();

        if (!$existingVitals) {
            $existingVitals = presciption_data::where('patient_id', $pati->id)
                ->whereNotNull('blood_pressure')
                ->latest()
                ->first();
        }

        return view('frontend.Doctordashboard.addsymptoms',compact('pati','medicine','dosage','unit','interval','duration','suggestion','symptoms','diagnosisTests','existingVitals'));
    }
    public function symptomsstore(Request $request)
{
    $request->validate([
        'patientid'    => 'required',
        'weight'       => 'nullable',
        'height'       => 'nullable',
        'bp'           => 'required',
        'pr'           => 'required',
        'temperature'  => 'required',
        'spo2'         => 'nullable',
        'sugar'        => 'nullable',
        'blood_group'  => 'required',
        'diagnosis'    => 'nullable',
    ]);

    $patient = Patient::where('patient_id', $request->patientid)->orWhere('id', $request->patientid)->firstOrFail();
    $pat_id = $patient->id;
    $payment_category_id = $patient->payment_category_id;
    $doctorId = Auth::guard('doctor')->user()->id;

    // Build a combined sugar string from the three sugar inputs (FBS, PPBS, RBS)
    $sugarValues = [];
    if ($request->filled('sugar_fasting')) {
        $sugarValues[] = 'FBS:' . $request->sugar_fasting;
    }
    if ($request->filled('sugar_pp')) {
        $sugarValues[] = 'PPBS:' . $request->sugar_pp;
    }
    if ($request->filled('sugar_random')) {
        $sugarValues[] = 'RBS:' . $request->sugar_random;
    }
    // fallback to single `sugar` if provided
    $sugar = count($sugarValues) ? implode(',', $sugarValues) : ($request->sugar ?? null);

    /* ---------------------------------------------------------
       1) Naye symptoms ko master list me save karo (agar naye ho)
    --------------------------------------------------------- */
    if ($request->filled('symptoms') && is_array($request->symptoms)) {
        $existingNames = symptoms::pluck('symptom_name')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();

        foreach ($request->symptoms as $symptomName) {
            $symptomName = trim($symptomName);
            if ($symptomName === '') {
                continue;
            }
            if (!in_array(strtolower($symptomName), $existingNames)) {
                symptoms::create([
                    'symptom_name' => $symptomName,
                    'status'       => 1,
                    'created_by'   => Auth::guard('doctor')->user()->name ?? 'Doctor',
                    'updated_by'   => Auth::guard('doctor')->user()->name ?? 'Doctor',
                ]);
                $existingNames[] = strtolower($symptomName);
            }
        }
    }

    /* ---------------------------------------------------------
       2) Naye diagnosis tests ko bhi master list me save karo
    --------------------------------------------------------- */
    if ($request->filled('diagnosis_test') && is_array($request->diagnosis_test)) {
        $existingTestNames = DiagnosisTest::pluck('test_name')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();

        foreach ($request->diagnosis_test as $testName) {
            $testName = trim($testName);
            if ($testName === '') {
                continue;
            }
            if (!in_array(strtolower($testName), $existingTestNames)) {
                DiagnosisTest::create([
                    'test_name'  => $testName,
                    'status'     => 1,
                    'created_by' => Auth::guard('doctor')->user()->name ?? 'Doctor',
                    'updated_by' => Auth::guard('doctor')->user()->name ?? 'Doctor',
                ]);
                $existingTestNames[] = strtolower($testName);
            }
        }
    }

    $medicine  = $request->medicine ?? [];
    $dosage    = $request->dosage ?? [];
    $unit      = $request->unit ?? [];
    $frequency = $request->frequency ?? [];
    $duration  = $request->duration ?? [];

    // Purane prescription records is patient ke delete karo taaki re-submit / edit karne par duplicate medicine rows na bane
    presciption_data::where('patient_id', $pat_id)->delete();

    // Agar ek bhi medicine row add nahi ki gayi ho, tab bhi kam se kam
    // ek record save ho jaye (taaki symptoms/advice/vitals loss na ho)
    $rowCount = count($medicine) > 0 ? count($medicine) : 1;

    for ($i = 0; $i < $rowCount; $i++) {
        $medVal = $medicine[$i] ?? null;
        $medId = null;

        if (!empty($medVal)) {
            if (is_numeric($medVal) && Medicine::where('id', $medVal)->exists()) {
                $medId = (int)$medVal;
            } else {
                $medName = trim($medVal);
                $existingMed = Medicine::where('medicine_name', $medName)->first();
                if ($existingMed) {
                    $medId = $existingMed->id;
                } else {
                    $newMed = Medicine::create([
                        'medicine_name' => $medName,
                        'generic_name'  => null,
                        'category_id'   => null,
                        'company_id'    => null,
                        'description'   => 'Added by Doctor during Prescription',
                        'created_by'    => $doctorId,
                        'updated_by'    => $doctorId,
                    ]);
                    $medId = $newMed->id;
                }
            }
        }

        // Unit resolution / creation
        $unitValRaw = $unit[$i] ?? null;
        $unitId = null;
        if (!empty($unitValRaw)) {
            if (is_numeric($unitValRaw) && unit_name::where('id', $unitValRaw)->exists()) {
                $unitId = (int)$unitValRaw;
            } else {
                $unitNameStr = trim($unitValRaw);
                $existingUnit = unit_name::where('unit_name', $unitNameStr)->first();
                if ($existingUnit) {
                    $unitId = $existingUnit->id;
                } else {
                    $newUnit = unit_name::create([
                        'unit_name' => $unitNameStr,
                        'status'    => 1,
                    ]);
                    $unitId = $newUnit->id;
                }
            }
        }

        // Dosage resolution / creation
        $dosValRaw = $dosage[$i] ?? null;
        $dosId = null;
        if (!empty($dosValRaw)) {
            if (is_numeric($dosValRaw) && dosage_name::where('id', $dosValRaw)->exists()) {
                $dosId = (int)$dosValRaw;
            } else {
                $dosNameStr = trim($dosValRaw);
                $existingDos = dosage_name::where('dosage_name', $dosNameStr)->first();
                if ($existingDos) {
                    $dosId = $existingDos->id;
                } else {
                    $newDos = dosage_name::create([
                        'dosage_name' => $dosNameStr,
                        'unit_id'     => $unitId,
                        'status'      => 1,
                    ]);
                    $dosId = $newDos->id;
                }
            }
        }

        // Frequency (Interval) resolution / creation
        $freqValRaw = $frequency[$i] ?? null;
        $freqId = null;
        if (!empty($freqValRaw)) {
            if (is_numeric($freqValRaw) && interval_name::where('id', $freqValRaw)->exists()) {
                $freqId = (int)$freqValRaw;
            } else {
                $freqNameStr = trim($freqValRaw);
                $existingFreq = interval_name::where('interval_name', $freqNameStr)->first();
                if ($existingFreq) {
                    $freqId = $existingFreq->id;
                } else {
                    $newFreq = interval_name::create([
                        'interval_name' => $freqNameStr,
                        'status'        => 1,
                    ]);
                    $freqId = $newFreq->id;
                }
            }
        }

        // Duration resolution / creation
        $durValRaw = $duration[$i] ?? null;
        $durId = null;
        if (!empty($durValRaw)) {
            if (is_numeric($durValRaw) && duration_name::where('id', $durValRaw)->exists()) {
                $durId = (int)$durValRaw;
            } else {
                $durNameStr = trim($durValRaw);
                $existingDur = duration_name::where('duration_name', $durNameStr)->first();
                if ($existingDur) {
                    $durId = $existingDur->id;
                } else {
                    $newDur = duration_name::create([
                        'duration_name' => $durNameStr,
                        'status'        => 1,
                    ]);
                    $durId = $newDur->id;
                }
            }
        }

        $savedPrescriptionId = $request->prescription_id[$i] ?? null;
        $prescription = $savedPrescriptionId
            ? presciption_data::where('id', $savedPrescriptionId)
                ->where('patient_id', $pat_id)
                ->where('Doctor_Emp_id', $doctorId)
                ->first()
            : null;

        $prescriptionData = [

            'Doctor_Emp_id'       => $doctorId,
            'patient_id'          => $pat_id,
            'payment_category_id' => $payment_category_id,
            'weight'              => $request->weight,
            'height'              => $request->height,
            'blood_pressure'      => $request->bp,
            'pulse_rate'          => $request->pr,
            'temperature'         => $request->temperature,
            'spo2'                => $request->spo2,
            'sugar'               => $sugar,
            'blood_groups'        => $request->blood_group,

            'symptoms' => $request->filled('symptoms')
                ? implode(',', $request->symptoms)
                : null,

            'diagnosis' => $request->diagnosis,

            'diagnosis_test' => $request->filled('diagnosis_test')
                ? implode(',', $request->diagnosis_test)
                : null,

            'medicine_id' => $medId,
            'dosage'      => $dosId    ?? $dosValRaw,
            'unit'        => $unitId   ?? $unitValRaw,
            'frequency'   => $freqId   ?? $freqValRaw,
            'duration'    => $durId    ?? $durValRaw,

            'advice' => $request->filled('advice')
                ? implode(',', $request->advice)
                : null,

            'followup' => $request->filled('followup')
                ? implode(',', $request->followup)
                : null,

            'next_visit_date' => $request->next_visit_date ?? null,
        ];

        if ($prescription) {
            $prescription->update($prescriptionData);
        } else {
            presciption_data::create($prescriptionData);
        }
    }

    // Prescription banne ke baad patient ka status 'completed' mark karna aur updated_at touch karna
    $patient->status = 'completed';
    $patient->touch();
    $patient->save();

    return redirect()->route('prescription.show', [
        'id' => $pat_id
    ])->with('success', 'Prescription Added Successfully');
}

    public function addPrescriptionMedicine(Request $request)
    {
        $request->validate([
            'patientid' => 'required',
            'medicine' => 'required',
            'dosage' => 'nullable',
            'unit' => 'nullable',
            'frequency' => 'nullable',
            'duration' => 'nullable',
        ]);

        $patient = Patient::where('patient_id', $request->patientid)
            ->orWhere('id', $request->patientid)
            ->firstOrFail();
        $medicineId = is_numeric($request->medicine) && Medicine::where('id', $request->medicine)->exists()
            ? (int) $request->medicine
            : null;

        if (!$medicineId) {
            return response()->json(['message' => 'Please select a valid medicine.'], 422);
        }

        $prescription = presciption_data::create([
            'Doctor_Emp_id' => Auth::guard('doctor')->id(),
            'patient_id' => $patient->id,
            'payment_category_id' => $patient->payment_category_id,
            'medicine_id' => $medicineId,
            'dosage' => $request->dosage,
            'unit' => $request->unit,
            'frequency' => $request->frequency,
            'duration' => $request->duration,
        ]);

        return response()->json(['id' => $prescription->id]);
    }

    public function deletePrescriptionMedicine($id)
    {
        presciption_data::where('id', $id)
            ->where('Doctor_Emp_id', Auth::guard('doctor')->id())
            ->delete();

        return response()->json(['status' => 'success']);
    }

    /**
     * Quick add medicine via AJAX if searched medicine is not found.
     */
    public function quickAddMedicine(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
        ]);

        $name = trim($request->medicine_name);
        $medicine = Medicine::where('medicine_name', $name)->first();

        if (!$medicine) {
            $doctorId = Auth::guard('doctor')->id() ?? Auth::id();
            $medicine = Medicine::create([
                'medicine_name' => $name,
                'generic_name'  => null,
                'category_id'   => null,
                'company_id'    => null,
                'description'   => 'Added by Doctor during Prescription',
                'created_by'    => $doctorId,
                'updated_by'    => $doctorId,
            ]);
        }

        return response()->json([
            'status'        => 'success',
            'id'            => $medicine->id,
            'medicine_name' => $medicine->medicine_name,
        ]);
    }

    public function destroy($id){
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->back()->with('success', 'Patient record deleted successfully.');
    }

} 