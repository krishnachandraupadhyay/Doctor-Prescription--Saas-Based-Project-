@extends('middleend.include.layout')

@section('title', 'Onboarding Dashboard')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header / Welcome Breadcrumb -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Hi, Welcome {{ auth()->user()->name ?? 'Onboarding Member' }}!
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Onboarding Console</span> &bull; Doctor Setup, Prescription Formats &amp; Clinic Configuration Portal
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="valex-date-badge">
                    <i class="bi bi-calendar3 me-1 text-primary"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </span>
                <a href="{{ route('adddoctor.onboarding') }}" class="btn btn-valex-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Doctor
                </a>
                <a href="{{ route('/manage.doctor') }}" class="btn btn-valex-success btn-sm">
                    <i class="bi bi-people-fill me-1"></i> Manage Doctors
                </a>
                <a href="{{ route('payment.method') }}" class="btn btn-valex-light btn-sm" title="Payment Categories">
                    <i class="bi bi-credit-card-2-front-fill me-1"></i> Payment Methods
                </a>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- ROW 1: 6-CARD KPI STATISTIC GRID                                          -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">

            {{-- 1. Total Doctors --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TOTAL DOCTORS</span>
                            <div class="valex-icon-circle bg-primary-transparent text-primary">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($totalDoctors ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-primary-transparent text-primary fs-11">Registered</span>
                            <a href="{{ route('/manage.doctor') }}" class="text-primary fs-11 fw-medium text-decoration-none">
                                View <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Active Doctors --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">ACTIVE DOCTORS</span>
                            <div class="valex-icon-circle bg-success-transparent text-success">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($activeDoctors ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-success-transparent text-success fs-11">Verified Active</span>
                            <a href="{{ route('/manage.doctor') }}" class="text-success fs-11 fw-medium text-decoration-none">
                                Manage <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Total Patients --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TOTAL PATIENTS</span>
                            <div class="valex-icon-circle bg-info-transparent text-info">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($totalPatients ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-info-transparent text-info fs-11">All Records</span>
                            <a href="#today-patients-section" class="text-info fs-11 fw-medium text-decoration-none">
                                Details <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Today's Patients --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">TODAY'S PATIENTS</span>
                            <div class="valex-icon-circle bg-danger-transparent text-danger">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($todayPatients ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-danger-transparent text-danger fs-11">New Today</span>
                            <a href="#today-patients-section" class="text-danger fs-11 fw-medium text-decoration-none">
                                Queue <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Total Medicines --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">MEDICINES</span>
                            <div class="valex-icon-circle bg-purple-transparent text-purple">
                                <i class="bi bi-capsule-pill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format($totalMedicines ?? 0) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-purple-transparent text-purple fs-11">Formulary</span>
                            <a href="{{ route('adddoctor.onboarding') }}" class="text-purple fs-11 fw-medium text-decoration-none">
                                Catalog <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Prescriptions Configured --}}
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="valex-kpi-card h-100">
                    <div class="valex-kpi-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="valex-kpi-label">PRESCRIPTIONS</span>
                            <div class="valex-icon-circle bg-warning-transparent text-warning">
                                <i class="bi bi-file-earmark-medical-fill"></i>
                            </div>
                        </div>
                        <h3 class="valex-kpi-value mb-1">{{ number_format(($fixedCount ?? 0) + ($customizeCount ?? 0)) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                            <span class="badge bg-warning-transparent text-warning fs-11">{{ $fixedCount ?? 0 }} Fixed / {{ $customizeCount ?? 0 }} Cust</span>
                            <a href="{{ route('prescription.type') }}" class="text-warning fs-11 fw-medium text-decoration-none">
                                Layouts <i class="bi bi-chevron-right fs-10"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- ROW 2: ANALYTICS APEXCHART & QUICK LAUNCHER GRID                          -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">

            {{-- Growth Analytics Spline Area ApexChart --}}
            <div class="col-xxl-8 col-xl-8 col-lg-7">
                <div class="valex-card h-100">
                    <div class="valex-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Onboarding Activity &amp; Growth Analytics
                            </h5>
                            <p class="text-muted fs-12 mb-0">Doctor onboarding trend and patient consultations overview</p>
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Chart Filter">
                            <button type="button" class="btn btn-outline-primary active" id="btn-chart-weekly" onclick="switchOnboardingChartMode('weekly')">Last 7 Days</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-chart-monthly" onclick="switchOnboardingChartMode('monthly')">Monthly Trend</button>
                        </div>
                    </div>
                    <div class="valex-card-body">
                        <!-- ApexCharts Container -->
                        <div id="onboarding-analytics-apexchart" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Launcher Grid (6-Tile Shortcut Box) --}}
            <div class="col-xxl-4 col-xl-4 col-lg-5">
                <div class="valex-card h-100">
                    <div class="valex-card-header">
                        <h5 class="valex-card-title mb-0">
                            <i class="bi bi-grid-fill me-2 text-primary"></i>Onboarding Shortcuts
                        </h5>
                    </div>
                    <div class="valex-card-body">
                        <div class="row g-2.5">
                            <div class="col-6">
                                <a href="{{ route('adddoctor.onboarding') }}" class="valex-action-tile bg-primary-transparent">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span class="tile-title">Add Doctor</span>
                                    <small class="tile-subtitle">New Doctor Form</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('/manage.doctor') }}" class="valex-action-tile bg-success-transparent">
                                    <i class="bi bi-people-fill"></i>
                                    <span class="tile-title">Manage Doctors</span>
                                    <small class="tile-subtitle">Doctor Directory</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('prescription.type') }}" class="valex-action-tile bg-purple-transparent">
                                    <i class="bi bi-file-earmark-medical-fill"></i>
                                    <span class="tile-title">Prescription Type</span>
                                    <small class="tile-subtitle">Layout Format</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('/prescrpt_header_footer') }}" class="valex-action-tile bg-info-transparent">
                                    <i class="bi bi-card-heading"></i>
                                    <span class="tile-title">Header / Footer</span>
                                    <small class="tile-subtitle">Doctor Branding</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('payment.method') }}" class="valex-action-tile bg-warning-transparent">
                                    <i class="bi bi-credit-card-2-front-fill"></i>
                                    <span class="tile-title">Payment Methods</span>
                                    <small class="tile-subtitle">Category Setup</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('/manage.doctor') }}" class="valex-action-tile bg-danger-transparent">
                                    <i class="bi bi-archive-fill"></i>
                                    <span class="tile-title">Doctor List</span>
                                    <small class="tile-subtitle">Manage &amp; Assign</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- ROW 3: RECENT DOCTORS TABLE & TODAY'S PATIENT REGISTRATIONS (FULL WIDTH)  -->
        <!-- ========================================================================= -->
        <div class="row g-4" id="today-patients-section">

            {{-- Recent Doctors List (Full Screen Width) --}}
            <div class="col-12">
                <div class="valex-card mb-0 shadow-sm">
                    <div class="valex-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Recently Registered Doctors
                            </h5>
                            <span class="badge bg-primary-transparent text-primary rounded-pill fw-semibold fs-11">
                                {{ isset($recentDoctors) ? count($recentDoctors) : 0 }} Recent
                            </span>
                        </div>
                        <a href="{{ route('/manage.doctor') }}" class="btn btn-valex-light btn-sm">
                            View All Doctors <i class="bi bi-arrow-right fs-10 ms-1"></i>
                        </a>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($recentDoctors) && count($recentDoctors) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Doctor Details</th>
                                            <th>Clinic Name</th>
                                            <th>Phone</th>
                                            <th class="text-end pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentDoctors as $key => $doc)
                                        @php
                                            $docInitials = strtoupper(substr($doc->name ?? 'Dr', 0, 2));
                                            $docActive = ($doc->status ?? 0) == 1;
                                            $docVerified = ($doc->verified ?? 0) == 1;
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="valex-avatar-initials bg-primary-transparent text-primary">
                                                        {{ $docInitials }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">Dr. {{ $doc->name ?? 'Doctor' }}</h6>
                                                        <small class="text-muted fs-11">{{ $doc->specialization ?? 'General Physician' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fs-13 text-dark">{{ $doc->clinic_name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $doc->phone ?? '-' }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('onboarding.view.doctor', $doc->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-primary" title="View Doctor">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.onboarding.edit', $doc->id) }}" class="btn btn-sm btn-valex-light py-1 px-2 text-muted" title="Edit Doctor">
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
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-person-x fs-2 d-block mb-1"></i>
                                No doctors registered yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Today's Patient Registrations (Full Screen Width) --}}
            <div class="col-12">
                <div class="valex-card mb-0 shadow-sm">
                    <div class="valex-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="valex-card-title mb-0">
                                <i class="bi bi-people-fill me-2 text-primary"></i>Today's Patient Registrations
                            </h5>
                            <span class="badge bg-danger-transparent text-danger rounded-pill fw-semibold fs-11">
                                {{ isset($todayPatientList) ? count($todayPatientList) : 0 }} Today
                            </span>
                        </div>
                        <a href="{{ route('/manage.doctor') }}" class="btn btn-valex-light btn-sm">
                            Manage Queue <i class="bi bi-arrow-right fs-10 ms-1"></i>
                        </a>
                    </div>
                    <div class="valex-card-body p-0">
                        @if(isset($todayPatientList) && count($todayPatientList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 valex-table">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Patient Name</th>
                                            <th>Doctor Assigned</th>
                                            <th>Contact</th>
                                            <th>Time</th>
                                            <th class="text-end pe-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayPatientList as $key => $patient)
                                        @php
                                            $pInitials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                            $pCompleted = strtolower($patient->status ?? '') == 'completed';
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
                                                <span class="fs-12 text-muted"><i class="bi bi-telephone me-1"></i>{{ $patient->mobile ?? $patient->phone ?? '-' }}</span>
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
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-2 d-block mb-1"></i>
                                No patients registered today yet.
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
<!-- JAVASCRIPT & APEXCHARTS INITIALIZATION                                    -->
<!-- ========================================================================= -->
<script>
    // Data from Laravel Controller
    const onboardingWeeklyDays = {!! json_encode($weeklyChartDays ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
    const onboardingWeeklyDoctorData = {!! json_encode($weeklyDoctorData ?? [0, 0, 0, 0, 0, 0, 0]) !!};
    const onboardingWeeklyPatientData = {!! json_encode($weeklyPatientData ?? [0, 0, 0, 0, 0, 0, 0]) !!};

    const onboardingMonthlyLabels = {!! json_encode($monthlyChartLabels ?? ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6']) !!};
    const onboardingMonthlyDoctorData = {!! json_encode($monthlyDoctorData ?? [0, 0, 0, 0, 0, 0]) !!};
    const onboardingMonthlyPatientData = {!! json_encode($monthlyPatientData ?? [0, 0, 0, 0, 0, 0]) !!};

    let onboardingAnalyticsChart;

    document.addEventListener("DOMContentLoaded", function () {
        const chartOptions = {
            series: [
                {
                    name: 'Doctors Onboarded',
                    data: onboardingWeeklyDoctorData
                },
                {
                    name: 'Patients Consulted',
                    data: onboardingWeeklyPatientData
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
                categories: onboardingWeeklyDays,
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

        const chartElement = document.querySelector("#onboarding-analytics-apexchart");
        if (chartElement) {
            onboardingAnalyticsChart = new ApexCharts(chartElement, chartOptions);
            onboardingAnalyticsChart.render();
        }
    });

    function switchOnboardingChartMode(mode) {
        if (!onboardingAnalyticsChart) return;

        const btnWeekly = document.getElementById('btn-chart-weekly');
        const btnMonthly = document.getElementById('btn-chart-monthly');

        if (mode === 'weekly') {
            btnWeekly.classList.add('active');
            btnMonthly.classList.remove('active');
            onboardingAnalyticsChart.updateOptions({
                xaxis: { categories: onboardingWeeklyDays },
                series: [
                    { name: 'Doctors Onboarded', data: onboardingWeeklyDoctorData },
                    { name: 'Patients Consulted', data: onboardingWeeklyPatientData }
                ]
            });
        } else if (mode === 'monthly') {
            btnMonthly.classList.add('active');
            btnWeekly.classList.remove('active');
            onboardingAnalyticsChart.updateOptions({
                xaxis: { categories: onboardingMonthlyLabels },
                series: [
                    { name: 'Doctors Onboarded', data: onboardingMonthlyDoctorData },
                    { name: 'Patients Consulted', data: onboardingMonthlyPatientData }
                ]
            });
        }
    }
</script>
@endsection