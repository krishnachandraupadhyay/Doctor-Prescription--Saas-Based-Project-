@extends('middleend.include.layout')
@section('title', 'Add & Manage Doctors - Onboarding Portal')

@section('content')
<style>
.btn-toggle-mini {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    padding: 3px 10px !important;
    height: 24px !important;
    line-height: 1 !important;
    border-radius: 50rem !important;
    cursor: pointer !important;
    box-shadow: none !important;
    transition: all 0.15s ease-in-out !important;
    text-decoration: none !important;
}
.btn-toggle-mini i {
    font-size: 11px !important;
    margin-right: 4px !important;
    line-height: 1 !important;
}
.btn-toggle-mini.is-visible {
    background-color: #0ab39c !important;
    border: 1px solid #0ab39c !important;
    color: #ffffff !important;
}
.btn-toggle-mini.is-visible:hover {
    background-color: #099885 !important;
    border-color: #099885 !important;
    color: #ffffff !important;
}
.btn-toggle-mini.is-hidden {
    background-color: #f3f6f9 !important;
    border: 1px solid #d0d7de !important;
    color: #405189 !important;
}
.btn-toggle-mini.is-hidden:hover {
    background-color: #e2e8f0 !important;
    border-color: #b0bcd0 !important;
    color: #1e293b !important;
}
.doc-id-badge {
    background: #eef2ff;
    color: #4338ca;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
}
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill text-primary me-2"></i> Add &amp; Manage Doctors
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Onboarding Console</span> &bull; Add new doctors, select clinics, and view doctor profiles
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill">
                    <i class="bi bi-people me-1"></i> Total Doctors: {{ $doctors->total() }}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Action Toolbar: Left = Search, Right = Add Doctor Modal Button -->
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                        <!-- Left Side: Search Bar -->
                        <form method="GET" action="{{ route('adddoctor.onboarding') }}" class="d-flex align-items-center flex-grow-1" style="max-width: 420px;">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control bg-light border-start-0 ps-0 fs-13" 
                                       placeholder="Search doctor name, ID, email, phone..." 
                                       value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('adddoctor.onboarding') }}" class="btn btn-light border border-start-0 border-end-0 text-muted" title="Clear Search">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                                <button class="btn btn-primary px-3 fs-13 fw-semibold" type="submit">Search</button>
                            </div>
                        </form>

                        <!-- Right Side: Add Doctor Modal Button -->
                        <div>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3.5 fs-13 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Doctor
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3 mb-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show m-3 mb-0 shadow-sm" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                                        <th class="py-3">Contact</th>
                                        <th class="py-3">Clinic Name</th>
                                        <th class="py-3">Prescription Type</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 fs-13">
                                    @forelse($doctors as $index => $doctor)
                                        @php
                                            $isSuperAdmin = in_array(strtolower($doctor->created_by ?? ''), ['super admin', 'admin', 'superadmin', '']) || is_null($doctor->created_by);
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
                                                <span class="d-block text-dark fw-medium fs-13"><i class="bi bi-envelope text-muted me-1"></i> {{ $doctor->email }}</span>
                                                @if($doctor->phone)
                                                    <small class="text-muted fs-11"><i class="bi bi-telephone text-muted me-1"></i> {{ $doctor->phone }}</small>
                                                @else
                                                    <small class="text-muted fs-11 fst-italic">No phone added</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark fs-13">
                                                    <i class="bi bi-hospital text-primary me-1"></i> {{ $doctor->clinic->name ?? $doctor->clinic_name ?? 'General Clinic' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($doctor->prescription_type)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                        {{ ucfirst($doctor->prescription_type) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill fs-11">
                                                        Not set
                                                    </span>
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
                                                    <a href="{{ route('prescription.type', $doctor->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill py-0.5 px-2" title="Prescription Layout">
                                                        <i class="bi bi-file-earmark-pdf fs-11"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-person-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                                @if(request('search'))
                                                    No doctors found matching <strong>"{{ request('search') }}"</strong>.
                                                    <div class="mt-2">
                                                        <a href="{{ route('adddoctor.onboarding') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Search
                                                        </a>
                                                    </div>
                                                @else
                                                    No doctors added yet. Click <b>+ Add Doctor</b> to register a new doctor.
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
                                @if(request('search'))
                                    <span class="ms-1">(Filtered for: <em>"{{ request('search') }}"</em>)</span>
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
                            <label for="doctor_email" class="form-label fw-semibold fs-13">Doctor Email <span class="text-danger">*</span></label>
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
                            <label for="doctor_password" class="form-label fw-semibold fs-13">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="doctor_password"
                                       name="password"
                                       placeholder="Min 8 characters"
                                       required minlength="8" maxlength="16">
                            </div>
                        </div>

                        {{-- Select Clinic --}}
                        <div class="col-md-6">
                            <label for="doctor_clinic_id" class="form-label fw-semibold fs-13">Select Clinic <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <select class="form-select" id="doctor_clinic_id" name="clinic_id" required>
                                    <option value="">-- Choose Registered Clinic --</option>
                                    @if(isset($clinics) && count($clinics) > 0)
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                                {{ $clinic->name }} ({{ $clinic->clinic_id }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Save Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const docForm = document.getElementById('addDoctorForm');
        if (docForm) {
            docForm.addEventListener('submit', function(e) {
                const name = document.getElementById('doctor_name').value;
                if (/[0-9]/.test(name)) {
                    e.preventDefault();
                    alert('Doctor name cannot contain numbers.');
                }
            });
        }
    });
</script>
@endsection