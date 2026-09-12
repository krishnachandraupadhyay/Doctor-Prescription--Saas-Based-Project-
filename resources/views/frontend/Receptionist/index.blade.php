@extends('backend.include.layout')

@section('title', 'Receptionist Dashboard')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Valex Page Header / Welcome Breadcrumb -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Hi, Welcome {{ $member->name ?? 'Receptionist' }}!
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Receptionist Desk Console</span>
                    &bull; Hospital &amp; Doctor Prescription Management Portal
                    @if($doctor ?? null)
                        &bull; <span class="fw-semibold text-dark">Dr. {{ $doctor->name }}</span>
                    @endif
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="valex-date-badge px-3 py-2 border rounded-3 bg-white shadow-xs fs-13 fw-medium text-dark">
                    <i class="bi bi-calendar3 me-1.5 text-primary"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </span>
                <a href="{{ route('receptionist.patients') }}" class="btn btn-primary rounded-3 px-3.5 py-2 fs-13 fw-bold shadow-xs ms-1">
                    <i class="bi bi-person-plus-fill me-1.5"></i> Register Patient
                </a>
                <a href="{{ route('receptionist.all_patients') }}" class="btn btn-success rounded-3 px-3.5 py-2 fs-13 fw-bold text-white shadow-xs ms-1">
                    <i class="bi bi-people-fill me-1.5"></i> All Patients
                </a>
                <a href="#today-queue-section" class="btn btn-outline-primary rounded-3 px-3.5 py-2 fs-13 fw-semibold shadow-xs ms-1">
                    <i class="bi bi-list-ul me-1.5"></i> View Queue
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 6-CARD VALEX KPI STATISTIC GRID (Exact Admin Design) -->
        <div class="row g-3 mb-4">

            {{-- 1. Today's Registrations --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TODAY'S PATIENTS</span>
                            <div class="valex-icon-circle bg-danger-transparent text-danger">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($todayPatientsCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-danger-transparent text-danger fs-11">Registered Today</span>
                            <a href="#today-queue-section" class="text-danger fs-11 fw-medium text-decoration-none">
                                Queue <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Waiting in Queue --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">WAITING QUEUE</span>
                            <div class="valex-icon-circle bg-warning-transparent text-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($waitingTodayCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-warning-transparent text-warning fs-11">In Waiting Lounge</span>
                            <span class="text-warning fs-11 fw-medium">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Completed Consultations --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">CONSULTED TODAY</span>
                            <div class="valex-icon-circle bg-success-transparent text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($completedTodayCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-success-transparent text-success fs-11">Prescribed</span>
                            <span class="text-success fs-11 fw-medium">Done</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. This Month's Patients --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">THIS MONTH</span>
                            <div class="valex-icon-circle bg-info-transparent text-info">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($thisMonthPatients ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-info-transparent text-info fs-11">Monthly Volume</span>
                            <span class="text-info fs-11 fw-medium">{{ date('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Total Patient Database --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TOTAL PATIENTS</span>
                            <div class="valex-icon-circle bg-primary-transparent text-primary">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($totalPatientsCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-primary-transparent text-primary fs-11">All Records</span>
                            <span class="text-primary fs-11 fw-medium">Lifetime</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Desk Status --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">DESK STATUS</span>
                            <div class="valex-icon-circle bg-purple-transparent text-purple">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1 fs-20 text-purple">Online</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-purple-transparent text-purple fs-11">{{ $member->member_id ?? 'REC-001' }}</span>
                            <span class="text-purple fs-11 fw-medium">Active</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ANALYTICS APEXCHART & QUICK SHORTCUTS (Exact Admin Layout) -->
        <div class="row g-3 mb-4">

            {{-- Spline Area ApexChart --}}
            <div class="col-xxl-8 col-xl-8 col-lg-7">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Patient Registrations &amp; Flow Analytics
                            </h5>
                            <p class="text-muted fs-12 mb-0">Daily patient arrivals and consultation trends</p>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="btn-rec-weekly" onclick="switchRecChartMode('weekly')">Last 7 Days</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-rec-monthly" onclick="switchRecChartMode('monthly')">Monthly Trend</button>
                        </div>
                    </div>
                    <div class="valex-card-body">
                        <div id="receptionist-analytics-apexchart" style="min-height: 280px;"></div>

                        <!-- Underneath Mini Stats -->
                        <div class="row g-2 pt-3 border-top border-light-subtle mt-2 text-center">
                            <div class="col-4 border-end">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">This Month</p>
                                <h6 class="mb-0 fw-bold text-dark">{{ $thisMonthPatients ?? 0 }} <span class="text-muted fs-11 fw-normal">Patients</span></h6>
                            </div>
                            <div class="col-4 border-end">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">Waiting Today</p>
                                <h6 class="mb-0 fw-bold text-warning">{{ $waitingTodayCount ?? 0 }} <span class="text-muted fs-11 fw-normal">In Queue</span></h6>
                            </div>
                            <div class="col-4">
                                <p class="text-muted fs-11 mb-1 text-uppercase fw-semibold">Completed Today</p>
                                <h6 class="mb-0 fw-bold text-success">{{ $completedTodayCount ?? 0 }} <span class="text-muted fs-11 fw-normal">Prescribed</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Launcher Grid (6-Tile Premium Design) --}}
            <div class="col-xxl-4 col-xl-4 col-lg-5">
                <div class="valex-card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="valex-card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-transparent text-primary rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                <i class="bi bi-grid-fill fs-14"></i>
                            </div>
                            <h5 class="valex-card-title mb-0 fw-bold fs-15 text-dark">
                                Receptionist Shortcuts
                            </h5>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                            6 Quick Actions
                        </span>
                    </div>
                    <div class="valex-card-body p-3.5">
                        <div class="row gy-3.5 gx-3">
                            {{-- 1. Register Patient --}}
                            <div class="col-6">
                                <a href="{{ route('receptionist.patients') }}" class="shortcut-tile-card st-primary">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">Register Patient</span>
                                        <small class="shortcut-tile-sub d-block text-muted">New Patient Entry</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>

                            {{-- 2. Today's Queue --}}
                            <div class="col-6">
                                <a href="#today-queue-section" class="shortcut-tile-card st-danger">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">Today's Queue</span>
                                        <small class="shortcut-tile-sub d-block">{{ $todayPatientsCount ?? 0 }} Registered Today</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>

                            {{-- 3. All Patients --}}
                            <div class="col-6">
                                <a href="{{ route('receptionist.all_patients') }}" class="shortcut-tile-card st-success">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">All Patients</span>
                                        <small class="shortcut-tile-sub d-block text-muted">Search Database</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>

                            {{-- 4. Recent Patients --}}
                            <div class="col-6">
                                <a href="#recent-patients-section" class="shortcut-tile-card st-info">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">Recent Patients</span>
                                        <small class="shortcut-tile-sub d-block text-muted">Latest 5 Records</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>

                            {{-- 5. Print Queue --}}
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="window.print()" class="shortcut-tile-card st-purple">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-printer-fill"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">Print Queue</span>
                                        <small class="shortcut-tile-sub d-block text-muted">Daily Queue List</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>

                            {{-- 6. Exit Desk --}}
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="document.getElementById('sidebar-member-logout-form').submit();" class="shortcut-tile-card st-warning">
                                    <div class="shortcut-icon-wrapper">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </div>
                                    <div>
                                        <span class="shortcut-tile-title d-block">Exit Desk</span>
                                        <small class="shortcut-tile-sub d-block text-muted">Sign Out Console</small>
                                    </div>
                                    <i class="bi bi-chevron-right shortcut-arrow"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TWO FULL-WIDTH DATA TABLES -->
        <div class="row g-3 mb-4" id="today-queue-section">

            {{-- Today's Patient Queue - Full Width --}}
            <div class="col-12">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-hourglass-split me-2 text-primary"></i>Today's Patient Queue
                            </h5>
                            <span class="badge bg-danger-transparent text-danger rounded-pill fw-semibold fs-11">
                                {{ isset($todayPatientsList) ? count($todayPatientsList) : 0 }} Today
                            </span>
                        </div>
                        <a href="{{ route('receptionist.patients') }}" class="btn btn-valex-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add Patient
                        </a>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($todayPatientsList) && count($todayPatientsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Patient Info</th>
                                            <th>Doctor</th>
                                            <th>Category</th>
                                            <th>Contact</th>
                                            <th>Time</th>
                                            <th class="text-end pe-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayPatientsList as $patient)
                                        @php
                                            $pInitials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                            $pCompleted = $patient->is_completed;
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials bg-info-transparent text-info">
                                                        {{ $pInitials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $patient->patient_name ?? 'Patient' }}</h6>
                                                        <small class="text-muted fs-11">{{ $patient->gender ?? '-' }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fs-12 text-primary fw-medium">
                                                    {{ $patient->doctor ? 'Dr. '.$patient->doctor->name : 'Clinic Doctor' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($patient->paymentCategory)
                                                    <span class="badge bg-primary-transparent text-primary fw-semibold fs-11 px-2 py-1 rounded-pill">
                                                        <i class="bi bi-wallet2 me-1"></i>{{ $patient->paymentCategory->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border fs-11 px-2 py-0.5">Standard</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $patient->mobile ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fs-11">
                                                    {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('h:i A') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($pCompleted)
                                                    <span class="badge bg-success-transparent text-success fw-semibold fs-11">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Completed
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-transparent text-warning fw-semibold fs-11">
                                                        <i class="bi bi-hourglass-split me-1"></i>Waiting
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                No patients registered today yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Registered Patients - Full Width -->
        <div class="row g-3 mb-4">

            {{-- Right Table: Recent Patients Registry --}}
            <div class="col-12" id="recent-patients-section">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>Recently Registered Patients
                            </h5>
                            <span class="badge bg-primary-transparent text-primary rounded-pill fw-semibold fs-11">
                                Top 5 Patients
                            </span>
                        </div>
                        <a href="{{ route('receptionist.all_patients') }}" class="btn btn-sm btn-valex-primary rounded-pill px-3 fw-semibold">
                            <i class="bi bi-eye me-1"></i> View All Patients
                        </a>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($recentPatients) && count($recentPatients) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Patient Details</th>
                                            <th>Registration ID</th>
                                            <th>Category</th>
                                            <th>Contact</th>
                                            <th>Date</th>
                                            <th class="text-end pe-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPatients as $p)
                                        @php
                                            $rInitials = strtoupper(substr($p->patient_name ?? 'P', 0, 2));
                                            $rCompleted = $p->is_completed;
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials bg-primary-transparent text-primary">
                                                        {{ $rInitials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $p->patient_name ?? 'Patient' }}</h6>
                                                        <small class="text-muted fs-11">{{ $p->gender ?? '-' }} &bull; {{ $p->age_year ? $p->age_year.' Yrs' : '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="member-id-badge">
                                                    {{ $p->registration ?? $p->patient_id ?? 'PAT-'.$p->id }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($p->paymentCategory)
                                                    <span class="badge bg-info-transparent text-info fw-semibold fs-11 px-2 py-1 rounded-pill">
                                                        <i class="bi bi-tag-fill me-1"></i>{{ $p->paymentCategory->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border fs-11 px-2 py-0.5">Standard</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $p->mobile ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-11 text-muted">
                                                    {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($rCompleted)
                                                    <span class="badge bg-success-transparent text-success fw-semibold fs-11">Completed</span>
                                                @else
                                                    <span class="badge bg-warning-transparent text-warning fw-semibold fs-11">Waiting</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light text-center border-top">
                                <span class="text-muted fs-12 me-2">Showing latest 5 patients of Dr. {{ $doctor->name ?? $member->created_by ?? 'Doctor' }}.</span>
                                <a href="{{ route('receptionist.all_patients') }}" class="fw-semibold text-primary text-decoration-none">
                                    Click here to view full patient database &amp; search <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-people fs-2 d-block mb-2 text-muted opacity-50"></i>
                                No patients found in database.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .shortcut-tile-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        border-radius: 14px;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid transparent;
        height: 100%;
    }
    .shortcut-tile-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .shortcut-icon-wrapper {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
        transition: transform 0.2s ease;
    }
    .shortcut-tile-card:hover .shortcut-icon-wrapper {
        transform: scale(1.08);
    }
    .shortcut-tile-title {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 2px;
    }
    .shortcut-tile-sub {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.85;
    }
    .shortcut-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        opacity: 0;
        transition: all 0.2s ease;
    }
    .shortcut-tile-card:hover .shortcut-arrow {
        opacity: 0.7;
        right: 8px;
    }

    /* Vibrant Themes */
    .st-primary {
        background: #edf5ff;
        border-color: #d0e4ff;
        color: #0150bf;
    }
    .st-primary .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #0162e8, #014bb8);
        color: #ffffff;
    }

    .st-danger {
        background: #fff0f3;
        border-color: #ffd0d8;
        color: #d61f47;
    }
    .st-danger .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #ee335e, #c71b44);
        color: #ffffff;
    }

    .st-success {
        background: #ebfaf0;
        border-color: #c3f2d2;
        color: #168a2b;
    }
    .st-success .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #22c03c, #19942e);
        color: #ffffff;
    }

    .st-info {
        background: #e8f9fd;
        border-color: #b8effb;
        color: #037c9e;
    }
    .st-info .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #05c3fb, #0396c2);
        color: #ffffff;
    }

    .st-purple {
        background: #f4f2ff;
        border-color: #d9d4fe;
        color: #5648e5;
    }
    .st-purple .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #6c5ffc, #4e40e0);
        color: #ffffff;
    }

    .st-warning {
        background: #fffbf0;
        border-color: #ffe9b3;
        color: #b38400;
    }
    .st-warning .shortcut-icon-wrapper {
        background: linear-gradient(135deg, #f5b731, #d49813);
        color: #ffffff;
    }
</style>

<!-- JAVASCRIPT & APEXCHARTS INITIALIZATION -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const recWeeklyDays = {!! json_encode($weeklyChartDays ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
    const recWeeklyPatientData = {!! json_encode($weeklyPatientData ?? [0, 0, 0, 0, 0, 0, 0]) !!};
    const recWeeklyCompletedData = {!! json_encode($weeklyCompletedData ?? [0, 0, 0, 0, 0, 0, 0]) !!};

    const recMonthlyLabels = {!! json_encode($monthlyChartLabels ?? ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6']) !!};
    const recMonthlyPatientData = {!! json_encode($monthlyPatientData ?? [0, 0, 0, 0, 0, 0]) !!};

    let recAnalyticsChart;

    document.addEventListener("DOMContentLoaded", function () {
        const chartOptions = {
            series: [
                {
                    name: 'Total Patient Visits',
                    data: recWeeklyPatientData
                },
                {
                    name: 'Completed Consultations',
                    data: recWeeklyCompletedData
                }
            ],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Poppins, sans-serif'
            },
            colors: ['#0162e8', '#38cab3'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: [3, 3]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: recWeeklyDays,
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
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '12px',
                markers: { radius: 12 }
            },
            tooltip: {
                theme: 'light'
            }
        };

        const chartElement = document.querySelector("#receptionist-analytics-apexchart");
        if (chartElement) {
            recAnalyticsChart = new ApexCharts(chartElement, chartOptions);
            recAnalyticsChart.render();
        }
    });

    function switchRecChartMode(mode) {
        if (!recAnalyticsChart) return;

        const btnWeekly = document.getElementById('btn-rec-weekly');
        const btnMonthly = document.getElementById('btn-rec-monthly');

        if (mode === 'weekly') {
            btnWeekly.classList.add('active');
            btnMonthly.classList.remove('active');
            recAnalyticsChart.updateOptions({
                xaxis: { categories: recWeeklyDays },
                series: [
                    { name: 'Total Patient Visits', data: recWeeklyPatientData },
                    { name: 'Completed Consultations', data: recWeeklyCompletedData }
                ]
            });
        } else if (mode === 'monthly') {
            btnMonthly.classList.add('active');
            btnWeekly.classList.remove('active');
            recAnalyticsChart.updateOptions({
                xaxis: { categories: recMonthlyLabels },
                series: [
                    { name: 'Total Patient Visits', data: recMonthlyPatientData },
                    { name: 'Completed Consultations', data: recMonthlyPatientData }
                ]
            });
        }
    }
</script>
@endsection
