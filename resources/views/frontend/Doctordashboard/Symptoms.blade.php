@extends("frontend.include.layout")

@section('title', 'Chief Complaints & Patient Queue - Doctor Portal')

@section('content')
<style>
    /* Customizer tab hide */
    .customizer-setting, #customizer-layout, .customizer-btn, [data-bs-target="#theme-settings-offcanvas"] {
        display: none !important;
    }

    .valex-filter-input {
        height: 38px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 13px !important;
    }
    .valex-filter-input:focus {
        border-color: #0162e8 !important;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12) !important;
    }

    .table-valex-styled th {
        background-color: #f8fafd !important;
        color: #6b7280 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.03em !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e9edf4 !important;
    }

    .table-valex-styled td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f4f9 !important;
    }

    .table-valex-styled tbody tr:hover td {
        background-color: #f9fbfe !important;
    }

    .btn-action-icon {
        width: 34px !important;
        height: 34px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 7px !important;
        transition: all 0.2s ease !important;
    }
    .btn-action-icon:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .btn-rx-consult {
        background-color: #0162e8 !important;
        border: 1px solid #0162e8 !important;
        color: #ffffff !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        white-space: nowrap !important;
        height: 34px !important;
        box-shadow: 0 2px 6px rgba(1, 98, 232, 0.25) !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-rx-consult:hover {
        background-color: #0150bf !important;
        border-color: #0150bf !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(1, 98, 232, 0.35) !important;
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
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header Breadcrumb -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Chief Complaints &amp; Patient Queue
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Doctor Consultation Desk</span> &bull; Patients registered by your Receptionist &amp; Clinic Desk
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('Addpatient') }}" class="btn-header-add">
                    <i class="bi bi-person-plus-fill fs-15"></i> Add New Patient
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="valex-card mb-4 shadow-sm border-0" style="border-radius: 12px;">
            <div class="valex-card-header py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-funnel-fill text-primary fs-5"></i>
                    <h6 class="valex-card-title mb-0 fs-14 fw-bold text-dark">Search &amp; Filter Patients</h6>
                </div>
            </div>
            <div class="valex-card-body p-4">
                <form action="{{ route('patient.list') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        {{-- Patient ID Dropdown --}}
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fw-semibold fs-12 text-muted mb-1">Patient ID</label>
                            <select name="patient_id" class="form-select form-select-sm valex-filter-input">
                                <option value="">All Patient IDs</option>
                                @foreach($patientIds as $pid)
                                    <option value="{{ $pid }}" @selected(request('patient_id') == $pid)>
                                        {{ $pid }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Registration Number --}}
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-semibold fs-12 text-muted mb-1">Registration Number</label>
                            <input type="text" name="registration" class="form-control form-control-sm valex-filter-input" placeholder="e.g. 26081100001" value="{{ request('registration') }}">
                        </div>

                        {{-- Patient Name --}}
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-semibold fs-12 text-muted mb-1">Patient Name</label>
                            <input type="text" name="patient_name" class="form-control form-control-sm valex-filter-input" placeholder="Search by name" value="{{ request('patient_name') }}">
                        </div>

                        {{-- Date --}}
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fw-semibold fs-12 text-muted mb-1">Date</label>
                            <input type="date" name="from_date" class="form-control form-control-sm valex-filter-input" value="{{ request('from_date') }}">
                        </div>

                        {{-- Filter & Reset Action Buttons --}}
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit" class="btn btn-valex-primary btn-sm flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1.5" style="height: 38px; border-radius: 8px;">
                                    <i class="bi bi-search"></i> <span>Filter</span>
                                </button>
                                <a href="{{ route('patient.list') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center justify-content-center" style="height: 38px; width: 38px; min-width: 38px; border-radius: 8px;" title="Reset Filters">
                                    <i class="bi bi-arrow-clockwise fs-15 text-muted"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Patient List Table Card -->
        <div class="valex-card shadow-sm border-0" style="border-radius: 14px;">
            <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between py-3 px-4 border-bottom gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary fs-5"></i>
                    <h5 class="valex-card-title mb-0 fw-bold fs-15 text-dark">Patient Queue &amp; Chief Complaints</h5>
                    <span class="badge bg-primary-transparent text-primary rounded-pill px-3 py-1.5 fs-12 fw-semibold ms-2">
                        {{ $pat->total() ?? $pat->count() }} Total Patients
                    </span>
                </div>
            </div>

            <div class="valex-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-valex-styled">
                        <thead>
                            <tr>
                                <th class="ps-4">Registration No</th>
                                <th>Patient Info</th>
                                <th>Contact &amp; Aadhaar</th>
                                <th>Registered By (Desk)</th>
                                <th>Date &amp; Status</th>
                                <th class="text-end pe-4">Consultation Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pat as $pati)
                            @php
                                $initials = strtoupper(substr($pati->patient_name ?? 'P', 0, 2));
                                $isCompleted = $pati->is_completed;
                            @endphp
                            <tr>
                                {{-- Registration No --}}
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace fs-12 px-2.5 py-1.5 rounded">
                                        {{ $pati->registration ?? $pati->patient_id ?? '-' }}
                                    </span>
                                </td>

                                {{-- Patient Info (Avatar + Name & Details in Flex) --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="valex-avatar-initials bg-primary-transparent text-primary rounded-circle fw-bold fs-13 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; min-width: 38px;">
                                            {{ $initials }}
                                        </div>
                                        <div class="d-flex flex-column gap-0.5">
                                            <span class="fw-bold fs-13 text-dark mb-0">{{ $pati->patient_name ?? 'Patient' }}</span>
                                            <div class="d-flex flex-wrap align-items-center gap-1.5 mt-0.5">
                                                <span class="text-muted fs-11">
                                                    {{ ucfirst($pati->gender ?? '-') }}, {{ $pati->age_year ? $pati->age_year.' Yrs' : ($pati->age_month ? $pati->age_month.' Mos' : '-') }}
                                                </span>
                                                @if($pati->husband_father_name)
                                                    @php
                                                        $sLabel = (strtolower($pati->guardian_type ?? '') === 'husband') ? "Husband's Name" : "Father's Name";
                                                    @endphp
                                                    <span class="badge bg-light text-secondary border px-2 py-0.5 rounded fs-10 ms-1">
                                                        <i class="bi bi-person-heart text-muted me-1"></i>{{ $sLabel }}: <strong class="text-dark">{{ $pati->husband_father_name }}</strong>
                                                    </span>
                                                @endif
                                                @if($pati->paymentCategory)
                                                    <span class="badge bg-primary-transparent text-primary border border-primary-subtle px-2 py-0.5 rounded fs-10 ms-1" title="Payment Category">
                                                        <i class="bi bi-wallet2 me-1"></i>{{ $pati->paymentCategory->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact & Aadhaar --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="fs-12 text-dark fw-medium">
                                            {{ $pati->mobile ?? '-' }}
                                        </span>&
                                        <span class="fs-11 text-muted">
                                            {{ $pati->aaddhar_num ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Registered By Desk --}}
                                <td>
                                    @if($pati->member_id)
                                        <span class="badge bg-info-transparent text-info px-2.5 py-1.5 rounded-pill fs-11 fw-semibold" title="Member ID: {{ $pati->member_id }}">
                                            <i class="bi bi-person-badge-fill me-1"></i>{{ $pati->created_by }} <small class="opacity-75">({{ $pati->member_id }})</small>
                                        </span>
                                    @elseif($pati->created_by)
                                        <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill fs-11 fw-medium">
                                            <i class="bi bi-person-check me-1"></i>Dr. {{ $pati->created_by }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-11">
                                            Direct Entry
                                        </span>
                                    @endif
                                </td>

                                {{-- Date & Status --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="fs-11 text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $pati->created_at ? \Carbon\Carbon::parse($pati->created_at)->format('d M Y, h:i A') : '-' }}
                                        </span>
                                        <div>
                                            @if($isCompleted)
                                                <span class="badge bg-success-transparent text-success rounded-pill px-2.5 py-1 fs-11 fw-semibold">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Completed
                                                </span>
                                            @else
                                                <span class="badge bg-warning-transparent text-warning rounded-pill px-2.5 py-1 fs-11 fw-semibold">
                                                    <i class="bi bi-hourglass-split me-1"></i>Waiting
                                                </span>
                                            @endif
                                            @php
                                                $hasStaffExam = $pati->presciption_data && $pati->presciption_data->whereNotNull('blood_pressure')->isNotEmpty();
                                            @endphp
                                            @if($hasStaffExam && !$isCompleted)
                                                <div class="mt-1">
                                                    <span class="badge bg-info-transparent text-info rounded-pill px-2 py-0.5 fs-10 fw-semibold">
                                                        <i class="bi bi-heart-pulse-fill me-1"></i>Vitals by Staff
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Consultation Action Buttons --}}
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end gap-1.5">
                                        {{-- Add Symptoms & Rx Button --}}
                                        <a href="{{ route('addsymptoms', $pati->id) }}" class="btn-rx-consult" title="Write Prescription & Clinical Consultation">
                                            <i class="bi bi-prescription2 fs-15"></i> Symptoms &amp; Rx
                                        </a>

                                        {{-- History Button --}}
                                        <a href="{{ route('patient.history', $pati->id) }}" class="btn btn-light border btn-action-icon text-info" title="View Patient History">
                                            <i class="bi bi-clock-history fs-14"></i>
                                        </a>

                                        {{-- Edit Button --}}
                                        <a href="{{ route('patient.edit', $pati->id) }}" class="btn btn-light border btn-action-icon text-warning" title="Edit Patient Details">
                                            <i class="bi bi-pencil-square fs-14"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a href="{{ route('patient.delete', $pati->id) }}" class="btn btn-light border btn-action-icon text-danger" title="Delete Record" onclick="return confirm('Kya aap is patient record ko delete karna chahte hain?');">
                                            <i class="bi bi-trash fs-14"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-muted opacity-50"></i>
                                    No patients found in your consultation queue.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pat->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $pat->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection