@extends('backend.include.layout')

@section('title', 'Deleted Doctors')

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-trash3-fill text-danger me-2"></i> Deleted Doctors</h4>
                <p class="text-muted fs-13 mb-0">Doctors in this list cannot log in to the portal. You can restore them back to Active Management at any time.</p>
            </div>
            <a href="{{ url('/managedoctor') }}" class="btn btn-primary rounded-pill px-3.5 py-1.5 fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 fw-bold text-dark">Deleted Doctors List</h5>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                {{ isset($doctors) ? count($doctors) : 0 }} Deleted
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Doctor ID</th>
                                        <th class="py-3">Doctor Name</th>
                                        <th class="py-3">Email Address</th>
                                        <th class="py-3">Clinic Name</th>
                                        <th class="py-3 text-center">Account Status</th>
                                        <th class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 fs-13">
                                    @forelse($doctors as $doctor)
                                    @php
                                        $initials = strtoupper(substr($doctor->name ?? 'Dr', 0, 2));
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                {{ $doctor->Doctor_Emp_id ?? 'DOC-'.$doctor->id }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="valex-avatar-circle bg-danger-subtle text-danger fs-12 fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark fs-13">Dr. {{ $doctor->name }}</h6>
                                                    <small class="text-muted fs-11">{{ $doctor->specialization ?? 'General Physician' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark"><i class="bi bi-envelope me-1 text-muted"></i> {{ $doctor->email }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium text-dark"><i class="bi bi-hospital me-1 text-primary"></i> {{ $doctor->clinic_name ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Deleted
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <a href="{{ route('promotedoctor', $doctor->id) }}" 
                                                   class="btn btn-outline-success btn-sm rounded-pill py-0.5 px-2"
                                                   title="Restore Doctor"
                                                   onclick="return confirm('Are you sure you want to restore Dr. {{ addslashes($doctor->name) }} back to active management?');">
                                                    <i class="bi bi-arrow-counterclockwise fs-11"></i>
                                                </a>
                                                <a href="{{ route('viewdoctor', $doctor->id) }}" 
                                                   class="btn btn-outline-info btn-sm rounded-pill py-0.5 px-2"
                                                   title="View Doctor Details">
                                                    <i class="bi bi-eye fs-11"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-trash3 fs-2 d-block mb-2 text-muted opacity-50"></i>
                                            No deleted doctors found. All doctors are in active management.
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
    <!-- container-fluid -->
</div>
@endsection