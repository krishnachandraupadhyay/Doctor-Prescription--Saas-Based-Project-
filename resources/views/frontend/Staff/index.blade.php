@extends('backend.include.layout')

@section('title', 'Staff Dashboard')

@section('content')
<style>
    .valex-search-box {
        position: relative !important;
        width: 220px !important;
    }
    .valex-search-box .search-icon {
        position: absolute !important;
        left: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #94a3b8 !important;
        font-size: 13px !important;
        pointer-events: none !important;
        z-index: 5 !important;
    }
    .valex-search-box input.form-control {
        padding-left: 34px !important;
        height: 34px !important;
        font-size: 12.5px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        transition: all 0.2s ease !important;
    }
    .valex-search-box input.form-control:focus {
        border-color: #0162e8 !important;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12) !important;
    }
    .card .card-header{
        display:flex;
        justify-content:space-between;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Welcome Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Welcome, {{ $member->name ?? 'Staff Member' }}! 👋
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-success fw-medium">Clinic Staff Desk</span> &bull; Daily Patient Care Coordination &amp; Clinic Support
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="valex-date-badge">
                    <i class="bi bi-calendar3 me-1 text-success"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 4 Valex KPI Metric Cards -->
        <div class="row g-3 mb-4">
            
            {{-- 1. Today's Clinic Queue --}}
            <div class="col-xxl-3 col-xl-3 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TODAY'S CLINIC PATIENTS</span>
                            <div class="valex-icon-circle bg-danger-transparent text-danger">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($todayPatientsCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-danger-transparent text-danger fs-11">Clinic Queue</span>
                            <small class="text-muted fs-11">Active today</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Total Records --}}
            <div class="col-xxl-3 col-xl-3 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TOTAL PATIENTS DATABASE</span>
                            <div class="valex-icon-circle bg-primary-transparent text-primary">
                                <i class="bi bi-database-fill-check"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($totalPatientsCount ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-primary-transparent text-primary fs-11">All Records</span>
                            <small class="text-muted fs-11">Lifetime</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Active Shift --}}
            <div class="col-xxl-3 col-xl-3 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">STAFF STATUS</span>
                            <div class="valex-icon-circle bg-success-transparent text-success">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1 text-success fs-20">On Duty</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-success-transparent text-success fs-11">Active Shift</span>
                            <small class="text-muted fs-11">Logged In</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Role Info --}}
            <div class="col-xxl-3 col-xl-3 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">ASSIGNED ROLE</span>
                            <div class="valex-icon-circle bg-warning-transparent text-warning">
                                <i class="bi bi-person-gear"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1 fs-20">Clinic Staff</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-warning-transparent text-warning fs-11">{{ $member->member_id ?? 'STAFF' }}</span>
                            <small class="text-muted fs-11">Support</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Queue Table & Duty Assistance Cards -->
        <div class="row g-3" id="today-queue-section">
            
            {{-- Today's Queue for Staff Assistance --}}
            <div class="col-12">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-people-fill me-2 text-success"></i>Today's Clinic Patient Queue
                            </h5>
                            <span class="badge bg-success-transparent text-success rounded-pill fw-semibold fs-11">
                                {{ isset($todayPatientsList) ? count($todayPatientsList) : 0 }} Patients
                            </span>
                        </div>
                        <div class="valex-search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" id="staff-queue-search" class="form-control form-control-sm" placeholder="Search queue...">
                        </div>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($todayPatientsList) && count($todayPatientsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table" id="staff-queue-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Patient Name</th>
                                            <th>Registration No.</th>
                                            <th>Contact</th>
                                            <th>Time</th>
                                            <th class="text-end pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayPatientsList as $key => $patient)
                                        @php
                                            $initials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                            $isCompleted = $patient->is_completed;
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials bg-success-transparent text-success">
                                                        {{ $initials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $patient->patient_name ?? 'Patient' }}</h6>
                                                        <small class="text-muted fs-11">
                                                            {{ $patient->gender ?? '-' }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : '-' }}
                                                            @if($patient->paymentCategory)
                                                                &bull; <span class="badge bg-primary-transparent text-primary fs-10 px-1.5 py-0.5 rounded">{{ $patient->paymentCategory->name }}</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border font-monospace fs-11">
                                                    {{ $patient->registration ?? $patient->patient_id ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $patient->mobile ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-11 text-muted">
                                                    {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('h:i A') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($patient->can_staff_add_vitals_today)
                                                    <a href="{{ route('staff.physical_exam', $patient->id) }}"
                                                       class="btn btn-sm btn-primary px-3 py-1 fw-medium shadow-xs"
                                                       style="border-radius:7px; font-size:12.5px;"
                                                       title="Add Physical Examination">
                                                        <i class="bi bi-plus-circle me-1"></i> Add Exam
                                                    </a>
                                                @else
                                                    <a href="{{ route('staff.physical_exam', $patient->id) }}"
                                                       class="btn btn-sm btn-info text-white px-3 py-1 fw-medium shadow-xs"
                                                       style="border-radius:7px; font-size:12.5px;"
                                                       title="View Physical Examination (Read-only)">
                                                        <i class="bi bi-eye me-1"></i> View Exam
                                                    </a>
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
                                No patients in queue today yet.
                            </div>
                        @endif
                    </div>
                
                </div>
            
            </div>

        </div><br>
        <!-- Total Patients Table for Staff -->
        <div class="row g-3 mt-2" id="total-patients-section">
            <div class="col-12">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0 fw-bold">
                                <i class="bi bi-database-fill me-2 text-primary"></i>Total Patients of Dr. {{ $doctor->name ?? $member->created_by ?? 'Doctor' }}
                            </h5>
                            <span class="badge bg-primary-transparent text-primary rounded-pill fw-semibold fs-11">
                                Top 5 of {{ number_format($totalPatientsCount ?? 0) }} Total
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="valex-search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="total-patients-search" class="form-control form-control-sm" placeholder="Search recent 5...">
                            </div>
                            <a href="{{ route('staff.all_patients') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                                <i class="bi bi-eye me-1"></i> View All Patients
                            </a>
                        </div>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($totalPatientsList) && count($totalPatientsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table" id="total-patients-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-4" style="width:50px;">#</th>
                                            <th>Patient Name</th>
                                            <th>Registration No.</th>
                                            <th>Contact</th>
                                            <th>Reg. Date &amp; Time</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($totalPatientsList as $key => $tpatient)
                                        @php
                                            $initials = strtoupper(substr($tpatient->patient_name ?? 'P', 0, 2));
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold text-muted fs-12">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials bg-primary-transparent text-primary">
                                                        {{ $initials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $tpatient->patient_name ?? 'Patient' }}</h6>
                                                        <small class="text-muted fs-11">
                                                            {{ $tpatient->gender ?? '-' }} &bull; {{ $tpatient->age_year ? $tpatient->age_year.' Yrs' : '-' }}
                                                            @if($tpatient->paymentCategory)
                                                                &bull; <span class="badge bg-primary-transparent text-primary fs-10 px-1.5 py-0.5 rounded">{{ $tpatient->paymentCategory->name }}</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border font-monospace fs-11">
                                                    {{ $tpatient->registration ?? $tpatient->patient_id ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $tpatient->mobile ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-11 text-muted">
                                                    {{ $tpatient->created_at ? \Carbon\Carbon::parse($tpatient->created_at)->format('d M Y, h:i A') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                @if($tpatient->can_staff_add_vitals_today)
                                                    <a href="{{ route('staff.physical_exam', $tpatient->id) }}"
                                                       class="btn btn-sm btn-outline-primary px-3 py-1 fw-medium"
                                                       style="border-radius:7px; font-size:12.5px;"
                                                       title="Perform Physical Examination for Today">
                                                        <i class="bi bi-stethoscope me-1"></i> Physical Exam
                                                    </a>
                                                @else
                                                    <a href="{{ route('staff.physical_exam', $tpatient->id) }}"
                                                       class="btn btn-sm btn-info text-white px-3 py-1 fw-medium shadow-xs"
                                                       style="border-radius:7px; font-size:12.5px;"
                                                       title="View Physical Examination (Read-only)">
                                                        <i class="bi bi-eye me-1"></i> View Exam
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light text-center border-top">
                                <span class="text-muted fs-12 me-2">Showing latest 5 patients of Dr. {{ $doctor->name ?? $member->created_by ?? 'Doctor' }}.</span>
                                <a href="{{ route('staff.all_patients') }}" class="fw-semibold text-primary text-decoration-none">
                                    Click here to view full patient database &amp; search <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-folder-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                No total patients found for this doctor yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript Search filter -->
<script>
    document.getElementById('staff-queue-search')?.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#staff-queue-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('total-patients-search')?.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#total-patients-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
