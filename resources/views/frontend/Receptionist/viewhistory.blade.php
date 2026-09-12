@extends('backend.include.layout')

@section('title', 'Patient History - Receptionist Desk')

@section('content')
<style>
    .vh-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .vh-header {
        background: #fbfcfe;
        border-bottom: 1px solid #f1f4f9;
        padding: 16px 22px;
    }
    .demographic-tile {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
    }
    .demographic-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .vital-pill-item {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 12px;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .vital-pill-item:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }
    .vital-badge-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .chip-symptom {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(1, 98, 232, 0.08);
        color: #0162e8;
        border: 1px solid rgba(1, 98, 232, 0.18);
        border-radius: 20px;
        padding: 3px 10px;
        margin: 2px 4px 4px 0;
        font-size: 12px;
        font-weight: 500;
    }
    .chip-diagnosis {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(14, 165, 233, 0.08);
        color: #0284c7;
        border: 1px solid rgba(14, 165, 233, 0.2);
        border-radius: 20px;
        padding: 3px 10px;
        margin: 2px 4px 4px 0;
        font-size: 12px;
        font-weight: 500;
    }
    .chip-test {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(22, 163, 74, 0.08);
        color: #16a34a;
        border: 1px solid rgba(22, 163, 74, 0.18);
        border-radius: 20px;
        padding: 3px 10px;
        margin: 2px 4px 4px 0;
        font-size: 12px;
        font-weight: 500;
    }
    .chip-advice {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(121, 40, 202, 0.08);
        color: #7928ca;
        border: 1px solid rgba(121, 40, 202, 0.18);
        border-radius: 20px;
        padding: 3px 10px;
        margin: 2px 4px 4px 0;
        font-size: 12px;
        font-weight: 500;
    }
    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9edf4;
    }
    .history-table th {
        background-color: #f8fafd;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 8px 12px;
        border-bottom: 1px solid #e9edf4;
    }
    .history-table td {
        padding: 9px 12px;
        font-size: 12px;
        color: #334155;
        border-bottom: 1px solid #f1f4f9;
        vertical-align: middle;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Patient Medical History &amp; Date-wise Visits
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Receptionist Console</span> &bull; Full history timeline, vitals &amp; prescription records for {{ $patient->patient_name }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('receptionist.all_patients') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to All Patients
                </a>
                <a href="{{ route('receptionist.dashboard') }}" class="btn btn-primary rounded-pill px-3 btn-sm fw-semibold">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>
        </div>

        @php
            $pInitials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
            $groupedVisits = $patient->presciption_data->sortByDesc(function($item) {
                return $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : $item->id;
            })->groupBy(function($item) {
                return $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') : 'visit_'.$item->id;
            });
            $totalVisits = $groupedVisits->count();
        @endphp

        <!-- Patient Demographics Profile Card -->
        <div class="vh-card p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle fw-bold fs-16 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        {{ $pInitials }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="mb-0 fw-bold text-dark fs-18">{{ $patient->patient_name }}</h4>
                            <span class="badge bg-light text-primary border font-monospace fs-12 px-2.5 py-1 rounded">
                                Reg No: {{ $patient->registration ?? $patient->patient_id }}
                            </span>
                        </div>
                        <small class="text-muted fs-12">
                            Doctor: <strong class="text-dark">Dr. {{ $patient->doctor->name ?? $patient->created_by ?? 'Doctor' }}</strong>
                        </small>
                    </div>
                </div>

                <div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-12 fw-bold">
                        <i class="bi bi-journal-medical me-1"></i> {{ $totalVisits }} {{ Str::plural('Past Visit', $totalVisits) }} Recorded
                    </span>
                </div>
            </div>

            <!-- Demographic Info Grid -->
            <div class="row g-3 pt-3 border-top">
                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-purple-subtle text-purple">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Guardian Name</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->husband_father_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-indigo-subtle text-indigo">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Gender &amp; Age</span>
                            <span class="text-dark fw-bold fs-13">
                                {{ ucfirst($patient->gender ?? '-') }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : ($patient->age_month ? $patient->age_month.' Mos' : '-') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-primary-subtle text-primary">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Phone Number</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->mobile ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-info-subtle text-info">
                            <i class="bi bi-card-text"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Aadhaar Card</span>
                            <span class="text-dark fw-bold fs-13">{{ $patient->aaddhar_num ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-success-subtle text-success">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">First Reg. Date</span>
                            <span class="text-dark fw-bold fs-13">
                                {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 col-lg-4 col-md-6 col-12">
                    <div class="demographic-tile">
                        <div class="demographic-icon bg-warning-subtle text-warning">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fs-10 fw-bold text-muted">Fee Category</span>
                            <span class="text-dark fw-bold fs-13">
                                {{ $patient->paymentCategory->name ?? 'Standard' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date-Wise Visit Timeline Section -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark fs-16 mb-0">
                <i class="bi bi-calendar3 me-2 text-primary"></i>Date-Wise Medical History Timeline
            </h5>
            <span class="text-muted fs-12">Showing newest visits first</span>
        </div>

        @forelse($groupedVisits as $visitKey => $visitGroup)
            @php
                $first = $visitGroup->first();
                $visitIndex = $totalVisits - $loop->index;
                $catObj = $first->paymentCategory ?? $patient->paymentCategory;
            @endphp
            <div class="vh-card">
                <!-- Visit Header -->
                <div class="vh-header d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill px-3 py-1.5 fs-12 fw-bold">
                            Visit #{{ $visitIndex }}
                        </span>
                        <span class="fs-14 fw-bold text-dark">
                            <i class="bi bi-calendar-event me-1 text-primary"></i>
                            {{ $first->created_at ? \Carbon\Carbon::parse($first->created_at)->format('d M Y, h:i A') : 'Recorded Visit' }}
                        </span>
                        @if($loop->first)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-11 fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Latest Visit
                            </span>
                        @endif
                    </div>

                    <div>
                        @if($catObj)
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                Fee Category: {{ $catObj->name ?? 'Standard' }}
                                @if(!empty($catObj->price))
                                    (₹{{ number_format($catObj->price, 2) }})
                                @endif
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Vitals Strip (4 Top, 3 Bottom Full-Width Grid) -->
                <div class="p-4 pb-4 bg-light border-bottom">
                    <div class="d-flex flex-column gap-3 mb-1">
                        <!-- Vitals Header Label -->
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fs-11 fw-bold text-muted letter-spacing-1">
                                <i class="bi bi-heart-pulse-fill text-danger me-1"></i> Physical Examination Vitals:
                            </span>
                        </div>

                        <!-- Row 1: Top 4 Vitals Grid (Full Width 4 Columns) -->
                        <div class="row g-3">
                            @if($first->blood_pressure)
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                    <div class="vital-pill-item w-100 h-100" title="Blood Pressure">
                                        <div class="vital-badge-icon bg-danger-subtle text-danger"><i class="bi bi-activity"></i></div>
                                        <div>
                                            <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">BP</small>
                                            <span class="fw-bold text-dark fs-13">{{ $first->blood_pressure }} <span class="fs-10 fw-normal text-muted">mmHg</span></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($first->pulse_rate)
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                    <div class="vital-pill-item w-100 h-100" title="Pulse Rate">
                                        <div class="vital-badge-icon bg-danger-subtle text-danger"><i class="bi bi-heart-pulse-fill"></i></div>
                                        <div>
                                            <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">Pulse</small>
                                            <span class="fw-bold text-dark fs-13">{{ $first->pulse_rate }} <span class="fs-10 fw-normal text-muted">bpm</span></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($first->temperature)
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                    <div class="vital-pill-item w-100 h-100" title="Body Temperature">
                                        <div class="vital-badge-icon bg-warning-subtle text-warning"><i class="bi bi-thermometer-half"></i></div>
                                        <div>
                                            <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">Temp</small>
                                            <span class="fw-bold text-dark fs-13">{{ $first->temperature }}&deg;F</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($first->spo2)
                                <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                    <div class="vital-pill-item w-100 h-100" title="Oxygen Saturation">
                                        <div class="vital-badge-icon bg-info-subtle text-info"><i class="bi bi-lungs-fill"></i></div>
                                        <div>
                                            <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">SpO2</small>
                                            <span class="fw-bold text-dark fs-13">{{ $first->spo2 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Row 2: Bottom 3 Vitals Grid (Full Width 3 Columns) -->
                        @if($first->weight || $first->blood_groups || $first->sugar)
                            <div class="row g-3">
                                @if($first->weight)
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                        <div class="vital-pill-item w-100 h-100" title="Body Weight">
                                            <div class="vital-badge-icon bg-primary-subtle text-primary"><i class="bi bi-speedometer2"></i></div>
                                            <div>
                                                <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">Weight</small>
                                                <span class="fw-bold text-dark fs-13">{{ $first->weight }} <span class="fs-10 fw-normal text-muted">kg</span></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($first->blood_groups)
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                        <div class="vital-pill-item w-100 h-100" title="Blood Group">
                                            <div class="vital-badge-icon bg-danger-subtle text-danger"><i class="bi bi-droplet-fill"></i></div>
                                            <div>
                                                <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">Blood Group</small>
                                                <span class="fw-bold text-dark fs-13">{{ $first->blood_groups }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($first->sugar)
                                    @php
                                        $cleanSugar = str_replace([':', ','], [': ', ' &bull; '], $first->sugar);
                                    @endphp
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                                        <div class="vital-pill-item w-100 h-100" title="Blood Sugar">
                                            <div class="vital-badge-icon" style="color: #6c5ffc; background: #f4f2ff;"><i class="bi bi-droplet"></i></div>
                                            <div>
                                                <small class="text-muted fs-10 text-uppercase d-block fw-bold" style="line-height: 1.1;">Blood Sugar</small>
                                                <span class="fw-bold text-dark fs-13">{!! $cleanSugar !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(!$first->blood_pressure && !$first->pulse_rate && !$first->temperature && !$first->spo2 && !$first->weight && !$first->blood_groups && !$first->sugar)
                            <span class="text-muted fs-12 fst-italic">No physical examination vitals recorded for this visit date.</span>
                        @endif
                    </div>
                </div>

                <!-- Visit Details Grid -->
                <div class="p-4" style="padding-top: 28px !important;">
                    <div class="row g-4">
                        <!-- Symptoms, Diagnosis, Tests -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Chief Complaints / Symptoms</small>
                                @if($first->symptoms)
                                    @foreach(explode(',', $first->symptoms) as $sym)
                                        <span class="chip-symptom">{{ trim($sym) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted fs-12 fst-italic">No symptoms specified</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Clinical Diagnosis</small>
                                @if(!empty(trim($first->diagnosis ?? '')))
                                    <span class="chip-diagnosis">{{ $first->diagnosis }}</span>
                                @else
                                    <span class="text-muted fs-12 fst-italic">No diagnosis notes</span>
                                @endif
                            </div>

                            <div>
                                <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Lab / Diagnostic Tests Advised</small>
                                @if($first->diagnosis_test)
                                    @foreach(explode(',', $first->diagnosis_test) as $test)
                                        <span class="chip-test">{{ trim($test) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted fs-12 fst-italic">No lab tests requested</span>
                                @endif
                            </div>
                        </div>

                        <!-- Prescribed Medicines Table -->
                        <div class="col-lg-6">
                            <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Prescribed Medicines (Rx)</small>

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
                                                <th>Medicine Name</th>
                                                <th>Dosage</th>
                                                <th>Unit</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($validMedicines as $m)
                                                <tr>
                                                    <td class="fw-bold text-primary">
                                                        {{ $m->medicine->medicine_name ?? 'Medicine #'.$m->medicine_id }}
                                                    </td>
                                                    <td>{{ optional($m->dosageName)->dosage_name ?? ($m->dosage ?? '-') }}</td>
                                                    <td>{{ optional($m->unitName)->unit_name ?? ($m->unit ?? '-') }}</td>
                                                    <td>{{ optional($m->intervalName)->interval_name ?? ($m->frequency ?? '-') }}</td>
                                                    <td>{{ optional($m->durationName)->duration_name ?? ($m->duration ?? '-') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded border text-muted fs-12 fst-italic">
                                    No medicines prescribed on this date.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Advice & Followup -->
                    @if($first->advice_names || $first->followup)
                        <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                @if(!empty($first->advice_names))
                                    <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Advice &amp; Instructions</small>
                                    @foreach($first->advice_names as $adv)
                                        <span class="chip-advice">{{ $adv }}</span>
                                    @endforeach
                                @endif
                            </div>
                            @if($first->followup)
                                <div>
                                    <small class="text-uppercase text-muted fw-bold fs-11 d-block mb-1">Follow-up Date</small>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill fs-12 fw-semibold">
                                        <i class="bi bi-calendar-check me-1"></i> {{ $first->followup }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="vh-card p-5 text-center text-muted">
                <i class="bi bi-folder-x display-4 d-block mb-2 text-muted opacity-50"></i>
                <h5 class="fw-bold text-dark mb-1">No Past Visits Recorded</h5>
                <p class="fs-12 mb-0">This patient has no recorded consultation or prescription history yet.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection
