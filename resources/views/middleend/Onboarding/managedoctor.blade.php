@extends('middleend.include.layout')
@section('title', 'Manage Doctors - Onboarding Portal')

@section('content')
<style>
.doc-id-badge {
    background: #eef2ff;
    color: #4338ca;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
}
.clinic-filter-select {
    min-width: 220px;
    background-color: #f8fafc;
    border-color: #e2e8f0;
    font-weight: 500;
}
.clinic-filter-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill text-primary me-2"></i> Manage Doctors
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Onboarding Console</span> &bull; View, filter by clinic, edit details, and manage doctors
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill">
                    <i class="bi bi-people me-1"></i> Total Doctors: {{ $doctors->total() }}
                </span>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3.5 fs-13 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Doctor
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Action Toolbar: Left = Clinic Filter, Right = Search -->
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <form method="GET" action="{{ route('/manage.doctor') }}" id="doctorFilterForm" class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <!-- Left Side: Clinic Filter Dropdown -->
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="input-group input-group-sm" style="max-width: 260px;">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-hospital text-primary"></i>
                                    </span>
                                    <select name="clinic_id" id="clinic_filter" class="form-select form-select-sm clinic-filter-select fs-13" onchange="document.getElementById('doctorFilterForm').submit();">
                                        <option value="">-- All Clinics (Filter) --</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                                {{ $clinic->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(request('clinic_id'))
                                    @php
                                        $selectedClinicObj = $clinics->firstWhere('id', request('clinic_id'));
                                    @endphp
                                    @if($selectedClinicObj)
                                        <span class="badge bg-indigo-subtle text-indigo border px-2.5 py-1.5 rounded-pill fs-12 fw-medium d-inline-flex align-items-center gap-1" style="background:#eef2ff; color:#4f46e5; border-color:#c7d2fe !important;">
                                            <i class="bi bi-funnel-fill"></i> Clinic: {{ $selectedClinicObj->name }}
                                            <a href="{{ route('/manage.doctor', ['search' => request('search')]) }}" class="text-danger ms-1 text-decoration-none" title="Remove Clinic Filter">&times;</a>
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <!-- Right Side: Search Input -->
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm" style="min-width: 280px; max-width: 350px;">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control bg-light border-start-0 ps-0 fs-13" 
                                           placeholder="Search doctor name, ID, phone..." 
                                           value="{{ request('search') }}">
                                    @if(request('search') || request('clinic_id'))
                                        <a href="{{ route('/manage.doctor') }}" class="btn btn-light border border-start-0 border-end-0 text-muted" title="Clear Filters">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-primary px-3 fs-13 fw-semibold" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3 mb-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 40px;">#</th>
                                        <th class="py-3">Doctor Details</th>
                                        <th class="py-3">Assigned Clinic</th>
                                        <th class="py-3">Contact</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 fs-13">
                                    @forelse($doctors as $index => $doctor)
                                        @php
                                            $isSuperAdmin = in_array(strtolower($doctor->created_by ?? ''), ['super admin', 'admin', 'superadmin', '']) || is_null($doctor->created_by);
                                            $docClinic = $doctor->clinic;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div>
                                                        <span class="fw-bold text-dark d-block fs-14">Dr. {{ $doctor->name }}</span>
                                                        <div class="d-flex align-items-center gap-1 mt-0.5 flex-wrap">
                                                            <span class="doc-id-badge">{{ $doctor->Doctor_Emp_id ?? 'DOC-'.$doctor->id }}</span>
                                                            @if($isSuperAdmin)
                                                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold" style="background:#f3e8ff; color:#7e22ce; border:1px solid #e9d5ff;">
                                                                    <i class="bi bi-shield-lock me-1"></i> Super Admin
                                                                </span>
                                                            @else
                                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                                                    <i class="bi bi-person-check me-1"></i> {{ $doctor->creator_name }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($docClinic)
                                                    <span class="fw-semibold text-dark d-block fs-13">
                                                        <i class="bi bi-hospital text-primary me-1"></i> {{ $docClinic->name }}
                                                    </span>
                                                    <span class="badge px-2 py-0.5 rounded-pill fs-10 fw-semibold" style="{{ strtolower($docClinic->prescription_type ?? 'fixed') === 'customize' ? 'background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;' : 'background:#ede9fe; color:#6366f1; border:1px solid #c7d2fe;' }}">
                                                        {{ ucfirst($docClinic->prescription_type ?? 'Fixed') }} Layout
                                                    </span>
                                                @elseif($doctor->clinic_name)
                                                    <span class="fw-medium text-dark d-block fs-13">
                                                        <i class="bi bi-hospital text-muted me-1"></i> {{ $doctor->clinic_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fs-12 fst-italic">No clinic assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="d-block text-dark fw-medium fs-13"><i class="bi bi-envelope text-muted me-1"></i> {{ $doctor->email }}</span>
                                                @if($doctor->phone)
                                                    <small class="text-muted fs-11"><i class="bi bi-telephone text-muted me-1"></i> {{ $doctor->phone }}</small>
                                                @else
                                                    <small class="text-muted fs-11 fst-italic">No phone added</small>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('doctor.onboarding.edit', $doctor->id) }}" class="btn btn-outline-primary btn-sm rounded-pill py-0.5 px-2" title="Edit Doctor Details">
                                                        <i class="bi bi-pencil-fill fs-11"></i> 
                                                    </a>
                                                    <a href="{{ route('onboarding.view.doctor', $doctor->id) }}" class="btn btn-outline-info btn-sm rounded-pill py-0.5 px-2" title="View Profile">
                                                        <i class="bi bi-eye fs-11"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-person-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                                                @if(request('clinic_id'))
                                                    @php
                                                        $filterClinic = $clinics->firstWhere('id', request('clinic_id'));
                                                    @endphp
                                                    <h6 class="fw-bold text-dark mt-2 mb-1">No Doctors found for {{ $filterClinic->name ?? 'selected clinic' }}</h6>
                                                    <p class="text-muted fs-13 mb-3">You can add doctors to this clinic anytime.</p>
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3.5 fs-13 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                                            <i class="bi bi-plus-circle me-1"></i> Add Doctor to {{ $filterClinic->name ?? 'Clinic' }}
                                                        </button>
                                                        <a href="{{ route('/manage.doctor') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fs-13">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> View All Clinics
                                                        </a>
                                                    </div>
                                                @elseif(request('search'))
                                                    No doctors found matching <strong>"{{ request('search') }}"</strong>.
                                                    <div class="mt-2">
                                                        <a href="{{ route('/manage.doctor') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filter
                                                        </a>
                                                    </div>
                                                @else
                                                    <h6 class="fw-bold text-dark mt-2 mb-1">No Doctors Found</h6>
                                                    <p class="text-muted fs-13 mb-3">Start by registering your first doctor.</p>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3.5 fs-13 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                                            <i class="bi bi-plus-circle me-1"></i> Add Doctor
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($doctors->hasPages() || $doctors->total() > 0)
                        <div class="card-footer bg-light border-top d-flex flex-wrap align-items-center justify-content-between py-3 px-4 gap-2">
                            <div class="text-muted fs-12">
                                Showing <strong>{{ $doctors->firstItem() ?? 0 }}</strong> to <strong>{{ $doctors->lastItem() ?? 0 }}</strong> of <strong>{{ $doctors->total() }}</strong> doctors
                                @if(request('search') || request('clinic_id'))
                                    <span class="ms-1">(Filtered Results)</span>
                                @endif
                            </div>
                            <div class="pagination-wrap">
                                {{ $doctors->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>

<!-- ========================================================================= -->
<!-- BOOTSTRAP MODAL: ADD DOCTOR MODAL                                         -->
<!-- ========================================================================= -->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="addDoctorModalLabel">
                    <i class="bi bi-person-plus-fill"></i> Add New Doctor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('adddoctor.onboarding.add') }}" id="addDoctorForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Doctor Name --}}
                        <div class="col-md-6">
                            <label for="doctor_name" class="form-label fw-semibold fs-13">Doctor Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="doctor_name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g. Dr. Ramesh Kumar"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>
                        </div>

                        {{-- Doctor Email --}}
                        <div class="col-md-6">
                            <label for="doctor_email" class="form-label fw-semibold fs-13">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control" 
                                       id="doctor_email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="e.g. doctor@clinic.com" 
                                       required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="doctor_password" class="form-label fw-semibold fs-13">Initial Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="doctor_password" 
                                       name="password" 
                                       placeholder="Minimum 8 characters" 
                                       minlength="8"
                                       maxlength="16"
                                       required>
                            </div>
                        </div>

                        {{-- Clinic Selection --}}
                        <div class="col-md-6">
                            <label for="modal_clinic_id" class="form-label fw-semibold fs-13">Assign Clinic <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <select class="form-select" id="modal_clinic_id" name="clinic_id" required>
                                    <option value="" disabled {{ !old('clinic_id', request('clinic_id')) ? 'selected' : '' }}>-- Choose Clinic --</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}" {{ old('clinic_id', request('clinic_id')) == $clinic->id ? 'selected' : '' }}>
                                            {{ $clinic->name }} ({{ ucfirst($clinic->prescription_type ?? 'Fixed') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-top">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Register Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection