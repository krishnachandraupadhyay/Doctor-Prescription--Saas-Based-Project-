@extends('backend.include.layout')

@section('title', 'Patient Records Database — Admin Console')

@section('content')
<div class="page-content wrapper py-4">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill text-primary me-2"></i>Patient Records Directory
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Super Admin Portal</span> &bull; All patients registered across hospital doctors &amp; desks
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white border px-3 py-2 text-dark fs-12 fw-semibold shadow-xs">
                    <i class="bi bi-person-lines-fill text-primary me-1"></i> Total Patients: {{ count($patients) }}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" id="patientsList">
                    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>All Registered Patients
                        </h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fs-12 fw-bold">
                            {{ count($patients) }} Patients
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Patient Info</th>
                                        <th class="py-3">Reg. Number</th>
                                        <th class="py-3">Added By (Staff/Desk)</th>
                                        <th class="py-3">Assigned Doctor</th>
                                        <th class="py-3 text-center">Visits Count</th>
                                        <th class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patients as $key => $patient)
                                    @php
                                         $visitsCount = \App\Models\presciption_data::where('patient_id', $patient->id)
                                             ->selectRaw("COUNT(DISTINCT DATE_FORMAT(created_at, '%Y-%m-%d %H:%i')) as total_visits")
                                             ->value('total_visits') ?? 0;
                                         if ($visitsCount == 0 && $patient->created_at) {
                                             $visitsCount = 1;
                                         }
                                        $initials = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="bg-primary-subtle text-primary rounded-circle fw-bold fs-12 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $patient->patient_name ?? 'Unknown' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace fs-12 px-2.5 py-1 rounded">
                                                {{ $patient->registration ?? $patient->patient_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-12">
                                                <i class="bi bi-person-badge text-primary me-1"></i>{{ $patient->created_by ?? 'Receptionist' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary fs-12">
                                                <i class="bi bi-person-heart me-1"></i>{{ $patient->doctor ? 'Dr. '.$patient->doctor->name : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill fs-12 fw-bold">
                                                {{ $visitsCount }} {{ Str::plural('Visit', $visitsCount) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('admin.view_patient', $patient->id) }}" class="btn btn-sm btn-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 34px; height: 34px;" title="View Patient Details">
                                                <i class="bi bi-eye-fill fs-6"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-people fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No patient records found in the database.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection