@extends('backend.include.layout')

@section('title', 'All Patients - Staff Portal')

@section('content')
<style>
    .valex-search-input-group {
        max-width: 360px;
        position: relative;
    }
    .valex-search-input-group .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 5;
    }
    .valex-search-input-group input {
        padding-left: 36px;
        height: 38px;
        font-size: 13px;
        border-radius: 8px;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    <i class="bi bi-people-fill me-2 text-primary"></i>All Patients Database
                </h4>
                <p class="text-muted fs-13 mb-0">
                    Dr. <span class="text-primary fw-semibold">{{ $doctor->name ?? $member->created_by ?? 'Doctor' }}</span> &bull; Search old patients and record new physical examinations
                </p>
            </div>
            <div>
                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Container -->
        <div class="valex-card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="valex-card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-4 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="valex-card-title mb-0 fw-bold text-dark">
                        Doctor Patient Records
                    </h5>
                    <span class="badge bg-primary-transparent text-primary px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                        Total {{ $allPatients->total() }} Patients
                    </span>
                </div>

                <!-- Search Form & Filter -->
                <form action="{{ route('staff.all_patients') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                    <div class="valex-search-input-group">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text"
                               name="search"
                               id="live-patient-search"
                               class="form-control border-secondary-subtle"
                               placeholder="Search Name, Reg No, Mobile..."
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('staff.all_patients') }}" class="btn btn-light btn-sm rounded-pill px-3 py-1.5 border">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="valex-card-body p-0">
                @if(count($allPatients) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 valex-table" id="all-patients-table">
                            <thead class="bg-light text-uppercase text-muted fs-11 fw-bold border-bottom">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 50px;">#</th>
                                    <th class="py-3">Patient Name</th>
                                    <th class="py-3">Reg. Number</th>
                                    <th class="py-3">Mobile Contact</th>
                                    <th class="py-3">Gender &amp; Age</th>
                                    <th class="py-3">Registration Date</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 fs-13">
                                @foreach($allPatients as $key => $patient)
                                    @php
                                        $initials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                        $rowIndex = ($allPatients->currentPage() - 1) * $allPatients->perPage() + $key + 1;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted fs-12">{{ $rowIndex }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="valex-avatar-initials bg-primary-transparent text-primary rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 12.5px;">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block fs-13.5">{{ $patient->patient_name ?? 'Patient' }}</span>
                                                    @if($patient->paymentCategory)
                                                        <small class="badge bg-primary-subtle text-primary fs-10 px-1.5 py-0.5 rounded">{{ $patient->paymentCategory->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace fs-11 px-2 py-1">
                                                {{ $patient->registration ?? $patient->patient_id ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-secondary fs-12 fw-medium">
                                                <i class="bi bi-telephone me-1 text-success"></i> {{ $patient->mobile ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fs-12">
                                                {{ $patient->gender ?? '-' }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : ($patient->age_month ? $patient->age_month.' Months' : '-') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-11">
                                                <i class="bi bi-calendar-event me-1"></i> {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y, h:i A') : '-' }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            @if($patient->can_staff_add_vitals_today)
                                                <a href="{{ route('staff.physical_exam', $patient->id) }}"
                                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 text-nowrap fw-medium"
                                                   style="font-size: 12px;"
                                                   title="Perform Physical Examination for Today">
                                                    <i class="bi bi-stethoscope me-1"></i> Physical Exam
                                                </a>
                                            @else
                                                <a href="{{ route('staff.physical_exam', $patient->id) }}"
                                                   class="btn btn-sm btn-info text-white rounded-pill px-3 py-1 text-nowrap fw-medium shadow-xs"
                                                   style="font-size: 12px;"
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

                    <!-- Pagination Section -->
                    <div class="p-3 bg-light border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="text-muted fs-12">
                            Showing <strong>{{ $allPatients->firstItem() ?? 0 }}</strong> to <strong>{{ $allPatients->lastItem() ?? 0 }}</strong> of <strong>{{ $allPatients->total() }}</strong> patients
                        </span>
                        <div class="valex-pagination">
                            {{ $allPatients->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-person-exclamation display-4 d-block mb-2 text-muted opacity-50"></i>
                        <h6 class="fw-bold text-dark">No Patients Found</h6>
                        <p class="fs-12 mb-0">No matching patient records found in Dr. {{ $doctor->name ?? $member->created_by ?? 'Doctor' }}'s database.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    // Live client-side instant search filter as user types
    document.getElementById('live-patient-search')?.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#all-patients-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
