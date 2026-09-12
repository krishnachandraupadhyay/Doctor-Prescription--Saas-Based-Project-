@extends('backend.include.layout')

@section('title', 'Physical Examination - Staff Portal')

@section('content')
<style>
    .exam-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 24px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .exam-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f4f9;
        background: linear-gradient(135deg, #f8faff, #f0f5ff);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .exam-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .exam-card-body { padding: 24px; }
    .form-label-exam {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .input-exam {
        border: 1.5px solid #e2e8f0;
        border-radius: 9px 0 0 9px !important;
        font-size: 14px;
        height: 42px;
        color: #1a202c;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background: #fafbfd;
    }
    .input-exam:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 3px rgba(1,98,232,0.1);
        background: #fff;
        z-index: 1;
    }
    .unit-badge {
        background: #f1f5fb;
        border: 1.5px solid #e2e8f0;
        border-left: none;
        border-radius: 0 9px 9px 0 !important;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 0 12px;
        height: 42px;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    .btn-submit-exam {
        background: linear-gradient(135deg, #0162e8, #0150bf);
        color: #fff;
        border: none;
        padding: 11px 32px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(1,98,232,0.3);
        transition: all 0.2s ease;
    }
    .btn-submit-exam:hover {
        background: linear-gradient(135deg, #0150bf, #013fa0);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(1,98,232,0.4);
        color: #fff;
    }
    .patient-info-strip {
        background: linear-gradient(135deg, #f0f7ff, #eaf0fe);
        border: 1px solid #c7d9f8;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 22px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
    }
    .patient-avatar {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0162e8, #05c3fb);
        color: #fff;
        font-weight: 800;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 10px rgba(1,98,232,0.3);
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-heart-pulse-fill text-danger me-2"></i>Physical Examination
                </h4>
                <p class="text-muted mb-0 fs-13">Record patient vitals before doctor consultation</p>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <a href="{{ route('staff.dashboard') }}" class="btn btn-light border fw-semibold fs-13 d-inline-flex align-items-center gap-2 px-4" style="border-radius:9px; height:40px;">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li class="fs-13">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Patient Info Strip --}}
        <div class="patient-info-strip">
            <div class="patient-avatar">
                {{ strtoupper(substr($patient->patient_name ?? 'P', 0, 2)) }}
            </div>
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-0 text-dark fs-15">{{ $patient->patient_name ?? '-' }}</h5>
                <div class="d-flex flex-wrap gap-3 mt-1">
                    <span class="badge bg-light text-dark border font-monospace fs-11 px-2 py-1">
                        {{ $patient->registration ?? $patient->patient_id ?? '-' }}
                    </span>
                    <span class="text-muted fs-12 fw-medium">{{ ucfirst($patient->gender ?? '-') }}</span>
                    @if($patient->age_year)
                        <span class="text-muted fs-12 fw-medium">{{ $patient->age_year }} Yrs</span>
                    @endif
                    @if($patient->mobile)
                        <span class="text-muted fs-12"><i class="bi bi-telephone me-1"></i>{{ $patient->mobile }}</span>
                    @endif
                </div>
            </div>
            <div>
                @if(isset($isEditable) && $isEditable)
                    <span class="badge bg-warning-transparent text-warning px-3 py-2 rounded-pill fs-12 fw-semibold">
                        <i class="bi bi-hourglass-split me-1"></i>Awaiting Examination
                    </span>
                @elseif(isset($existingVitals) && $existingVitals)
                    <span class="badge bg-success-transparent text-success px-3 py-2 rounded-pill fs-12 fw-semibold">
                        <i class="bi bi-check-circle-fill me-1"></i>Exam Recorded Today
                    </span>
                @else
                    <span class="badge bg-secondary-transparent text-secondary px-3 py-2 rounded-pill fs-12 fw-semibold">
                        <i class="bi bi-lock-fill me-1"></i>View Only Mode
                    </span>
                @endif
            </div>
        </div>

        @if(isset($isEditable) && !$isEditable)
            <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center gap-3" style="border-radius:12px; background: #fff8e6; color: #856404;">
                <i class="bi bi-lock-fill fs-3 text-warning"></i>
                <div>
                    <h6 class="mb-0 fw-bold fs-14">View Only Mode</h6>
                    <span class="fs-12">Staff physical examination is in View-Only mode. Parameters cannot be edited unless Receptionist registers or re-registers this patient for today's visit.</span>
                </div>
            </div>
        @elseif(isset($existingVitals) && $existingVitals)
            <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3" style="border-radius:12px; background: #e0f2fe; color: #0369a1;">
                <i class="bi bi-eye-fill fs-3 text-info"></i>
                <div>
                    <h6 class="mb-0 fw-bold fs-14">Today's Physical Examination Recorded</h6>
                    <span class="fs-12">Staff parameters for today's visit have been recorded. You can view recorded vitals below.</span>
                </div>
            </div>
        @endif

        {{-- Physical Examination Form --}}
        <form action="{{ route('staff.physical_exam.store', $patient->id) }}" method="POST" id="physicalExamForm">
            @csrf

            <div class="exam-card">
                <div class="exam-card-header">
                    <h5 class="exam-card-title">
                        <i class="bi bi-heart-pulse-fill text-danger fs-5"></i>
                        Patient Vitals &amp; Clinical Parameters
                    </h5>
                    <span class="badge bg-danger-transparent text-danger px-3 py-2 rounded-pill fs-11 fw-semibold">
                        <i class="bi bi-activity me-1"></i>Physical Examination
                    </span>
                </div>
                <div class="exam-card-body">
                    <div class="row g-4">

                        {{-- Blood Pressure --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-activity text-danger"></i>
                                Blood Pressure (BP) <span class="text-danger ms-1">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="bp" id="bp" class="form-control input-exam" placeholder="e.g. 120/80" value="{{ old('bp', $existingVitals->blood_pressure ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : 'required' }}>
                                <span class="unit-badge">mmHg</span>
                            </div>
                        </div>

                        {{-- Pulse Rate --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-heart-pulse text-danger"></i>
                                Pulse Rate (PR) <span class="text-danger ms-1">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="pr" id="pr" class="form-control input-exam" placeholder="e.g. 72" value="{{ old('pr', $existingVitals->pulse_rate ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : 'required' }}>
                                <span class="unit-badge">bpm</span>
                            </div>
                        </div>

                        {{-- Temperature --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-thermometer-half text-warning"></i>
                                Temperature <span class="text-danger ms-1">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="temperature" id="temperature" class="form-control input-exam" placeholder="e.g. 98.6" value="{{ old('temperature', $existingVitals->temperature ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : 'required' }}>
                                <span class="unit-badge">&deg;F</span>
                            </div>
                        </div>

                        {{-- SpO2 --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-lungs text-info"></i>
                                Oxygen Saturation (SpO2)
                            </label>
                            <div class="input-group">
                                <input type="text" name="spo2" id="spo2" class="form-control input-exam" placeholder="e.g. 98" value="{{ old('spo2', $existingVitals->spo2 ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                <span class="unit-badge">%</span>
                            </div>
                        </div>

                        {{-- Weight --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-speedometer2 text-primary"></i>
                                Weight
                            </label>
                            <div class="input-group">
                                <input type="text" name="weight" id="weight" class="form-control input-exam" placeholder="e.g. 68" value="{{ old('weight', $existingVitals->weight ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                <span class="unit-badge">kg</span>
                            </div>
                        </div>

                        {{-- Height --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-arrows-vertical text-primary"></i>
                                Height
                            </label>
                            <div class="input-group">
                                <input type="text" name="height" id="height" class="form-control input-exam" placeholder="e.g. 172" value="{{ old('height', $existingVitals->height ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                <span class="unit-badge">cm</span>
                            </div>
                        </div>

                        {{-- Blood Group Selection --}}
                        <div class="col-md-4">
                            <label class="form-label-exam">
                                <i class="bi bi-droplet-fill text-danger"></i>
                                Blood Group
                            </label>
                            <div class="input-group">
                                <select name="blood_group" id="blood_group" class="form-select" style="border: 1.5px solid #e2e8f0; border-radius: 9px !important; font-size: 14px; height: 42px; background: #fafbfd; color: #1a202c;" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                    <option value="">Select Blood Group</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group', $existingVitals->blood_groups ?? ($patient->blood_group ?? '')) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Blood Sugar (FBS, PBS, RBS) --}}
                        <div class="col-md-8">
                            <label class="form-label-exam">
                                <i class="bi bi-droplet text-danger"></i>
                                Blood Sugar (mg/dL) &mdash; FBS / PBS / RBS
                            </label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="sugar_fasting" id="sugar_fasting" class="form-control input-exam" placeholder="Fasting (FBS)" value="{{ old('sugar_fasting', $sugarFasting ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                        <span class="unit-badge">FBS</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="sugar_pp" id="sugar_pp" class="form-control input-exam" placeholder="Post Prandial (PBS)" value="{{ old('sugar_pp', $sugarPp ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                        <span class="unit-badge">PBS</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="sugar_random" id="sugar_random" class="form-control input-exam" placeholder="Random (RBS)" value="{{ old('sugar_random', $sugarRandom ?? '') }}" {{ isset($isEditable) && !$isEditable ? 'disabled' : '' }}>
                                        <span class="unit-badge">RBS</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                <a href="{{ route('staff.dashboard') }}" class="btn btn-light border fw-semibold px-5" style="border-radius:10px; height:44px; display:inline-flex; align-items:center; gap:8px;">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                @if(isset($isEditable) && $isEditable)
                    <button type="submit" class="btn-submit-exam">
                        <i class="bi bi-check2-circle fs-5"></i> Save Physical Examination
                    </button>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border px-4 py-2.5 rounded-3 fs-13 fw-semibold">
                        <i class="bi bi-lock-fill me-1"></i> Read-Only Mode
                    </span>
                @endif
            </div>

        </form>

    </div>
</div>
@endsection
