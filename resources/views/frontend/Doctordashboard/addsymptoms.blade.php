@extends("frontend.include.layout")

@section('title', 'Write Prescription & Symptoms - Doctor Portal')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    /* Select2 Custom Valex Theme Styles */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 40px !important;
        height: 40px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #0162e8 !important;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12) !important;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
        font-size: 13px !important;
        z-index: 9999 !important;
    }
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #0162e8 !important;
        color: #ffffff !important;
    }
    .select2-container--bootstrap-5 .select2-search__field {
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
    }
    .select2-container--bootstrap-5 .select2-search__field:focus {
        border-color: #0162e8 !important;
        outline: none !important;
    }
    .select2-container--bootstrap-5 .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 13px !important;
    }
    .select2-container--bootstrap-5 .select2-selection__placeholder {
        color: #94a3b8 !important;
    }

    /* Clean Valex Form Styles */
    .valex-rx-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        transition: all 0.2s ease;
    }
    .valex-rx-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
    }
    .btn-edit-vitals {
        background-color: #ffffff !important;
        color: #059669 !important;
        border: 1.5px solid #10b981 !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        padding: 4px 14px !important;
        border-radius: 30px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        cursor: pointer;
    }
    .btn-edit-vitals:hover, .btn-edit-vitals:focus {
        background-color: #10b981 !important;
        color: #ffffff !important;
        border-color: #10b981 !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35) !important;
    }
    .btn-valex-primary {
        background: linear-gradient(135deg, #0162e8, #0150bf) !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(1, 98, 232, 0.25) !important;
    }
    .btn-valex-primary:hover, .btn-valex-primary:focus {
        background: linear-gradient(135deg, #0150bf, #013fa0) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(1, 98, 232, 0.35) !important;
    }
    .btn-valex-success {
        background: linear-gradient(135deg, #16a34a, #15803d) !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.25) !important;
    }
    .btn-valex-success:hover, .btn-valex-success:focus {
        background: linear-gradient(135deg, #15803d, #166534) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35) !important;
    }
    .valex-rx-header {
        padding: 16px 22px;
        border-bottom: 1px solid #f1f4f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fbfcfe;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }
    .valex-rx-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .valex-rx-body {
        padding: 22px;
    }

    /* Input Styling */
    .form-label-valex {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748b;
        margin-bottom: 6px;
    }
    .input-valex {
        height: 40px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .input-valex:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12);
    }

    /* Interactive Chips */
    .symptom-chip-valex {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: rgba(1, 98, 232, 0.08);
        color: #0162e8;
        border: 1px solid rgba(1, 98, 232, 0.2);
        border-radius: 30px;
        padding: 6px 14px;
        margin: 4px 6px 4px 0;
        font-size: 13px;
        font-weight: 500;
        animation: fadeIn 0.2s ease-in-out;
    }
    .symptom-chip-valex .remove-chip {
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        line-height: 1;
        color: #ef4444;
        transition: transform 0.15s;
    }
    .symptom-chip-valex .remove-chip:hover {
        transform: scale(1.2);
    }

    .test-chip-valex {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: rgba(22, 163, 74, 0.08);
        color: #16a34a;
        border: 1px solid rgba(22, 163, 74, 0.2);
        border-radius: 30px;
        padding: 6px 14px;
        margin: 4px 6px 4px 0;
        font-size: 13px;
        font-weight: 500;
        animation: fadeIn 0.2s ease-in-out;
    }
    .test-chip-valex .remove-chip {
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        line-height: 1;
        color: #ef4444;
        transition: transform 0.15s;
    }
    .test-chip-valex .remove-chip:hover {
        transform: scale(1.2);
    }

    /* Modern Prescription Table */
    .valex-rx-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9edf4;
    }
    .valex-rx-table th {
        background-color: #f8fafd;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 12px 16px;
        border-bottom: 1px solid #e9edf4;
    }
    .valex-rx-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f4f9;
        vertical-align: middle;
        background: #ffffff;
    }
    .valex-rx-table tbody tr:last-child td {
        border-bottom: none;
    }
    .valex-rx-table tbody tr:hover td {
        background-color: #fbfdff;
    }

    .empty-state-box {
        text-align: center;
        padding: 24px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px dashed #cbd5e1;
        color: #94a3b8;
        font-size: 13px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .btn-header-light {
        background-color: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-header-light:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Doctor Consultation &amp; Prescription Desk
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Examination</span> &bull; Record vitals, symptoms, diagnosis &amp; write medications
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('symptoms') }}" class="btn-header-light">
                    <i class="bi bi-arrow-left"></i> Back to Patient Queue
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ================= PATIENT PROFILE BANNER ================= -->
        @php
            $pInitials = strtoupper(substr($pati->patient_name ?? 'P', 0, 2));
        @endphp
        <div class="valex-rx-card p-3 mb-4" style="background: linear-gradient(135deg, #f8fafd 0%, #ffffff 100%); border-left: 4px solid #0162e8;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="valex-avatar-initials bg-primary text-white rounded-circle fw-bold fs-16 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                        {{ $pInitials }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 fw-bold text-dark">{{ $pati->patient_name }}</h5>
                            <span class="badge bg-light text-primary border font-monospace fs-12 px-2.5 py-1 rounded">
                                {{ $pati->registration ?? $pati->patient_id }}
                            </span>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-1.5 fs-12">
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fw-medium">
                                <i class="bi bi-gender-ambiguous text-primary me-1"></i>{{ ucfirst($pati->gender ?? '-') }}, {{ $pati->age_year ? $pati->age_year.' Yrs' : ($pati->age_month ? $pati->age_month.' Mos' : '-') }}
                            </span>
                            @if($pati->husband_father_name)
                                @php
                                    $aLabel = (strtolower($pati->guardian_type ?? '') === 'husband') ? "Husband's Name" : "Father's Name";
                                @endphp
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill ms-lg-2">
                                    <i class="bi bi-person-heart text-primary me-1.5"></i>
                                    <span class="text-muted fw-normal">{{ $aLabel }}:</span>
                                    <strong class="text-dark fw-bold ms-1">{{ $pati->husband_father_name }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-4 text-muted fs-12">
                    <div>
                        <span class="d-block text-uppercase fs-10 fw-bold text-muted">Contact</span>
                        <span class="text-dark fw-medium"><i class="bi bi-telephone text-primary me-1"></i>{{ $pati->mobile ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="d-block text-uppercase fs-10 fw-bold text-muted">Aadhaar / ID</span>
                        <span class="text-dark fw-medium"><i class="bi bi-card-text text-muted me-1"></i>{{ $pati->aaddhar_num ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="d-block text-uppercase fs-10 fw-bold text-muted">Registered Desk</span>
                        @if($pati->member_id)
                            <span class="badge bg-info-transparent text-info px-2.5 py-1 rounded-pill fs-11">
                                <i class="bi bi-person-badge-fill me-1"></i>{{ $pati->created_by }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-11">
                                Direct Entry
                            </span>
                        @endif
                    </div>
                    @if($pati->paymentCategory)
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Payment Category</span>
                            <span class="badge bg-primary-transparent text-primary fw-semibold px-2.5 py-1 rounded-pill fs-11 border border-primary-subtle">
                                <i class="bi bi-wallet2 me-1"></i>{{ $pati->paymentCategory->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= MAIN PRESCRIPTION FORM ================= -->
        <form action="{{ route('symptoms.store') }}" id="prescriptionForm" method="POST">
            @csrf
            <input type="hidden" name="patientid" value="{{ $pati->patient_id }}">

            @php
                $hasStaffVitals = !empty($existingVitals && ($existingVitals->blood_pressure || $existingVitals->pulse_rate || $existingVitals->temperature));
            @endphp

            @if($hasStaffVitals)
                {{-- HIDDEN INPUTS CARRYING STAFF VITALS SO VALIDATION PASSES AND VITALS ARE SAVED WITH PRESCRIPTION --}}
                <input type="hidden" name="bp" id="bp_hidden" value="{{ old('bp', $existingVitals->blood_pressure) }}">
                <input type="hidden" name="pr" id="pr_hidden" value="{{ old('pr', $existingVitals->pulse_rate) }}">
                <input type="hidden" name="temperature" id="temperature_hidden" value="{{ old('temperature', $existingVitals->temperature) }}">
                <input type="hidden" name="spo2" id="spo2_hidden" value="{{ old('spo2', $existingVitals->spo2) }}">
                <input type="hidden" name="weight" id="weight_hidden" value="{{ old('weight', $existingVitals->weight) }}">
                <input type="hidden" name="height" id="height_hidden" value="{{ old('height', $existingVitals->height) }}">
                <input type="hidden" name="blood_group" id="blood_group_hidden" value="{{ old('blood_group', $existingVitals->blood_groups) }}">
                <input type="hidden" name="sugar" id="sugar_hidden" value="{{ old('sugar', $existingVitals->sugar) }}">

                @php
                    $fbsVal = null;
                    $pbsVal = null;
                    $rbsVal = null;
                    if (!empty($existingVitals->sugar)) {
                        $rawSugar = explode(',', $existingVitals->sugar);
                        foreach($rawSugar as $sp) {
                            $sp = trim($sp);
                            if (stripos($sp, 'FBS:') === 0) {
                                $fbsVal = substr($sp, 4);
                            } elseif (stripos($sp, 'PBS:') === 0) {
                                $pbsVal = substr($sp, 4);
                            } elseif (stripos($sp, 'PPBS:') === 0) {
                                $pbsVal = substr($sp, 5);
                            } elseif (stripos($sp, 'RBS:') === 0) {
                                $rbsVal = substr($sp, 4);
                            } elseif (empty($fbsVal) && empty($pbsVal) && empty($rbsVal)) {
                                $rbsVal = $sp;
                            }
                        }
                    }
                @endphp

                {{-- STAFF PHYSICAL EXAMINATION COMPLETE BANNER (5 ON TOP, 5 ON BOTTOM GRID) --}}
                <div class="card border mb-4 shadow-sm" style="border-radius: 12px; border-color: #bbf7d0 !important; overflow: hidden; background: #ffffff;">
                    {{-- Header Bar --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between px-3 py-2" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-bottom: 1px solid #bbf7d0;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; min-width: 26px;">
                                <i class="bi bi-check-lg fw-bold fs-13"></i>
                            </div>
                            <span class="fw-bold text-success-emphasis fs-13">
                                Physical Examination Recorded by Clinic Staff
                            </span>
                            <span class="badge bg-success text-white fs-10 px-2 py-0.5 rounded-pill">
                                Vitals Added
                            </span>
                        </div>
                        <button type="button" class="btn-edit-vitals" onclick="document.getElementById('manualVitalsCard').classList.toggle('d-none');">
                            <i class="bi bi-pencil-square"></i> Edit Vitals
                        </button>
                    </div>

                    {{-- 5-5 Grid Body (5 Top, 5 Bottom) --}}
                    <div class="p-3 bg-white">
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-2">
                            {{-- 1. Blood Pressure --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-activity text-danger"></i> BP
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->blood_pressure ?? '-' }}</strong> <small class="text-muted fs-10">mmHg</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 2. Pulse Rate --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-heart-pulse text-danger"></i> Pulse
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->pulse_rate ?? '-' }}</strong> <small class="text-muted fs-10">bpm</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 3. Temperature --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-thermometer-half text-warning"></i> Temp
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->temperature ?? '-' }}</strong> <small class="text-muted fs-10">&deg;F</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 4. SpO2 --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-lungs text-info"></i> SpO2
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->spo2 ?? '-' }}</strong> <small class="text-muted fs-10">%</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 5. Weight --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-speedometer2 text-primary"></i> Weight
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->weight ?? '-' }}</strong> <small class="text-muted fs-10">kg</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 6. Height --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-arrows-vertical text-primary"></i> Height
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $existingVitals->height ?? '-' }}</strong> <small class="text-muted fs-10">cm</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 7. Blood Group --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-danger-subtle border border-danger-subtle text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-danger-emphasis fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-droplet-fill text-danger"></i> Blood Grp
                                    </span>
                                    <span class="fs-13 text-danger mt-1">
                                        <strong class="fw-bold">{{ $existingVitals->blood_groups ?? '-' }}</strong>
                                    </span>
                                </div>
                            </div>

                            {{-- 8. FBS --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-droplet text-danger"></i> FBS
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $fbsVal ?? '-' }}</strong> <small class="text-muted fs-10">mg/dL</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 9. PBS --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-droplet text-danger"></i> PBS
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $pbsVal ?? '-' }}</strong> <small class="text-muted fs-10">mg/dL</small>
                                    </span>
                                </div>
                            </div>

                            {{-- 10. RBS --}}
                            <div class="col">
                                <div class="p-2 rounded-2 bg-light border text-center h-100 d-flex flex-column justify-content-center">
                                    <span class="text-muted fs-11 fw-semibold text-uppercase d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-droplet text-danger"></i> RBS
                                    </span>
                                    <span class="fs-13 text-dark mt-1">
                                        <strong>{{ $rbsVal ?? '-' }}</strong> <small class="text-muted fs-10">mg/dL</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INVISIBLE FOR DOCTOR (Hidden by default, can be toggled if needed) --}}
                <div class="valex-rx-card d-none" id="manualVitalsCard">
                    <div class="valex-rx-header">
                        <h5 class="valex-rx-title">
                            <i class="bi bi-heart-pulse-fill text-danger"></i> Patient Vitals &amp; Clinical Parameters (Adjust)
                        </h5>
                        <span class="badge bg-secondary-transparent text-secondary px-3 py-1 rounded-pill fs-11 fw-semibold">
                            Optional Adjustment
                        </span>
                    </div>
                    <div class="valex-rx-body">
                        <div class="row g-3">
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Pressure (BP)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-activity"></i></span>
                                    <input type="text" name="bp_editable" class="form-control input-valex" value="{{ old('bp', $existingVitals->blood_pressure) }}" oninput="document.getElementById('bp_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">mmHg</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Pulse Rate (PR)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-heart-pulse"></i></span>
                                    <input type="text" name="pr_editable" class="form-control input-valex" value="{{ old('pr', $existingVitals->pulse_rate) }}" oninput="document.getElementById('pr_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">bpm</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Temperature</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-warning"><i class="bi bi-thermometer-half"></i></span>
                                    <input type="text" name="temperature_editable" class="form-control input-valex" value="{{ old('temperature', $existingVitals->temperature) }}" oninput="document.getElementById('temperature_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">&deg;F</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Oxygen Saturation (SpO2)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-info"><i class="bi bi-lungs"></i></span>
                                    <input type="text" name="spo2_editable" class="form-control input-valex" value="{{ old('spo2', $existingVitals->spo2) }}" oninput="document.getElementById('spo2_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">%</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Weight</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-speedometer2"></i></span>
                                    <input type="text" name="weight_editable" class="form-control input-valex" value="{{ old('weight', $existingVitals->weight) }}" oninput="document.getElementById('weight_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">kg</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Height</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-arrows-vertical"></i></span>
                                    <input type="text" name="height_editable" class="form-control input-valex" value="{{ old('height', $existingVitals->height) }}" oninput="document.getElementById('height_hidden').value = this.value">
                                    <span class="input-group-text bg-light text-muted fs-12">cm / ft</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Group</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-droplet-fill"></i></span>
                                    <select class="form-select input-valex" name="blood_group_editable" onchange="document.getElementById('blood_group_hidden').value = this.value">
                                        <option value="">Select Blood Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $existingVitals->blood_groups) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Sugar (FBS / PBS / RBS)</label>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" id="sugar_fasting_editable" placeholder="FBS" value="{{ $fbsVal !== '-' ? $fbsVal : '' }}" oninput="updateHiddenSugar();">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" id="sugar_pp_editable" placeholder="PBS" value="{{ $pbsVal !== '-' ? $pbsVal : '' }}" oninput="updateHiddenSugar();">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" id="sugar_random_editable" placeholder="RBS" value="{{ $rbsVal !== '-' ? $rbsVal : '' }}" oninput="updateHiddenSugar();">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function updateHiddenSugar() {
                        var fbs = document.getElementById('sugar_fasting_editable') ? document.getElementById('sugar_fasting_editable').value.trim() : '';
                        var pbs = document.getElementById('sugar_pp_editable') ? document.getElementById('sugar_pp_editable').value.trim() : '';
                        var rbs = document.getElementById('sugar_random_editable') ? document.getElementById('sugar_random_editable').value.trim() : '';
                        var parts = [];
                        if (fbs) parts.push('FBS:' + fbs);
                        if (pbs) parts.push('PBS:' + pbs);
                        if (rbs) parts.push('RBS:' + rbs);
                        document.getElementById('sugar_hidden').value = parts.join(',');
                    }
                </script>
            @else
                <!-- 1. VITALS & PHYSICAL EXAMINATION CARD (Visible when not filled by staff) -->
                <div class="valex-rx-card">
                    <div class="valex-rx-header">
                        <h5 class="valex-rx-title">
                            <i class="bi bi-heart-pulse-fill text-danger"></i> Patient Vitals &amp; Clinical Parameters
                        </h5>
                        <span class="badge bg-danger-transparent text-danger px-3 py-1 rounded-pill fs-11 fw-semibold">
                            <i class="bi bi-activity me-1"></i> Physical Examination
                        </span>
                    </div>

                    <div class="valex-rx-body">
                        <div class="row g-3">

                            {{-- Blood Pressure --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Pressure (BP) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-activity"></i></span>
                                    <input type="text" name="bp" id="bp" class="form-control input-valex" placeholder="e.g. 120/80" value="{{ old('bp') }}" required>
                                    <span class="input-group-text bg-light text-muted fs-12">mmHg</span>
                                </div>
                            </div>

                            {{-- Pulse Rate --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Pulse Rate (PR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-heart-pulse"></i></span>
                                    <input type="text" name="pr" id="pr" class="form-control input-valex" placeholder="e.g. 72" value="{{ old('pr') }}" required>
                                    <span class="input-group-text bg-light text-muted fs-12">bpm</span>
                                </div>
                            </div>

                            {{-- Temperature --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Temperature <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-warning"><i class="bi bi-thermometer-half"></i></span>
                                    <input type="text" name="temperature" id="temperature" class="form-control input-valex" placeholder="e.g. 98.6" value="{{ old('temperature') }}" required>
                                    <span class="input-group-text bg-light text-muted fs-12">&deg;F</span>
                                </div>
                            </div>

                            {{-- SpO2 --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Oxygen Saturation (SpO2)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-info"><i class="bi bi-lungs"></i></span>
                                    <input type="text" name="spo2" id="spo2" class="form-control input-valex" placeholder="e.g. 98" value="{{ old('spo2') }}">
                                    <span class="input-group-text bg-light text-muted fs-12">%</span>
                                </div>
                            </div>

                            {{-- Weight --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Weight</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-speedometer2"></i></span>
                                    <input type="text" name="weight" id="weight" class="form-control input-valex" placeholder="e.g. 68" value="{{ old('weight') }}">
                                    <span class="input-group-text bg-light text-muted fs-12">kg</span>
                                </div>
                            </div>

                            {{-- Height --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Height</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-arrows-vertical"></i></span>
                                    <input type="text" name="height" id="height" class="form-control input-valex" placeholder="e.g. 172" value="{{ old('height') }}">
                                    <span class="input-group-text bg-light text-muted fs-12">cm / ft</span>
                                </div>
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Group <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-droplet-fill"></i></span>
                                    <select class="form-select input-valex" name="blood_group" required>
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Blood Sugar (FBS, PPBS, RBS) --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label-valex">Blood Sugar (mg/dL)</label>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" name="sugar_fasting" placeholder="FBS" title="Fasting Blood Sugar">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" name="sugar_pp" placeholder="PPBS" title="Post Prandial">
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control form-control-sm text-center input-valex" name="sugar_random" placeholder="RBS" title="Random Blood Sugar">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. SYMPTOMS & CLINICAL DIAGNOSIS CARD -->
            <div class="valex-rx-card">
                <div class="valex-rx-header">
                    <h5 class="valex-rx-title">
                        <i class="bi bi-chat-square-text-fill text-primary"></i> Chief Complaints &amp; Clinical Diagnosis
                    </h5>
                    <span class="badge bg-primary-transparent text-primary px-3 py-1 rounded-pill fs-11 fw-semibold">
                        <i class="bi bi-clipboard-pulse me-1"></i> Patient Symptoms
                    </span>
                </div>

                <div class="valex-rx-body">
                    <div class="row g-4">

                        {{-- Chief Complaints / Symptoms --}}
                        <div class="col-lg-6">
                            <label class="form-label-valex">Chief Complaints / Symptoms</label>
                            <div class="input-group mb-2">
                                <input type="text" name="symptoms_input" id="symptoms" class="form-control input-valex" list="symptomsDatalist" autocomplete="off" placeholder="Type symptom name and click Add (or Enter)...">
                                <button type="button" id="addSymptomBtn" class="btn btn-valex-primary px-3 fw-semibold d-inline-flex align-items-center gap-1.5" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important; height: 40px; font-size: 13px;">
                                    <i class="bi bi-plus-circle-fill"></i> Add
                                </button>
                                <datalist id="symptomsDatalist">
                                    @foreach($symptoms as $symp)
                                        <option value="{{ $symp->symptom_name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            {{-- Selected Symptoms Chips --}}
                            <div class="p-3 bg-light rounded-3 border" style="min-height: 56px;">
                                <div id="symptomsList"></div>
                                <div id="symptomsEmptyMsg" class="text-muted fs-12 fst-italic">
                                    <i class="bi bi-info-circle me-1"></i> No symptoms added yet. Type above and press Add.
                                </div>
                                <div id="symptomsHiddenInputs"></div>
                            </div>
                        </div>

                        {{-- Diagnosis Lab Tests --}}
                        <div class="col-lg-6">
                            <label class="form-label-valex">Recommended Diagnosis Tests (Lab)</label>
                            <div class="input-group mb-2">
                                <input type="text" name="diagnosis_test_input" id="diagnosis_test" class="form-control input-valex" list="diagnosisTestDatalist" autocomplete="off" placeholder="Type test name (e.g. CBC, Lipid Profile)...">
                                <button type="button" id="addDiagnosisTestBtn" class="btn btn-valex-success px-3 fw-semibold d-inline-flex align-items-center gap-1.5" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important; height: 40px; font-size: 13px;">
                                    <i class="bi bi-plus-circle-fill"></i> Add Test
                                </button>
                                <datalist id="diagnosisTestDatalist">
                                    @foreach($diagnosisTests as $dtest)
                                        <option value="{{ $dtest->test_name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            {{-- Selected Tests Chips --}}
                            <div class="p-3 bg-light rounded-3 border" style="min-height: 56px;">
                                <div id="diagnosisTestList"></div>
                                <div id="diagnosisTestEmptyMsg" class="text-muted fs-12 fst-italic">
                                    <i class="bi bi-info-circle me-1"></i> No diagnosis tests added yet.
                                </div>
                                <div id="diagnosisTestHiddenInputs"></div>
                            </div>
                        </div>

                        {{-- Clinical Diagnosis Summary --}}
                        <div class="col-12">
                            <label for="diagnosis" class="form-label-valex">Provisional / Clinical Diagnosis</label>
                            <textarea name="diagnosis" id="diagnosis" rows="2" class="form-control" placeholder="Enter clinical assessment, findings, or provisional diagnosis notes..." style="border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px;">{{ old('diagnosis') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 3. MEDICATIONS & PRESCRIPTION (Rx) CARD -->
            <div class="valex-rx-card">
                <div class="valex-rx-header">
                    <h5 class="valex-rx-title">
                        <i class="bi bi-capsule-pill text-purple" style="color: #7928ca;"></i> Rx - Medications &amp; Dosages
                    </h5>
                    <span class="badge bg-purple-transparent text-purple px-3 py-1 rounded-pill fs-11 fw-semibold">
                        <i class="bi bi-prescription me-1"></i> Rx Medicine Chart
                    </span>
                </div>

                <div class="valex-rx-body">
                    <!-- Medication Input Selector Row -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="row g-2 align-items-end">

                            {{-- Medicine Select --}}
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <label class="form-label-valex mb-1">Select Medicine</label>
                                <select name="medicine_name_input" id="medicine_name" class="form-select input-valex">
                                    <option value="">-- Choose Medicine --</option>
                                    @foreach($medicine as $medi)
                                        <option value="{{ $medi->id }}">{{ $medi->medicine_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dosage Select --}}
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6">
                                <label class="form-label-valex mb-1">Dosage</label>
                                <select name="dosage_input" id="dosage_select" class="form-select input-valex">
                                    <option value="">Select Dosage</option>
                                    @foreach($dosage as $dos)
                                        <option value="{{ $dos->id }}" data-unit="{{ $dos->unit_id }}">{{ $dos->dosage_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit Select --}}
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6">
                                <label class="form-label-valex mb-1">Unit</label>
                                <select name="unit_input" id="unit_select" class="form-select input-valex">
                                    <option value="">Select Unit</option>
                                    @foreach($unit as $un)
                                        <option value="{{ $un->id }}">{{ $un->unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Frequency Select --}}
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label-valex mb-1">Frequency</label>
                                <select name="frequency_input" id="frequency_select" class="form-select input-valex">
                                    <option value="">Select Frequency</option>
                                    @foreach($interval as $inter)
                                        <option value="{{ $inter->id }}">{{ $inter->interval_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Duration Select --}}
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label-valex mb-1">Duration</label>
                                <select name="duration_input" id="duration" class="form-select input-valex">
                                    <option value="">Select Duration</option>
                                    @foreach($duration as $dur)
                                        <option value="{{ $dur->id }}">{{ $dur->duration_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Add Medicine Button --}}
                            <div class="col-12 mt-2 text-end">
                                <button type="button" id="addMedicineBtn" class="btn btn-valex-primary px-4 fw-semibold shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Add Medicine to Rx
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Dynamic Medicine Table -->
                    <div class="table-responsive">
                        <table class="valex-rx-table" id="medicineTable" style="display:none;">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Medicine Name</th>
                                    <th>Dosage</th>
                                    <th>Unit</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th class="text-end" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="medicineTableBody"></tbody>
                        </table>
                    </div>

                    <div id="medicineEmptyMsg" class="empty-state-box">
                        <i class="bi bi-capsule fs-2 d-block mb-1 opacity-50"></i>
                        No medicines added to this prescription yet. Select above and click <strong>"Add Medicine to Rx"</strong>.
                    </div>
                    <div id="medicineHiddenInputs"></div>

                </div>
            </div>

            <!-- 4. ADVICE & FOLLOW-UP CARD -->
            <div class="valex-rx-card">
                <div class="valex-rx-header">
                    <h5 class="valex-rx-title">
                        <i class="bi bi-journal-text text-info"></i> Advice &amp; Follow-Up Instructions
                    </h5>
                    <span class="badge bg-info-transparent text-info px-3 py-1 rounded-pill fs-11 fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i> Patient Care
                    </span>
                </div>

                <div class="valex-rx-body">
                    <!-- Advice Input Selector Row -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label-valex mb-1">Dietary &amp; General Advice</label>
                                <select name="advice_input" id="advice" class="form-select input-valex">
                                    <option value="">-- Select Standard Advice --</option>
                                    @foreach($suggestion as $suggest)
                                        <option value="{{ $suggest->id }}">{{ $suggest->suggestion_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-valex mb-1">Follow-Up Schedule</label>
                                <input type="text" name="followup_input" id="followup_input" class="form-control input-valex" placeholder="e.g. Review after 5 days, SOS if fever persists">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="addAdviceBtn" class="btn btn-valex-primary w-100 fw-semibold" style="height: 40px;">
                                    <i class="bi bi-plus-circle me-1"></i> Add Advice
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Advice Table -->
                    <div class="table-responsive">
                        <table class="valex-rx-table" id="adviceTable" style="display:none;">
                            <thead>
                                <tr>
                                    <th style="width: 55%;">Advice / Suggestion</th>
                                    <th style="width: 35%;">Follow-Up Schedule</th>
                                    <th class="text-end" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="adviceTableBody"></tbody>
                        </table>
                    </div>

                    <div id="adviceEmptyMsg" class="empty-state-box">
                        <i class="bi bi-chat-left-dots fs-2 d-block mb-1 opacity-50"></i>
                        No advice or follow-up notes added yet.
                    </div>
                    <div id="adviceHiddenInputs"></div>

                    <!-- Next Visiting / Follow-Up Schedule -->
                    <div class="row g-3 mt-2 pt-3 border-top">
                        <div class="col-lg-6 col-md-8">
                            <label class="form-label-valex mb-1 text-primary fw-bold">
                                <i class="bi bi-calendar2-event-fill me-1"></i> Next Visit / Review Schedule (अगली विज़िट की तारीख)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary-subtle text-primary border-primary-subtle">
                                    <i class="bi bi-calendar-check"></i>
                                </span>
                                <input type="date" name="next_visit_date" id="next_visit_date" class="form-control input-valex border-primary-subtle" min="{{ date('Y-m-d') }}" value="{{ old('next_visit_date') }}">
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <small class="text-muted me-1 align-self-center fs-11 fw-semibold">Quick Set:</small>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="setNextVisitDays(3)">+3 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="setNextVisitDays(5)">+5 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="setNextVisitDays(7)">+1 Week</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="setNextVisitDays(15)">+15 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="setNextVisitDays(30)">+1 Month</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 fs-11 rounded-pill" onclick="document.getElementById('next_visit_date').value=''">Clear</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 5. FINAL SUBMISSION BAR -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-4 bg-white rounded-3 shadow-sm border mb-5">
                <a href="{{ route('symptoms') }}" class="btn btn-light px-4">
                    <i class="bi bi-x-circle me-1"></i> Discard &amp; Back
                </a>
                <button type="submit" class="btn btn-valex-primary px-5 py-2 fw-bold fs-14 shadow">
                    <i class="bi bi-check2-circle me-1"></i> Save &amp; Generate Prescription (Rx)
                </button>
            </div>

        </form>

    </div>
</div>

<!-- JAVASCRIPT FOR DYNAMIC ADDS, SELECT2 SEARCH & CHIPS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    /* =========================================================
       0) INITIALIZE SELECT2 SEARCHABLE DROPDOWNS
    ========================================================= */
    $('#medicine_name').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Search or Type New Medicine --',
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term + ' (Create New Medicine)',
                newTag: true
            };
        }
    });

    $('#dosage_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or Type Dosage',
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') return null;
            return {
                id: term,
                text: term + ' (Create New Dosage)',
                newTag: true
            };
        }
    });

    $('#unit_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or Type Unit',
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') return null;
            return {
                id: term,
                text: term + ' (Create New Unit)',
                newTag: true
            };
        }
    });

    $('#frequency_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or Type Frequency',
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') return null;
            return {
                id: term,
                text: term + ' (Create New Frequency)',
                newTag: true
            };
        }
    });

    $('#duration').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or Type Duration',
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') return null;
            return {
                id: term,
                text: term + ' (Create New Duration)',
                newTag: true
            };
        }
    });

    $('#advice').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Select or Type Advice --',
        allowClear: true,
        width: '100%',
        tags: true
    });

    // Auto-fill Unit when a Dosage is chosen (based on data-unit attribute)
    $('#dosage_select').on('change', function () {
        var selectedOpt = $(this).find(':selected');
        var unitVal = selectedOpt ? selectedOpt.data('unit') : null;
        if (unitVal) {
            $('#unit_select').val(unitVal).trigger('change');
        }
    });

    /* =========================================================
       1) ADD SYMPTOMS (Chips & Hidden Inputs)
    ========================================================= */
    var symptomsInput = document.getElementById('symptoms');
    var addSymptomBtn = document.getElementById('addSymptomBtn');
    var symptomsList = document.getElementById('symptomsList');
    var symptomsEmptyMsg = document.getElementById('symptomsEmptyMsg');
    var symptomsHiddenInputs = document.getElementById('symptomsHiddenInputs');

    function refreshSymptomsEmptyMsg() {
        if (symptomsEmptyMsg && symptomsList) {
            symptomsEmptyMsg.style.display = symptomsList.children.length ? 'none' : 'block';
        }
    }

    function isSymptomAlreadyAdded(value) {
        if (!symptomsHiddenInputs) return false;
        var existing = symptomsHiddenInputs.querySelectorAll('input[name="symptoms[]"]');
        for (var i = 0; i < existing.length; i++) {
            if (existing[i].value.trim().toLowerCase() === value.trim().toLowerCase()) {
                return true;
            }
        }
        return false;
    }

    function addSymptomChip(value) {
        var chip = document.createElement('span');
        chip.className = 'symptom-chip-valex';

        var text = document.createElement('span');
        text.textContent = value;

        var removeBtn = document.createElement('span');
        removeBtn.className = 'remove-chip';
        removeBtn.innerHTML = '&times;';
        removeBtn.title = 'Remove symptom';

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'symptoms[]';
        hiddenInput.value = value;
        symptomsHiddenInputs.appendChild(hiddenInput);

        removeBtn.addEventListener('click', function () {
            chip.remove();
            hiddenInput.remove();
            refreshSymptomsEmptyMsg();
        });

        chip.appendChild(text);
        chip.appendChild(removeBtn);
        symptomsList.appendChild(chip);
        refreshSymptomsEmptyMsg();
    }

    if (addSymptomBtn && symptomsInput) {
        addSymptomBtn.addEventListener('click', function () {
            var value = symptomsInput.value.trim();
            if (!value) {
                symptomsInput.focus();
                return;
            }

            if (isSymptomAlreadyAdded(value)) {
                symptomsInput.value = '';
                symptomsInput.focus();
                alert('"' + value + '" is already added.');
                return;
            }

            addSymptomChip(value);
            symptomsInput.value = '';
            symptomsInput.focus();
        });

        symptomsInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSymptomBtn.click();
            }
        });
    }

    refreshSymptomsEmptyMsg();

    /* =========================================================
       2) ADD DIAGNOSIS TEST (Chips & Hidden Inputs)
    ========================================================= */
    var diagnosisTestInput = document.getElementById('diagnosis_test');
    var addDiagnosisTestBtn = document.getElementById('addDiagnosisTestBtn');
    var diagnosisTestList = document.getElementById('diagnosisTestList');
    var diagnosisTestEmptyMsg = document.getElementById('diagnosisTestEmptyMsg');
    var diagnosisTestHiddenInputs = document.getElementById('diagnosisTestHiddenInputs');

    function refreshDiagnosisTestEmptyMsg() {
        if (diagnosisTestEmptyMsg && diagnosisTestList) {
            diagnosisTestEmptyMsg.style.display = diagnosisTestList.children.length ? 'none' : 'block';
        }
    }

    function isDiagnosisTestAlreadyAdded(value) {
        if (!diagnosisTestHiddenInputs) return false;
        var existing = diagnosisTestHiddenInputs.querySelectorAll('input[name="diagnosis_test[]"]');
        for (var i = 0; i < existing.length; i++) {
            if (existing[i].value.trim().toLowerCase() === value.trim().toLowerCase()) {
                return true;
            }
        }
        return false;
    }

    function addDiagnosisTestChip(value) {
        var chip = document.createElement('span');
        chip.className = 'test-chip-valex';

        var text = document.createElement('span');
        text.textContent = value;

        var removeBtn = document.createElement('span');
        removeBtn.className = 'remove-chip';
        removeBtn.innerHTML = '&times;';
        removeBtn.title = 'Remove test';

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'diagnosis_test[]';
        hiddenInput.value = value;
        diagnosisTestHiddenInputs.appendChild(hiddenInput);

        removeBtn.addEventListener('click', function () {
            chip.remove();
            hiddenInput.remove();
            refreshDiagnosisTestEmptyMsg();
        });

        chip.appendChild(text);
        chip.appendChild(removeBtn);
        diagnosisTestList.appendChild(chip);
        refreshDiagnosisTestEmptyMsg();
    }

    if (addDiagnosisTestBtn && diagnosisTestInput) {
        addDiagnosisTestBtn.addEventListener('click', function () {
            var value = diagnosisTestInput.value.trim();
            if (!value) {
                diagnosisTestInput.focus();
                return;
            }

            if (isDiagnosisTestAlreadyAdded(value)) {
                diagnosisTestInput.value = '';
                diagnosisTestInput.focus();
                alert('"' + value + '" is already added.');
                return;
            }

            addDiagnosisTestChip(value);
            diagnosisTestInput.value = '';
            diagnosisTestInput.focus();
        });

        diagnosisTestInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addDiagnosisTestBtn.click();
            }
        });
    }

    refreshDiagnosisTestEmptyMsg();

    /* =========================================================
       3) ADD MEDICINE TO PRESCRIPTION (Table & Hidden Inputs)
    ========================================================= */
    var addMedicineBtn = document.getElementById('addMedicineBtn');
    var medicineTable = document.getElementById('medicineTable');
    var medicineTableBody = document.getElementById('medicineTableBody');
    var medicineEmptyMsg = document.getElementById('medicineEmptyMsg');
    var medicineHiddenInputs = document.getElementById('medicineHiddenInputs');

    function refreshMedicineEmptyMsg() {
        if (!medicineTableBody || !medicineTable || !medicineEmptyMsg) return;
        var hasRows = medicineTableBody.children.length > 0;
        medicineTable.style.display = hasRows ? 'table' : 'none';
        medicineEmptyMsg.style.display = hasRows ? 'none' : 'block';
    }

    if (addMedicineBtn) {
        addMedicineBtn.addEventListener('click', function () {
            var medVal = $('#medicine_name').val();
            if (!medVal) {
                $('#medicine_name').select2('open');
                return;
            }

            var medSelectData = $('#medicine_name').select2('data')[0];
            var medText = medSelectData ? medSelectData.text.replace(' (Create New Medicine)', '') : medVal;

            var dosVal = $('#dosage_select').val() || '';
            var dosSelectData = $('#dosage_select').select2('data')[0];
            var dosText = dosSelectData && dosSelectData.id ? dosSelectData.text.replace(' (Create New Dosage)', '') : '-';

            var unitVal = $('#unit_select').val() || '';
            var unitSelectData = $('#unit_select').select2('data')[0];
            var unitText = unitSelectData && unitSelectData.id ? unitSelectData.text.replace(' (Create New Unit)', '') : '-';

            var freqVal = $('#frequency_select').val() || '';
            var freqSelectData = $('#frequency_select').select2('data')[0];
            var freqText = freqSelectData && freqSelectData.id ? freqSelectData.text.replace(' (Create New Frequency)', '') : '-';

            var durVal = $('#duration').val() || '';
            var durSelectData = $('#duration').select2('data')[0];
            var durText = durSelectData && durSelectData.id ? durSelectData.text.replace(' (Create New Duration)', '') : '-';

            var isNewTag = !$.isNumeric(medVal);

            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="fw-bold text-primary">' + medText + (isNewTag ? ' <span class="badge bg-success-subtle text-success border border-success-subtle fs-10 px-1.5 py-0.5 rounded-pill">New</span>' : '') + '</td>' +
                '<td><span class="badge bg-light text-dark border">' + dosText + '</span></td>' +
                '<td><span class="badge bg-light text-dark border">' + unitText + '</span></td>' +
                '<td><span class="badge bg-info-transparent text-info">' + freqText + '</span></td>' +
                '<td><span class="badge bg-purple-transparent text-purple">' + durText + '</span></td>' +
                '<td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" title="Remove Medicine"><i class="bi bi-trash"></i></button></td>';

            // hidden inputs so this row's data is submitted with the form
            var fieldsWrapper = document.createElement('span');

            var medInput = document.createElement('input');
            medInput.type = 'hidden';
            medInput.name = 'medicine[]';
            medInput.value = isNewTag ? medText : medVal;
            fieldsWrapper.appendChild(medInput);

            var dosInput = document.createElement('input');
            dosInput.type = 'hidden';
            dosInput.name = 'dosage[]';
            dosInput.value = (dosSelectData && dosSelectData.newTag) ? dosText : dosVal;
            fieldsWrapper.appendChild(dosInput);

            var unitInput = document.createElement('input');
            unitInput.type = 'hidden';
            unitInput.name = 'unit[]';
            unitInput.value = (unitSelectData && unitSelectData.newTag) ? unitText : unitVal;
            fieldsWrapper.appendChild(unitInput);

            var freqInput = document.createElement('input');
            freqInput.type = 'hidden';
            freqInput.name = 'frequency[]';
            freqInput.value = (freqSelectData && freqSelectData.newTag) ? freqText : freqVal;
            fieldsWrapper.appendChild(freqInput);

            var durInput = document.createElement('input');
            durInput.type = 'hidden';
            durInput.name = 'duration[]';
            durInput.value = (durSelectData && durSelectData.newTag) ? durText : durVal;
            fieldsWrapper.appendChild(durInput);

            medicineHiddenInputs.appendChild(fieldsWrapper);

            tr.querySelector('button').addEventListener('click', function () {
                tr.remove();
                fieldsWrapper.remove();
                refreshMedicineEmptyMsg();
            });

            medicineTableBody.appendChild(tr);
            refreshMedicineEmptyMsg();

            // reset the selects for next entry
            $('#medicine_name').val('').trigger('change');
            $('#dosage_select').val('').trigger('change');
            $('#unit_select').val('').trigger('change');
            $('#frequency_select').val('').trigger('change');
            $('#duration').val('').trigger('change');
        });
    }

    refreshMedicineEmptyMsg();

    /* =========================================================
       4) ADD ADVICE (Table & Hidden Inputs)
    ========================================================= */
    var followupInput = document.getElementById('followup_input');
    var addAdviceBtn = document.getElementById('addAdviceBtn');
    var adviceTable = document.getElementById('adviceTable');
    var adviceTableBody = document.getElementById('adviceTableBody');
    var adviceEmptyMsg = document.getElementById('adviceEmptyMsg');
    var adviceHiddenInputs = document.getElementById('adviceHiddenInputs');

    function refreshAdviceEmptyMsg() {
        if (!adviceTableBody || !adviceTable || !adviceEmptyMsg) return;
        var hasRows = adviceTableBody.children.length > 0;
        adviceTable.style.display = hasRows ? 'table' : 'none';
        adviceEmptyMsg.style.display = hasRows ? 'none' : 'block';
    }

    if (addAdviceBtn) {
        addAdviceBtn.addEventListener('click', function () {
            var advVal = $('#advice').val();
            var advData = $('#advice').select2('data')[0];
            var advText = advData && advData.id ? advData.text : '';
            var followupText = followupInput ? followupInput.value.trim() : '';

            if (!advVal && !followupText) {
                $('#advice').select2('open');
                return;
            }

            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="fw-medium text-dark">' + (advText || '<span class="text-muted">Standard Advice</span>') + '</td>' +
                '<td><span class="badge bg-light text-dark border">' + (followupText || 'As Advised') + '</span></td>' +
                '<td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" title="Remove Advice"><i class="bi bi-trash"></i></button></td>';

            var fieldsWrapper = document.createElement('span');

            var adviceHidden = document.createElement('input');
            adviceHidden.type = 'hidden';
            adviceHidden.name = 'advice[]';
            adviceHidden.value = advVal || advText;
            fieldsWrapper.appendChild(adviceHidden);

            var followupHidden = document.createElement('input');
            followupHidden.type = 'hidden';
            followupHidden.name = 'followup[]';
            followupHidden.value = followupText;
            fieldsWrapper.appendChild(followupHidden);

            adviceHiddenInputs.appendChild(fieldsWrapper);

            tr.querySelector('button').addEventListener('click', function () {
                tr.remove();
                fieldsWrapper.remove();
                refreshAdviceEmptyMsg();
            });

            adviceTableBody.appendChild(tr);
            refreshAdviceEmptyMsg();

            // reset fields for next entry
            $('#advice').val('').trigger('change');
            if (followupInput) followupInput.value = '';
        });
    }

    refreshAdviceEmptyMsg();
});

// Quick set next visit date helper function
function setNextVisitDays(days) {
    var d = new Date();
    d.setDate(d.getDate() + days);
    var month = '' + (d.getMonth() + 1);
    var day = '' + d.getDate();
    var year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    var dateStr = [year, month, day].join('-');
    var el = document.getElementById('next_visit_date');
    if (el) {
        el.value = dateStr;
    }
}
</script>
@endsection
