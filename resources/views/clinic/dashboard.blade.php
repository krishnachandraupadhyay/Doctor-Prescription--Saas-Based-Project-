@extends('clinic.include.layout')
@section('title', 'Clinic Dashboard - MediPortal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Welcome Header Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    {{-- Left: Clinic Icon + Details --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px; font-size: 24px;">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18 lh-1">{{ $clinic->name }}</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->clinic_id }}
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                </span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center text-muted fs-13 gap-2 mt-1">
                                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $clinic->address }}</span>
                                <span class="text-muted opacity-50">&bull;</span>
                                <span><i class="bi bi-telephone-fill text-success me-1"></i>{{ $clinic->phone }}</span>
                                <span class="text-muted opacity-50">&bull;</span>
                                <span class="text-primary fw-medium"><i class="bi bi-shield-check me-1"></i>Clinic Console</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Date + Action Buttons --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto ms-lg-0">
                        <div class="px-3 py-2 bg-light border rounded-3 text-dark fs-12 fw-medium d-none d-sm-flex align-items-center gap-1.5">
                            <i class="bi bi-calendar3 text-primary"></i>
                            <span>{{ \Carbon\Carbon::now()->format('D, d M Y') }}</span>
                        </div>
                        <a href="{{ route('clinic.prescription_design') }}" class="btn btn-light border px-3 py-2 fs-13 fw-semibold rounded-3 text-primary d-flex align-items-center gap-1.5 shadow-xs">
                            <i class="bi bi-file-earmark-medical-fill text-primary"></i>
                            <span>Prescription Design</span>
                        </a>
                        <a href="{{ route('clinic.doctors') }}" class="btn btn-primary px-3.5 py-2 fs-13 fw-semibold rounded-3 shadow-sm d-flex align-items-center gap-1.5">
                            <i class="bi bi-person-badge"></i>
                            <span>View Doctors</span>
                        </a>
                        <a href="{{ route('clinic.profile') }}" class="btn btn-light border px-3 py-2 fs-13 fw-semibold rounded-3 text-secondary d-flex align-items-center gap-1.5">
                            <i class="bi bi-gear text-muted"></i>
                            <span>Settings</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Metric Cards --}}
        <div class="row g-3 mb-4">
            {{-- Total Doctors --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Doctors</span>
                            <h3 class="fw-bold my-1 text-dark">{{ $totalDoctors }}</h3>
                            <a href="{{ route('clinic.doctors') }}" class="text-primary fs-12 fw-semibold text-decoration-none">
                                View Doctors <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-person-badge-fill fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Patients --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Patients</span>
                            <h3 class="fw-bold my-1 text-dark">{{ $totalPatients }}</h3>
                            <a href="{{ route('clinic.patients') }}" class="text-success fs-12 fw-semibold text-decoration-none">
                                View Directory <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="bg-success-subtle text-success p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-people-fill fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Patients --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Today's Visits</span>
                            <h3 class="fw-bold my-1 text-dark">{{ $todayPatients }}</h3>
                            <span class="text-muted fs-12">Active Today</span>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-calendar-check-fill fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Staff --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Clinic Team</span>
                            <h3 class="fw-bold my-1 text-dark">{{ $totalStaff }}</h3>
                            <span class="text-muted fs-12">Reception &amp; Staff</span>
                        </div>
                        <div class="bg-info-subtle text-info p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-person-lines-fill fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doctors & Recent Patients Lists --}}
        <div class="row g-3">
            {{-- Clinic Doctors List --}}
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold fs-16 mb-0 text-dark">
                            <i class="bi bi-person-badge text-primary me-2"></i> Clinic Doctors
                        </h5>
                        <a href="{{ route('clinic.doctors') }}" class="fs-12 fw-semibold text-primary text-decoration-none">
                            View All ({{ $totalDoctors }})
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @if(count($recentDoctors) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light fs-12">
                                        <tr>
                                            <th>DOCTOR</th>
                                            <th>SPECIALIZATION</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-13">
                                        @foreach($recentDoctors as $doc)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">Dr. {{ $doc->name }}</div>
                                                <small class="text-muted">{{ $doc->Doctor_Emp_id }} &bull; {{ $doc->email }}</small>
                                            </td>
                                            <td>{{ $doc->specialization ?? 'General Physician' }}</td>
                                            <td>
                                                @if($doc->status)
                                                    <span class="badge bg-success-subtle text-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-muted opacity-50"></i>
                                No doctors registered under this clinic yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Patients List --}}
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold fs-16 mb-0 text-dark">
                            <i class="bi bi-people text-success me-2"></i> Recent Patients
                        </h5>
                        <a href="{{ route('clinic.patients') }}" class="fs-12 fw-semibold text-success text-decoration-none">
                            View All ({{ $totalPatients }})
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @if(count($recentPatients) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light fs-12">
                                        <tr>
                                            <th>PATIENT</th>
                                            <th>PHONE</th>
                                            <th>DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-13">
                                        @foreach($recentPatients as $p)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $p->name }}</div>
                                                <small class="text-muted">{{ $p->patient_id ?? 'ID: '.$p->id }}</small>
                                            </td>
                                            <td>{{ $p->phone ?? '-' }}</td>
                                            <td>{{ $p->created_at ? $p->created_at->format('d M, Y') : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-journal-medical fs-1 d-block mb-2 text-muted opacity-50"></i>
                                No patient visits recorded yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
