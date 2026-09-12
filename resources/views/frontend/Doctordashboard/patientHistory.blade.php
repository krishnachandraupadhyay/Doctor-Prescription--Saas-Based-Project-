@extends('frontend.include.layout')

@section('title', 'Patient Medical History & Visits - Doctor Portal')

@section('content')
<style>
    /* Hide floating customizer button */
    .customizer-setting, #customizer-layout, .customizer-btn, [data-bs-target="#theme-settings-offcanvas"] {
        display: none !important;
    }

    .valex-history-wrapper {
        padding-bottom: 40px;
    }

    /* Structured Patient Demographics Card */
    .history-patient-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        padding: 24px;
        margin-bottom: 24px;
    }

    .demographic-tile {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
        transition: all 0.2s ease;
    }
    .demographic-tile:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .demographic-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .btn-rx-action {
        background-color: #0162e8 !important;
        border: 1px solid #0162e8 !important;
        color: #ffffff !important;
        padding: 7px 18px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 6px rgba(1, 98, 232, 0.25) !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-rx-action:hover {
        background-color: #0150bf !important;
        border-color: #0150bf !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(1, 98, 232, 0.35) !important;
    }

    /* Visit Card */
    .visit-card-valex {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
        transition: all 0.25s ease;
        overflow: hidden;
    }
    .visit-card-valex:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }

    .visit-header-valex {
        background: #fbfcfe;
        border-bottom: 1px solid #f1f4f9;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .visit-date-badge {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-rx-action {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        background: #0284c7 !important;
        color: #ffffff !important;
        border: 1px solid #0284c7 !important;
        border-radius: 20px !important;
        padding: 6px 16px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2) !important;
    }
    .btn-rx-action:hover {
        background: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3) !important;
    }
    .btn-rx-action i {
        font-size: 13px !important;
    }

    .vitals-container-strip {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-top: 14px;
        width: 100%;
    }

    .vitals-row-strip {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
    }

    .vital-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        padding: 7px 16px;
        font-size: 13.5px;
        font-weight: 500;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        transition: all 0.15s ease;
    }
    .vital-pill i {
        font-size: 15px;
    }
    .vital-pill strong {
        font-size: 14px;
        color: #0f172a;
    }
    .vital-pill:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
    }

    .visit-body-valex {
        padding: 22px;
    }

    .history-section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chip-symptom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(1, 98, 232, 0.08);
        color: #0162e8;
        border: 1px solid rgba(1, 98, 232, 0.18);
        border-radius: 30px;
        padding: 4px 12px;
        margin: 2px 4px 4px 0;
        font-size: 12.5px;
        font-weight: 500;
    }

    .chip-diagnosis {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(14, 165, 233, 0.08);
        color: #0284c7;
        border: 1px solid rgba(14, 165, 233, 0.2);
        border-radius: 30px;
        padding: 4px 12px;
        margin: 2px 4px 4px 0;
        font-size: 12.5px;
        font-weight: 500;
    }

    .chip-test {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(22, 163, 74, 0.08);
        color: #16a34a;
        border: 1px solid rgba(22, 163, 74, 0.18);
        border-radius: 30px;
        padding: 4px 12px;
        margin: 2px 4px 4px 0;
        font-size: 12.5px;
        font-weight: 500;
    }

    .chip-advice {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(121, 40, 202, 0.08);
        color: #7928ca;
        border: 1px solid rgba(121, 40, 202, 0.18);
        border-radius: 30px;
        padding: 4px 12px;
        margin: 2px 4px 4px 0;
        font-size: 12.5px;
        font-weight: 500;
    }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9edf4;
        margin-top: 8px;
    }
    .history-table th {
        background-color: #f8fafd;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 14px;
        border-bottom: 1px solid #e9edf4;
    }
    .history-table td {
        padding: 10px 14px;
        font-size: 12.5px;
        color: #334155;
        border-bottom: 1px solid #f1f4f9;
        vertical-align: middle;
        background: #ffffff;
    }
    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .empty-history-box {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: 14px;
        border: 1px dashed #cbd5e1;
        color: #94a3b8;
    }

    .btn-header-add {
        background-color: #0162e8 !important;
        border: 1px solid #0162e8 !important;
        color: #ffffff !important;
        padding: 8px 18px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 3px 10px rgba(1, 98, 232, 0.25) !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-header-add:hover {
        background-color: #0150bf !important;
        border-color: #0150bf !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(1, 98, 232, 0.35) !important;
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

<div class="page-content wrapper valex-dashboard-wrapper valex-history-wrapper">
    <div class="container-fluid">

        <!-- Page Breadcrumb Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Patient Medical History &amp; Past Visits
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Records Timeline</span> &bull; Comprehensive consultation history, vitals, prescriptions &amp; lab advice
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('symptoms') }}" class="btn-header-light">
                    <i class="bi bi-arrow-left"></i> Back to Queue
                </a>
                <a href="{{ route('addsymptoms', $patient->id) }}" class="btn-header-add">
                    <i class="bi bi-plus-circle-fill"></i> New Consultation
                </a>
            </div>
        </div>

        <!-- ================= PATIENT PROFILE CARD ================= -->
        @php
            $pInitials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
            $groupedVisits = $patient->presciption_data->sortByDesc(function($item) {
                return $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : $item->id;
            })->groupBy(function($item) {
                return $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') : 'visit_'.$item->id;
            });
            $totalVisits = $groupedVisits->count();
        @endphp

        <div class="history-patient-card shadow-sm">
            {{-- Top Row: Avatar, Name, Registration, Actions --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="valex-avatar-initials bg-primary text-white rounded-circle fw-bold fs-16 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px; box-shadow: 0 4px 12px rgba(1, 98, 232, 0.25);">
                        {{ $pInitials }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0 fw-bold text-dark fs-18">{{ $patient->patient_name }}</h4>
                            <span class="badge bg-light text-primary border font-monospace fs-12 px-2.5 py-1 rounded">
                                {{ $patient->registration ?? $patient->patient_id }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2.5">
                    <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fs-12 fw-bold">
                        <i class="bi bi-journal-medical me-1"></i> {{ $totalVisits }} {{ Str::plural('Consultation', $totalVisits) }}
                    </span>
                    <a href="{{ route('addsymptoms', $patient->id) }}" class="btn-rx-action">
                        <i class="bi bi-prescription2 fs-15"></i> Write Rx
                    </a>
                </div>
            </div>

            {{-- Bottom Row: Demographic KPI Tiles Spanning Full Width (Father Name, Gender/Age, Phone, Aadhaar, Reg Date, Desk) --}}
            <div class="row g-3 pt-3 border-top">
                {{-- Father's / Husband's Name --}}
                @php
                    $gLabel = (strtolower($patient->guardian_type ?? '') === 'husband') ? "Husband's Name" : "Father's Name";
                @endphp
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-purple-transparent text-purple">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">{{ $gLabel }}</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->husband_father_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Gender & Age --}}
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-indigo-transparent text-indigo">
                            @if(strtolower($patient->gender ?? '') === 'female')
                                <i class="bi bi-gender-female text-danger"></i>
                            @elseif(strtolower($patient->gender ?? '') === 'male')
                                <i class="bi bi-gender-male text-primary"></i>
                            @else
                                <i class="bi bi-person"></i>
                            @endif
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Gender &amp; Age</span>
                            <span class="text-dark fw-bold fs-13">
                                {{ ucfirst($patient->gender ?? '-') }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : ($patient->age_month ? $patient->age_month.' Mos' : '-') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Phone Number --}}
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-primary-transparent text-primary">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Phone Number</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->mobile ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Aadhaar Card / ID --}}
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-info-transparent text-info">
                            <i class="bi bi-card-text"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Aadhaar Card / ID</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->aaddhar_num ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Registration Date --}}
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-success-transparent text-success">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Registration Date</span>
                            <span class="text-dark fw-bold fs-13">
                                {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Registered Desk --}}
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-warning-transparent text-warning">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Registered Desk</span>
                            @if($patient->member_id)
                                <span class="text-dark fw-bold fs-13">
                                    {{ $patient->created_by }} <small class="text-muted">({{ $patient->member_id }})</small>
                                </span>
                            @else
                                <span class="text-dark fw-bold fs-13">
                                    Direct Entry
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= VISITS TIMELINE ================= -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark fs-16 mb-0">
                <i class="bi bi-clock-history text-primary me-2"></i>Consultation Records Timeline
            </h5>
            <span class="text-muted fs-12">Showing most recent consultations first</span>
        </div>

        @forelse($groupedVisits as $visitKey => $visitGroup)
            @php
                $first = $visitGroup->first();
                $visitIndex = $totalVisits - $loop->index;
            @endphp
            <div class="visit-card-valex">
                {{-- Visit Header with Date & Vitals --}}
                <div class="visit-header-valex">
                    {{-- Top Row: Visit Badge, Date/Time, and Action --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 w-100 pb-3 border-bottom">
                        <div class="d-flex flex-wrap align-items-center gap-2.5">
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 fs-12 fw-bold shadow-xs">
                                Visit #{{ $visitIndex }}
                            </span>
                            <span class="visit-date-badge fs-13.5 fw-bold text-dark d-inline-flex align-items-center gap-1.5 ms-1">
                                <i class="bi bi-calendar2-week text-primary"></i>
                                {{ $first->created_at ? \Carbon\Carbon::parse($first->created_at)->format('d M Y, h:i A') : 'Recorded Visit' }}
                            </span>
                            @if($loop->first)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-11 fw-bold ms-1 d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Latest
                                </span>
                            @endif
                        </div>

                        <div>
                            <a href="{{ route('prescription.show', $patient->id) }}" class="btn-rx-action">
                                <i class="bi bi-printer-fill"></i> View / Print Rx
                            </a>
                        </div>
                    </div>

                    {{-- Bottom Row: Labeled Vitals Examination Strip (4 on Top, 3 on Bottom) --}}
                    <div class="vitals-container-strip">
                        
                        {{-- Row 1: BP, Pulse, Temp, SpO2 (4 Vitals) --}}
                        <div class="vitals-row-strip">
                            <span class="text-uppercase fs-12 fw-bold text-muted me-1 d-flex align-items-center gap-1.5" style="min-width: 68px;">
                                <i class="bi bi-heart-pulse-fill text-danger fs-14"></i> Vitals:
                            </span>

                            @if($first->blood_pressure)
                                <span class="vital-pill" title="Blood Pressure">
                                    <i class="bi bi-activity text-danger"></i>
                                    <span class="text-muted fw-normal">BP:</span>
                                    <strong>{{ $first->blood_pressure }} mmHg</strong>
                                </span>
                            @endif

                            @if($first->pulse_rate)
                                <span class="vital-pill" title="Pulse Rate">
                                    <i class="bi bi-heart-pulse text-danger"></i>
                                    <span class="text-muted fw-normal">Pulse:</span>
                                    <strong>{{ $first->pulse_rate }} bpm</strong>
                                </span>
                            @endif

                            @if($first->temperature)
                                <span class="vital-pill" title="Body Temperature">
                                    <i class="bi bi-thermometer-half text-warning"></i>
                                    <span class="text-muted fw-normal">Temp:</span>
                                    <strong>{{ $first->temperature }}&deg;F</strong>
                                </span>
                            @endif

                            @if($first->spo2)
                                <span class="vital-pill" title="Oxygen Saturation">
                                    <i class="bi bi-lungs text-info"></i>
                                    <span class="text-muted fw-normal">SpO2:</span>
                                    <strong>{{ $first->spo2 }}%</strong>
                                </span>
                            @endif
                        </div>

                        {{-- Row 2: Weight, Blood Group, Sugar (3 Vitals) --}}
                        <div class="vitals-row-strip">
                            <span class="text-uppercase fs-12 fw-bold text-muted me-1 d-none d-md-inline-block" style="min-width: 68px; visibility: hidden;">
                                <i class="bi bi-heart-pulse-fill text-danger fs-14"></i> Vitals:
                            </span>

                            @if($first->weight)
                                <span class="vital-pill" title="Body Weight">
                                    <i class="bi bi-speedometer2 text-primary"></i>
                                    <span class="text-muted fw-normal">Weight:</span>
                                    <strong>{{ $first->weight }} kg</strong>
                                </span>
                            @endif

                            @if($first->blood_groups)
                                <span class="vital-pill" title="Blood Group">
                                    <i class="bi bi-droplet-fill text-danger"></i>
                                    <span class="text-muted fw-normal">Blood Group:</span>
                                    <strong>{{ $first->blood_groups }}</strong>
                                </span>
                            @endif

                            @if($first->sugar)
                                @php
                                    $cleanSugar = str_replace([':', ','], [': ', ' &bull; '], $first->sugar);
                                @endphp
                                <span class="vital-pill" title="Blood Sugar (mg/dL)">
                                    <i class="bi bi-droplet text-purple"></i>
                                    <span class="text-muted fw-normal">Sugar:</span>
                                    <strong>{!! $cleanSugar !!}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="visit-body-valex">
                    <div class="row g-4">

                        {{-- Symptoms & Diagnosis (Left Column) --}}
                        <div class="col-lg-6">
                            {{-- Chief Complaints --}}
                            <div class="mb-3">
                                <span class="history-section-title">
                                    <i class="bi bi-clipboard-pulse text-primary"></i> Chief Complaints / Symptoms
                                </span>
                                <div>
                                    @if($first->symptoms)
                                        @foreach(explode(',', $first->symptoms) as $symptom)
                                            <span class="chip-symptom">{{ trim($symptom) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted fs-12 fst-italic">No specific symptoms recorded</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Clinical Diagnosis --}}
                            <div class="mb-3">
                                <span class="history-section-title">
                                    <i class="bi bi-file-earmark-medical text-dark"></i> Clinical Diagnosis
                                </span>
                                <div>
                                    @if(!empty(trim($first->diagnosis ?? '')))
                                        @if(strpos($first->diagnosis, ',') !== false)
                                            @foreach(explode(',', $first->diagnosis) as $diagItem)
                                                @if(trim($diagItem) !== '')
                                                    <span class="chip-diagnosis">{{ trim($diagItem) }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="px-3 py-2 bg-light rounded-3 border text-dark fs-13 lh-base fw-medium">
                                                {{ $first->diagnosis }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted fs-12 fst-italic d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-info-circle"></i> No clinical diagnosis notes recorded
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Lab Tests Advised --}}
                            <div>
                                <span class="history-section-title">
                                    <i class="bi bi-eyedropper text-success"></i> Recommended Investigations / Tests
                                </span>
                                <div>
                                    @if($first->diagnosis_test)
                                        @foreach(explode(',', $first->diagnosis_test) as $test)
                                            <span class="chip-test">{{ trim($test) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted fs-12 fst-italic">No laboratory tests requested</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Prescribed Medications Table (Right Column) --}}
                        <div class="col-lg-6">
                            <span class="history-section-title">
                                <i class="bi bi-capsule text-purple"></i> Prescribed Medications (Rx)
                            </span>

                            @php
                                $validMedicines = $visitGroup->filter(function($row) {
                                    return $row->medicine != null || $row->medicine_id != null;
                                });
                            @endphp

                            @if($validMedicines->count() > 0)
                                <div class="table-responsive">
                                    <table class="history-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%;">Medicine</th>
                                                <th>Dosage</th>
                                                <th>Unit</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($validMedicines as $medRow)
                                                <tr>
                                                    <td class="fw-bold text-primary">
                                                        {{ $medRow->medicine->medicine_name ?? 'Medicine #'.$medRow->medicine_id }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            {{ optional($medRow->dosageName)->dosage_name ?? ($medRow->dosage ?? '-') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            {{ optional($medRow->unitName)->unit_name ?? ($medRow->unit ?? '-') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-transparent text-info">
                                                            {{ optional($medRow->intervalName)->interval_name ?? ($medRow->frequency ?? '-') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-purple-transparent text-purple">
                                                            {{ optional($medRow->durationName)->duration_name ?? ($medRow->duration ?? '-') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 border text-muted fs-12 fst-italic">
                                    <i class="bi bi-info-circle me-1"></i> No medicines prescribed during this visit.
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Advice & Follow-Up Strip -->
                    <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <span class="history-section-title mb-1">
                                <i class="bi bi-chat-left-text text-info"></i> Advice &amp; Instructions
                            </span>
                            <div>
                                @if(!empty($first->advice_names))
                                    @foreach($first->advice_names as $advName)
                                        <span class="chip-advice">{{ $advName }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted fs-12 fst-italic">General standard care advised</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            @if($first->followup)
                                <div>
                                    <span class="d-block text-uppercase fs-10 fw-bold text-muted">Follow-Up Schedule</span>
                                    <span class="badge bg-warning-transparent text-warning px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                        <i class="bi bi-calendar-check me-1"></i> {{ $first->followup }}
                                    </span>
                                </div>
                            @endif

                            <a href="{{ route('prescription.show', $patient->id) }}" class="btn btn-valex-light btn-sm fw-medium">
                                <i class="bi bi-printer me-1"></i> View / Print Prescription
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="empty-history-box shadow-sm">
                <i class="bi bi-folder-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                <h5 class="fw-bold text-dark mb-1">No Past Visits Found</h5>
                <p class="text-muted fs-13 mb-3">This patient has not had any consultation or prescription recorded yet.</p>
                <a href="{{ route('addsymptoms', $patient->id) }}" class="btn btn-valex-primary px-4 py-2">
                    <i class="bi bi-plus-circle me-1"></i> Write First Prescription
                </a>
            </div>
        @endforelse

    </div>
</div>
@endsection