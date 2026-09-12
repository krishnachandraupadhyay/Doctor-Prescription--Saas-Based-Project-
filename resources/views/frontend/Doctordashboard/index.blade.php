@extends("frontend.include.layout")

@section('title', 'Doctor Dashboard')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        @if(Auth::guard('doctor')->user()->verified == 0)
            <!-- Unverified Doctor Alert Banner -->
            <div class="valex-card alert-card border-warning-subtle mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="valex-icon-circle bg-warning-transparent text-warning">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold text-dark">Verification Pending</h6>
                        <p class="mb-0 text-muted fs-13">Your doctor account is awaiting administrative verification. Some clinical features might be limited until verified.</p>
                    </div>
                    <a href="{{ route('UploadDocument') }}" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                        <i class="bi bi-upload me-1"></i> Upload Documents
                    </a>
                </div>
            </div>
        @endif

        <!-- Valex Page Header / Welcome Breadcrumb -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Hi, Welcome Dr. {{ Auth::guard('doctor')->user()->name ?? 'Doctor' }}!
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinic Overview</span> &bull; Patient Management &amp; Prescription Portal
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="valex-date-badge">
                    <i class="bi bi-calendar3 me-1 text-primary"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </span>
                <a href="{{ route('Addpatient') }}" class="btn btn-valex-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Patient
                </a>
                <a href="{{ route('Addpatient') }}" class="btn btn-valex-success btn-sm">
                    <i class="bi bi-file-earmark-medical-fill me-1"></i> New Prescription
                </a>
                <a href="{{ route('downloadpdf') }}" class="btn btn-valex-light btn-sm" title="Download Reports">
                    <i class="bi bi-download me-1"></i> PDF
                </a>
            </div>
        </div>

        @php
            $latestDocReq = \App\Models\DoctorCorrectionRequest::where('doctor_id', Auth::guard('doctor')->id())
                ->latest()
                ->first();
        @endphp

        @if($latestDocReq && ($latestDocReq->created_at->diffInDays(now()) < 3 || $latestDocReq->status === 'pending'))
            <div class="alert {{ $latestDocReq->status === 'approved' ? 'alert-success border-success-subtle' : ($latestDocReq->status === 'rejected' ? 'alert-danger border-danger-subtle' : 'alert-warning border-warning-subtle') }} alert-dismissible fade show rounded-4 shadow-sm mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;" class="{{ $latestDocReq->status === 'approved' ? 'bg-success text-white' : ($latestDocReq->status === 'rejected' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">
                        <i class="bi {{ $latestDocReq->status === 'approved' ? 'bi-check2-circle fs-5' : ($latestDocReq->status === 'rejected' ? 'bi-x-circle fs-5' : 'bi-hourglass-split fs-5') }}"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold fs-14">
                            @if($latestDocReq->status === 'approved')
                                Profile Correction Approved &bull; {{ $latestDocReq->field_label }}
                            @elseif($latestDocReq->status === 'rejected')
                                Profile Correction Rejected &bull; {{ $latestDocReq->field_label }}
                            @else
                                Profile Correction Request Pending &bull; {{ $latestDocReq->field_label }}
                            @endif
                        </h6>
                        <p class="mb-0 fs-12 text-muted">
                            @if($latestDocReq->status === 'approved')
                                Approved by <strong>{{ $latestDocReq->reviewed_by ?: ($latestDocReq->clinic->name ?? 'Clinic') }}</strong>. Your profile has been updated to <strong>{{ $latestDocReq->requested_value }}</strong>.
                            @elseif($latestDocReq->status === 'rejected')
                                Rejected by <strong>{{ $latestDocReq->reviewed_by ?: ($latestDocReq->clinic->name ?? 'Clinic') }}</strong>. Reason: <em>{{ $latestDocReq->admin_notes }}</em>.
                            @else
                                Your request to update <strong>{{ $latestDocReq->field_label }}</strong> to <strong>{{ $latestDocReq->requested_value }}</strong> is currently awaiting review by Clinic Administration.
                            @endif
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <a href="{{ route('frontend.profile.view') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">
                        View History
                    </a>
                    <button type="button" class="btn-close position-relative p-0" data-bs-dismiss="alert" aria-label="Close" style="top: auto; right: auto;"></button>
                </div>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- ROW 1: VALEX SIGNATURE KPI STATISTIC CARDS (4 CARDS)                      -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">

            {{-- 1. Total Patients --}}
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="valex-kpi-label">TOTAL PATIENTS</span>
                            <div class="valex-icon-circle bg-primary-transparent text-primary">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h3 class="valex-kpi-value mb-0">{{ number_format($totalPatients ?? 0) }}</h3>
                            <span class="badge bg-success-transparent text-success fw-semibold fs-12">
                                <i class="bi bi-arrow-up-right me-1"></i>+12.5%
                            </span>
                        </div>
                        <div class="valex-kpi-footer mt-2 pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
                            <span class="text-muted fs-12">This Month: <b class="text-dark">{{ $thisMonthPatients ?? 0 }}</b></span>
                            <a href="{{ route('Addpatient') }}" class="text-primary fs-12 fw-medium text-decoration-none">
                                View all <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Today's Appointments --}}
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="valex-kpi-label">TODAY'S APPOINTMENTS</span>
                            <div class="valex-icon-circle bg-danger-transparent text-danger">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h3 class="valex-kpi-value mb-0">{{ number_format($todayPatients ?? 0) }}</h3>
                            <span class="badge bg-danger-transparent text-danger fw-semibold fs-12">
                                <i class="bi bi-clock-history me-1"></i>Active Today
                            </span>
                        </div>
                        <div class="valex-kpi-footer mt-2 pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
                            <span class="text-muted fs-12">Waiting: <b class="text-danger">{{ $waitingToday ?? 0 }}</b> &bull; Done: <b class="text-success">{{ $completedToday ?? 0 }}</b></span>
                            <a href="#today-queue-section" class="text-danger fs-12 fw-medium text-decoration-none">
                                Queue <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Total Medicine Formulary --}}
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="valex-kpi-label">MEDICINE INVENTORY</span>
                            <div class="valex-icon-circle bg-success-transparent text-success">
                                <i class="bi bi-capsule-pill"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h3 class="valex-kpi-value mb-0">{{ number_format($totalMedicine ?? 0) }}</h3>
                            <span class="badge bg-success-transparent text-success fw-semibold fs-12">
                                <i class="bi bi-check2-all me-1"></i>Available
                            </span>
                        </div>
                        <div class="valex-kpi-footer mt-2 pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
                            <span class="text-muted fs-12">Formulary Items</span>
                            <a href="{{ route('listofmedicine') }}" class="text-success fs-12 fw-medium text-decoration-none">
                                Formulary <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Clinic Members & Staff --}}
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="valex-kpi-label">CLINIC STAFF &amp; MEMBERS</span>
                            <div class="valex-icon-circle bg-warning-transparent text-warning">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            <h3 class="valex-kpi-value mb-0">{{ number_format($totalMembers ?? 0) }}</h3>
                            <span class="badge bg-warning-transparent text-warning fw-semibold fs-12">
                                <i class="bi bi-shield-check me-1"></i>Active Team
                            </span>
                        </div>
                        <div class="valex-kpi-footer mt-2 pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
                            <span class="text-muted fs-12">Receptionist &amp; Staff</span>
                            <a href="{{ route('addmember') }}" class="text-warning fs-12 fw-medium text-decoration-none">
                                View Team <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- ROW 2: VALEX PATIENT ANALYTICS (CHART) + DOCTOR CLINIC PROFILE WIDGET     -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">

            {{-- Patients Analytics Chart (Valex Style Spline Area) --}}
            <div class="col-xxl-8 col-xl-8 col-lg-7">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Patient Visits &amp; Consultation Trends
                            </h5>
                            <p class="text-muted fs-12 mb-0">Weekly and monthly patient consultations analysis</p>
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Chart Filter">
                            <button type="button" class="btn btn-outline-primary active" id="btn-chart-weekly" onclick="switchChartMode('weekly')">Last 7 Days</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-chart-monthly" onclick="switchChartMode('monthly')">Monthly Trend</button>
                        </div>
                    </div>
                    <div class="valex-card-body">
                        <!-- ApexCharts Container -->
                        <div id="patient-trend-apexchart" style="min-height: 280px;"></div>

                        <!-- Mini metrics underneath chart -->
                        <div class="row g-2 pt-3 border-top border-light-subtle mt-2 text-center">
                            <div class="col-4 border-end">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">This Week</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $thisWeekPatients ?? 0 }} <span class="text-muted fs-11 fw-normal">Patients</span></h6>
                            </div>
                            <div class="col-4 border-end">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">This Month</p>
                                <h6 class="mb-0 fw-bold text-primary">{{ $thisMonthPatients ?? 0 }} <span class="text-muted fs-11 fw-normal">Patients</span></h6>
                            </div>
                            <div class="col-4">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">Total Prescriptions</p>
                                <h6 class="mb-0 fw-bold text-success">{{ $totalPatients ?? 0 }} <span class="text-muted fs-11 fw-normal">All Time</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doctor Profile & Clinic Overview Widget (Valex Doctor Card) --}}
            <div class="col-xxl-4 col-xl-4 col-lg-5">
                <div class="valex-card h-100 valex-doctor-card">
                    <div class="valex-card-header d-flex align-items-center justify-content-between">
                        <h5 class="valex-card-title mb-0">
                            <i class="bi bi-person-badge-fill me-2 text-primary"></i>Doctor &amp; Clinic Profile
                        </h5>
                        <span class="badge bg-{{ Auth::guard('doctor')->user()->verified == 1 ? 'success-transparent text-success' : 'warning-transparent text-warning' }} rounded-pill">
                            <i class="bi bi-{{ Auth::guard('doctor')->user()->verified == 1 ? 'patch-check-fill' : 'clock-history' }} me-1"></i>
                            {{ Auth::guard('doctor')->user()->verified == 1 ? 'Verified Doctor' : 'Unverified' }}
                        </span>
                    </div>
                    <div class="valex-card-body">
                        @php
                            $doctorUser = Auth::guard('doctor')->user();
                            $clinicPhoto = \App\Models\doctor_clinic_document::where('doctor_id', Auth::guard('doctor')->id())
                                ->when($doctorUser && $doctorUser->clinic_id, function($q) use ($doctorUser) {
                                    $q->orWhere('clinic_id', $doctorUser->clinic_id);
                                })->value('photo');
                            $avatarImg = $clinicPhoto 
                                ? asset($clinicPhoto) 
                                : ($doctorUser->image 
                                    ? asset('storage/'.$doctorUser->image) 
                                    : ($doctorUser->logo ? asset('upload/logo/'.$doctorUser->logo) : asset('backend/assets/images/icons8-user-default-64.png')));
                        @endphp

                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-light-subtle">
                            <div class="position-relative">
                                <img src="{{ $avatarImg }}" alt="Dr. {{ $doctorUser->name ?? 'Doctor' }}" class="valex-doc-avatar shadow-sm" onerror="this.src='{{ asset('backend/assets/images/icons8-user-default-64.png') }}'">
                                <span class="valex-avatar-status bg-success"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-bold text-dark">Dr. {{ $doctorUser->name ?? 'Doctor' }}</h5>
                                <span class="badge bg-primary-transparent text-primary fw-semibold fs-11 mb-1">
                                    {{ $doctorUser->specialization ?? 'General Physician' }}
                                </span>
                                <div class="text-muted fs-12">
                                    <i class="bi bi-award me-1"></i> {{ $doctorUser->qualification ?? 'MBBS / MD' }}
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Contact & Clinic Details List -->
                        <ul class="valex-profile-list list-unstyled mb-3">
                            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle">
                                <span class="text-muted fs-13"><i class="bi bi-hospital me-2 text-primary"></i>Clinic</span>
                                <span class="fw-semibold text-dark fs-13 text-end">{{ $doctorUser->clinic_name ?? 'My Medical Clinic' }}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle">
                                <span class="text-muted fs-13"><i class="bi bi-envelope-fill me-2 text-info"></i>Email</span>
                                <span class="fw-medium text-dark fs-13 text-truncate ms-2" style="max-width: 180px;">{{ $doctorUser->email ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle">
                                <span class="text-muted fs-13"><i class="bi bi-telephone-fill me-2 text-success"></i>Phone</span>
                                <span class="fw-medium text-dark fs-13">{{ $doctorUser->phone ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle">
                                <span class="text-muted fs-13"><i class="bi bi-card-checklist me-2 text-warning"></i>Reg. No.</span>
                                <span class="badge bg-light text-dark border fs-12">{{ $doctorUser->registration_number ?? 'Reg-Approved' }}</span>
                            </li>
                            <li class="d-flex align-items-start justify-content-between py-2">
                                <span class="text-muted fs-13 flex-shrink-0"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Address</span>
                                <span class="fw-medium text-dark fs-13 text-end text-truncate ms-2" style="max-width: 180px;">{{ $doctorUser->clinic_address ?? 'Not provided' }}</span>
                            </li>
                        </ul>

                        <!-- Doctor Profile Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('frontend.profile.view') }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="bi bi-person-badge me-1"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- ROW 3: TODAY'S PATIENT QUEUE (VALEX TABLE) - FULL WIDTH                  -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4" id="today-queue-section">

            {{-- Today's Patient Queue Table --}}
            <div class="col-12">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-people-fill me-2 text-primary"></i>Today's Patient Queue
                            </h5>
                            <span class="badge bg-primary-transparent text-primary rounded-pill fw-semibold fs-12">
                                {{ isset($todayPatientList) ? count($todayPatientList) : 0 }} Patients
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="patient-table-search" class="form-control form-control-sm valex-search-input" placeholder="Search patient..." onkeyup="filterPatientTable()">
                            <a href="{{ route('Addpatient') }}" class="btn btn-valex-primary btn-sm flex-shrink-0">
                                <i class="bi bi-plus-lg me-1"></i> Add Patient
                            </a>
                        </div>
                    </div>

                    <div class="valex-card-body p-0">
                        @if(isset($todayPatientList) && count($todayPatientList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table" id="todayPatientsTable">
                                    <thead>
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">#</th>
                                            <th>Patient Name</th>
                                            <th>Contact / Phone</th>
                                            <th>Time / Token</th>
                                            <th>Status</th>
                                            <th class="text-end pe-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayPatientList as $key => $patient)
                                        @php
                                            $initials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                            $avatarColors = ['bg-primary-transparent text-primary', 'bg-success-transparent text-success', 'bg-info-transparent text-info', 'bg-warning-transparent text-warning', 'bg-danger-transparent text-danger', 'bg-purple-transparent text-purple'];
                                            $colorClass = $avatarColors[$key % count($avatarColors)];
                                            $isCompleted = $patient->is_completed;
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials {{ $colorClass }}">
                                                        {{ $initials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $patient->patient_name ?? 'Unknown' }}</h6>
                                                        <small class="text-muted fs-11">
                                                            {{ $patient->gender ?? '-' }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : ($patient->age_month ? $patient->age_month.' Mos' : '-') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fs-13 text-dark"><i class="bi bi-telephone text-muted me-1 fs-11"></i>{{ $patient->mobile ?? $patient->phone ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fs-12">
                                                    <i class="bi bi-clock me-1 text-primary"></i>{{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('h:i A') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($isCompleted)
                                                    <span class="badge bg-success-transparent text-success fw-semibold">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Completed
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-transparent text-warning fw-semibold">
                                                        <i class="bi bi-hourglass-split me-1"></i>Waiting
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('addsymptoms', $patient->id) }}" class="btn btn-sm btn-valex-primary py-1 px-2" title="Write Symptoms & Prescription">
                                                        <i class="bi bi-file-earmark-medical me-1"></i> Rx
                                                    </a>
                                                    <a href="{{ route('patient.history', $patient->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-info" title="Patient History">
                                                        <i class="bi bi-clock-history"></i>
                                                    </a>
                                                    <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-muted" title="Edit Details">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="valex-empty-state text-center py-5">
                                <div class="valex-empty-icon bg-primary-transparent text-primary mb-3">
                                    <i class="bi bi-people fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">No Patients Queued For Today Yet</h6>
                                <p class="text-muted fs-13 mb-3">Patients registered today will show up here automatically.</p>
                                <a href="{{ route('Addpatient') }}" class="btn btn-valex-primary btn-sm">
                                    <i class="bi bi-person-plus-fill me-1"></i> Register First Patient Today
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- ROW 4: RECENTLY REGISTERED PATIENTS (FULL WIDTH)                         -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">

            {{-- Recently Registered Patients - Full Width Table --}}
            <div class="col-12">
                <div class="valex-card">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>Recently Registered Patients
                            </h5>
                            <span class="badge bg-success-transparent text-success rounded-pill fw-semibold fs-12">Registry</span>
                        </div>
                        <span class="text-muted fs-12 fw-medium">All-time Records</span>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($recentPatients) && count($recentPatients) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">#</th>
                                            <th>Patient Details</th>
                                            <th>Registration ID</th>
                                            <th>Guardian</th>
                                            <th>Category</th>
                                            <th>Contact</th>
                                            <th>Registered On</th>
                                            <th class="text-end pe-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPatients as $key => $rp)
                                        @php
                                            $rpInitials = strtoupper(substr($rp->patient_name ?? 'P', 0, 2));
                                            $rpColors = ['bg-primary-transparent text-primary', 'bg-success-transparent text-success', 'bg-info-transparent text-info', 'bg-warning-transparent text-warning', 'bg-danger-transparent text-danger', 'bg-purple-transparent text-purple'];
                                            $rpColor = $rpColors[$key % count($rpColors)];
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials {{ $rpColor }}">{{ $rpInitials }}</div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $rp->patient_name ?? 'Unknown' }}</h6>
                                                        <small class="text-muted fs-11">{{ $rp->gender ?? '-' }} &bull; {{ $rp->age_year ? $rp->age_year.' Yrs' : ($rp->age_month ? $rp->age_month.' Mos' : '-') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border font-monospace fs-12">{{ $rp->patient_id ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-13 text-dark">
                                                    @if(!empty($rp->husband_father_name))
                                                        <i class="bi bi-person-heart text-muted me-1 fs-11"></i>{{ $rp->husband_father_name }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if($rp->paymentCategory)
                                                    <span class="badge bg-info-transparent text-info fw-semibold">
                                                        <i class="bi bi-tag-fill me-1"></i>{{ $rp->paymentCategory->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border">Standard</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fs-13 text-dark">
                                                    @if($rp->mobile)
                                                        <i class="bi bi-telephone text-muted me-1 fs-11"></i>{{ $rp->mobile }}
                                                    @else
                                                        <span class="text-muted fs-12">—</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">{{ $rp->created_at ? \Carbon\Carbon::parse($rp->created_at)->format('d M Y') : '-' }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('addsymptoms', $rp->id) }}" class="btn btn-sm btn-valex-primary py-1 px-2" title="Write Prescription">
                                                        <i class="bi bi-file-earmark-medical"></i> Rx
                                                    </a>
                                                    <a href="{{ route('patient.history', $rp->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-info" title="Patient History">
                                                        <i class="bi bi-clock-history"></i>
                                                    </a>
                                                    <a href="{{ route('patient.edit', $rp->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-muted" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="valex-empty-icon bg-success-transparent text-success mb-3">
                                    <i class="bi bi-person-plus fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">No Patients Registered Yet</h6>
                                <p class="text-muted fs-13 mb-3">Recently added patients will appear here.</p>
                                <a href="{{ route('Addpatient') }}" class="btn btn-valex-primary btn-sm">
                                    <i class="bi bi-person-plus-fill me-1"></i> Add First Patient
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- container-fluid -->
</div>

<!-- ========================================================================= -->
<!-- VALEX THEME CUSTOM CSS STYLING                                            -->
<!-- ========================================================================= -->
<style>
    :root {
        --valex-primary: #0162e8;
        --valex-primary-rgb: 1, 98, 232;
        --valex-secondary: #05c3fb;
        --valex-success: #22c03c;
        --valex-danger: #ee335e;
        --valex-warning: #ffc107;
        --valex-info: #05c3fb;
        --valex-purple: #6c5ffc;
        --valex-dark: #282f53;
        --valex-card-bg: #ffffff;
        --valex-border: #e9edf4;
        --valex-text-muted: #8c9097;
    }

    .valex-dashboard-wrapper {
        background-color: #f2f4f8;
        min-height: calc(100vh - 70px);
        padding-top: 20px;
        padding-bottom: 40px;
    }

    /* Page Header */
    .valex-page-title {
        font-size: 20px;
        letter-spacing: -0.3px;
        color: #282f53;
    }

    .valex-date-badge {
        background: #ffffff;
        border: 1px solid var(--valex-border);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        color: #495057;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    /* Valex Buttons */
    .btn-valex-primary {
        background-color: var(--valex-primary);
        border-color: var(--valex-primary);
        color: #ffffff;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-valex-primary:hover {
        background-color: #0152c2;
        border-color: #0152c2;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(1, 98, 232, 0.3);
    }

    .btn-valex-success {
        background-color: var(--valex-success);
        border-color: var(--valex-success);
        color: #ffffff;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-valex-success:hover {
        background-color: #1ea333;
        border-color: #1ea333;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(34, 192, 60, 0.3);
    }

    .btn-valex-light {
        background-color: #f8f9fa;
        border: 1px solid var(--valex-border);
        color: #495057;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-valex-light:hover {
        background-color: #e9ecef;
        color: #212529;
    }

    /* Valex KPI Cards */
    .valex-kpi-card {
        background: #ffffff;
        border: 1px solid var(--valex-border);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(169, 184, 200, 0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .valex-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(169, 184, 200, 0.2);
    }
    .valex-kpi-body {
        padding: 20px;
    }
    .valex-kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--valex-text-muted);
    }
    .valex-kpi-value {
        font-size: 26px;
        font-weight: 700;
        color: #282f53;
        letter-spacing: -0.5px;
    }

    /* Valex Icon Circles */
    .valex-icon-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Valex Transparent Colors */
    .bg-primary-transparent {
        background-color: rgba(1, 98, 232, 0.12) !important;
        color: #0162e8 !important;
    }
    .bg-success-transparent {
        background-color: rgba(34, 192, 60, 0.12) !important;
        color: #22c03c !important;
    }
    .bg-danger-transparent {
        background-color: rgba(238, 51, 94, 0.12) !important;
        color: #ee335e !important;
    }
    .bg-warning-transparent {
        background-color: rgba(255, 193, 7, 0.15) !important;
        color: #c99700 !important;
    }
    .bg-info-transparent {
        background-color: rgba(5, 195, 251, 0.12) !important;
        color: #05c3fb !important;
    }
    .bg-purple-transparent {
        background-color: rgba(108, 95, 252, 0.12) !important;
        color: #6c5ffc !important;
    }
    .text-purple {
        color: #6c5ffc !important;
    }

    /* Valex Standard Card */
    .valex-card {
        background: #ffffff;
        border: 1px solid var(--valex-border);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(169, 184, 200, 0.12);
        overflow: hidden;
    }
    .valex-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--valex-border);
        background: #ffffff;
    }
    .valex-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #282f53;
        letter-spacing: -0.2px;
    }
    .valex-card-body {
        padding: 20px;
    }

    /* Doctor Profile Avatar */
    .valex-doc-avatar {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .valex-avatar-status {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        border: 2px solid #ffffff;
    }

    /* Avatar Initials */
    .valex-avatar-initials {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Valex Table */
    .valex-table thead th {
        background-color: #f8f9fc;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: var(--valex-text-muted);
        border-bottom: 1px solid var(--valex-border);
        padding: 12px 14px;
    }
    .valex-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #f1f4f9;
    }
    .valex-table tbody tr:hover td {
        background-color: #f9fbff;
    }
    .valex-search-input {
        max-width: 180px;
        border-radius: 6px;
        border-color: var(--valex-border);
        font-size: 12px;
    }

    /* Valex Action Tiles (Grid Launcher) */
    .valex-action-tile {
        display: block;
        padding: 16px 12px;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .valex-action-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    /* Empty state */
    .valex-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .fs-10 { font-size: 10px !important; }
    .fs-11 { font-size: 11px !important; }
    .fs-12 { font-size: 12px !important; }
    .fs-13 { font-size: 13px !important; }
</style>

<!-- ========================================================================= -->
<!-- JAVASCRIPT & APEXCHARTS INITIALIZATION                                    -->
<!-- ========================================================================= -->
<script>
    // Data from Laravel Controller
    const weeklyDays = {!! json_encode($weeklyChartDays ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
    const weeklyData = {!! json_encode($weeklyChartData ?? [0, 0, 0, 0, 0, 0, 0]) !!};

    const monthlyLabels = {!! json_encode($monthlyChartLabels ?? ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6']) !!};
    const monthlyData = {!! json_encode($monthlyChartData ?? [0, 0, 0, 0, 0, 0]) !!};

    let patientChart;

    document.addEventListener("DOMContentLoaded", function () {
        // ApexCharts Options for Valex Style Patient Analytics Chart
        const chartOptions = {
            series: [{
                name: 'Patients Consulted',
                data: weeklyData
            }],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Poppins, sans-serif'
            },
            colors: ['#0162e8'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#0162e8', opacity: 0.4 },
                        { offset: 100, color: '#38cab3', opacity: 0.0 }
                    ]
                }
            },
            xaxis: {
                categories: weeklyDays,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#8c9097', fontSize: '11px', fontWeight: 500 }
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: {
                    style: { colors: '#8c9097', fontSize: '11px' },
                    formatter: function (val) {
                        return Math.round(val);
                    }
                }
            },
            grid: {
                borderColor: '#f1f4f9',
                strokeDashArray: 4,
                padding: { top: 0, right: 10, bottom: 0, left: 10 }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return val + " Patients";
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#0162e8'],
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: { size: 6 }
            }
        };

        const chartElement = document.querySelector("#patient-trend-apexchart");
        if (chartElement) {
            patientChart = new ApexCharts(chartElement, chartOptions);
            patientChart.render();
        }
    });

    // Chart Mode Toggle (Weekly / Monthly)
    function switchChartMode(mode) {
        if (!patientChart) return;

        const btnWeekly = document.getElementById('btn-chart-weekly');
        const btnMonthly = document.getElementById('btn-chart-monthly');

        if (mode === 'weekly') {
            btnWeekly.classList.add('active');
            btnMonthly.classList.remove('active');
            patientChart.updateOptions({
                xaxis: { categories: weeklyDays },
                series: [{ name: 'Patients Consulted', data: weeklyData }]
            });
        } else if (mode === 'monthly') {
            btnMonthly.classList.add('active');
            btnWeekly.classList.remove('active');
            patientChart.updateOptions({
                xaxis: { categories: monthlyLabels },
                series: [{ name: 'Monthly Patients', data: monthlyData }]
            });
        }
    }

    // Live filter search for Today's Patient Table
    function filterPatientTable() {
        const input = document.getElementById('patient-table-search');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('todayPatientsTable');
        if (!table) return;

        const tr = table.getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            const tdName = tr[i].getElementsByTagName('td')[1];
            const tdPhone = tr[i].getElementsByTagName('td')[2];
            if (tdName || tdPhone) {
                const nameValue = tdName ? (tdName.textContent || tdName.innerText) : '';
                const phoneValue = tdPhone ? (tdPhone.textContent || tdPhone.innerText) : '';
                if (nameValue.toLowerCase().indexOf(filter) > -1 || phoneValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection