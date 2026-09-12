@extends('clinic.include.layout')
@section('title', 'Clinic Doctors - MediPortal')

@section('content')
<style>
    .upload-asset-card {
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        position: relative;
        transition: all 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 190px;
    }
    .upload-asset-card:hover {
        border-color: #7b2ff7;
        background: #faf5ff;
        box-shadow: 0 4px 14px rgba(123, 47, 247, 0.08);
    }
    .upload-asset-card.has-file {
        border-style: solid;
        border-color: #e2e8f0;
        background: #ffffff;
    }
    .upload-asset-card input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
    }
    .upload-icon-bubble {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ede9fe;
        color: #7b2ff7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .upload-asset-card:hover .upload-icon-bubble {
        transform: scale(1.08);
        background: #7b2ff7;
        color: #ffffff;
    }
    .btn-choose-file {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ffffff;
        border: 1px solid #7b2ff7;
        color: #7b2ff7;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-top: 8px;
        pointer-events: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .upload-asset-card:hover .btn-choose-file {
        background: #7b2ff7;
        color: #ffffff;
    }
    .preview-thumbnail-container {
        position: relative;
        max-width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .preview-thumb-img {
        max-height: 100px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 3px;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .file-name-badge {
        font-size: 11px;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 2px 8px;
        margin-top: 6px;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 500;
    }
</style>
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 pb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-person-badge-fill text-primary me-2"></i> Clinic Doctors
                        </h4>
                        <p class="text-muted fs-13 mb-0">List of all doctors registered under <strong>{{ $clinic->name }}</strong> ({{ $clinic->clinic_id }})</p>
                    </div>

                    <!-- Right Side: Add Doctor Button -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 fs-13 fw-semibold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                            <i class="bi bi-person-plus-fill fs-15"></i> Add Doctor
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-12 text-uppercase">
                            <tr>
                                <th>DOCTOR ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>SPECIALIZATION</th>
                                <th>STATUS</th>
                                <th>VERIFICATION</th>
                                <th class="text-end pe-3">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="fs-13">
                            @forelse($doctors as $doc)
                            @php
                                $isActive = ($doc->status ?? 0) == 1;
                                $isVerified = ($doc->verified ?? 0) == 1;
                                $initials = strtoupper(substr($doc->name ?? 'Dr', 0, 2));
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-semibold">{{ $doc->Doctor_Emp_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-12" style="width: 32px; height: 32px;">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-13">Dr. {{ $doc->name }}</div>
                                            <small class="text-muted fs-11">{{ $doc->specialization ?? 'General Physician' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $doc->email }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $doc->specialization ?? 'General Physician' }}</span>
                                </td>
                                <td>
                                    @if($isActive)
                                        <a href="{{ route('clinic.doctors.toggle_status', $doc->id) }}"
                                           class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                           title="Click to Deactivate">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active
                                        </a>
                                    @else
                                        <a href="{{ route('clinic.doctors.toggle_status', $doc->id) }}"
                                           class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                           title="Click to Activate">
                                            <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($isVerified)
                                        <a href="{{ route('clinic.doctors.verify', $doc->id) }}"
                                           class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                           title="Click to Unverify">
                                            <i class="bi bi-shield-check me-1"></i> Verified
                                        </a>
                                    @else
                                        <a href="{{ route('clinic.doctors.verify', $doc->id) }}"
                                           class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                           title="Click to Verify">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </a>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-1">
                                        {{-- Upload Doctor Documents (Photo & Signature) --}}
                                        <button type="button"
                                                class="btn btn-sm btn-light rounded-circle p-1 text-primary btn-upload-doc"
                                                style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;"
                                                title="Upload Doctor Signature & Photo"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadDoctorDocModal"
                                                data-doctor-id="{{ $doc->id }}"
                                                data-doctor-name="{{ $doc->name }}"
                                                data-doctor-code="{{ $doc->Doctor_Emp_id }}"
                                                data-upload-url="{{ route('clinic.doctors.upload_documents', $doc->id) }}"
                                                data-current-photo="{{ ($doc->doctor_clinic_documents && $doc->doctor_clinic_documents->photo && file_exists(public_path($doc->doctor_clinic_documents->photo))) ? asset($doc->doctor_clinic_documents->photo) : '' }}"
                                                data-current-sign="{{ ($doc->doctor_clinic_documents && $doc->doctor_clinic_documents->doctor_sign && file_exists(public_path($doc->doctor_clinic_documents->doctor_sign))) ? asset($doc->doctor_clinic_documents->doctor_sign) : '' }}">
                                            <i class="bi bi-cloud-arrow-up-fill fs-14"></i>
                                        </button>

                                        {{-- Status toggle icon --}}
                                        <a href="{{ route('clinic.doctors.toggle_status', $doc->id) }}"
                                           class="btn btn-sm btn-light rounded-circle p-1"
                                           style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;"
                                           title="{{ $isActive ? 'Deactivate Doctor' : 'Activate Doctor' }}">
                                            <i class="bi {{ $isActive ? 'bi-toggle-on text-success fs-15' : 'bi-toggle-off text-muted fs-15' }}"></i>
                                        </a>

                                        {{-- Verify toggle icon --}}
                                        <a href="{{ route('clinic.doctors.verify', $doc->id) }}"
                                           class="btn btn-sm btn-light rounded-circle p-1"
                                           style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;"
                                           title="{{ $isVerified ? 'Click to Unverify' : 'Click to Verify' }}">
                                            <i class="bi {{ $isVerified ? 'bi-patch-check-fill text-success fs-14' : 'bi-patch-check text-warning fs-14' }}"></i>
                                        </a>

                                        {{-- Delete doctor --}}
                                        <form action="{{ route('clinic.doctors.destroy', $doc->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to remove Dr. {{ $doc->name }} from this clinic?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light rounded-circle p-1 text-danger" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;" title="Remove Doctor">
                                                <i class="bi bi-trash fs-14"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-x fs-1 d-block mb-2 opacity-50"></i>
                                    No doctors registered under this clinic yet.
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                            <i class="bi bi-plus-lg me-1"></i> Add Doctor
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($doctors, 'hasPages') && $doctors->hasPages())
                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-13">Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} doctors</span>
                    <div>{{ $doctors->links() }}</div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- BOOTSTRAP MODAL: ADD DOCTOR MODAL FOR CLINIC                             -->
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
            <form method="POST" action="{{ route('clinic.doctors.store') }}" id="addClinicDoctorForm">
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
                                       placeholder="e.g. doctor@hospital.com"
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

                        {{-- Clinic (Pre-assigned to current clinic) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Clinic / Hospital</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <input type="text" class="form-control bg-light" value="{{ $clinic->name }} ({{ $clinic->clinic_id }})" readonly disabled>
                            </div>
                        </div>

                        {{-- Contact / Phone --}}
                        <div class="col-md-6">
                            <label for="doctor_phone" class="form-label fw-semibold fs-13">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel"
                                       class="form-control"
                                       id="doctor_phone"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="10-digit mobile number"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            </div>
                        </div>

                        {{-- Specialization --}}
                        <div class="col-md-6">
                            <label for="doctor_specialization" class="form-label fw-semibold fs-13">Specialization</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-award"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="doctor_specialization"
                                       name="specialization"
                                       value="{{ old('specialization') }}"
                                       placeholder="e.g. General Physician, Cardiologist">
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

<!-- ========================================================================= -->
<!-- BOOTSTRAP MODAL: UPLOAD DOCTOR DOCUMENTS (SIGNATURE & PHOTO)              -->
<!-- ========================================================================= -->
<div class="modal fade" id="uploadDoctorDocModal" tabindex="-1" aria-labelledby="uploadDoctorDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header py-3 text-white" style="background: linear-gradient(135deg, #7b2ff7, #f107a3);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold fs-16 text-white mb-0" id="uploadDoctorDocModalLabel">Upload Doctor Documents</h5>
                        <small class="text-white-50 fs-12">Upload photo &amp; signature for digital prescriptions</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="uploadDoctorDocForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light-subtle">

                    {{-- Doctor Info Banner in Modal --}}
                    <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between mb-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-16" style="width: 44px; height: 44px;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0 fs-15" id="modal_doc_name">Dr. Name</h6>
                                <span class="badge bg-light text-muted border fs-11" id="modal_doc_code">ID: DOC-0000</span>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                            <i class="bi bi-hospital me-1"></i> {{ $clinic->name }}
                        </span>
                    </div>

                    <div class="row g-4">
                        {{-- 1. Doctor Photo Upload Card --}}
                        <div class="col-md-6">
                            <div class="card border h-100 shadow-sm rounded-3">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label fw-bold text-dark fs-13 mb-0">
                                            <i class="bi bi-person-bounding-box text-primary me-1"></i> Doctor Photo / Avatar
                                        </label>
                                        <span class="badge bg-light text-secondary border fs-10">Max 2MB</span>
                                    </div>
                                    <p class="text-muted fs-11 mb-3">Official portrait photograph for prescription header.</p>

                                    <div class="upload-asset-card flex-grow-1" id="dropzone_photo">
                                        <input type="file" name="photo" id="doc_photo_input" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        
                                        {{-- Idle / Empty State --}}
                                        <div class="upload-idle-state" id="idle_photo">
                                            <div class="upload-icon-bubble mx-auto">
                                                <i class="bi bi-camera"></i>
                                            </div>
                                            <div class="fw-semibold text-dark fs-13">Choose Doctor Photo</div>
                                            <div class="text-muted fs-11 mt-0.5">Drag &amp; drop or click to browse</div>
                                            <div class="btn-choose-file">
                                                <i class="bi bi-folder2-open"></i> Browse Image
                                            </div>
                                        </div>

                                        {{-- Selected / Existing Preview State --}}
                                        <div class="preview-thumbnail-container d-none" id="preview_box_photo">
                                            <div class="position-relative d-inline-block">
                                                <img id="preview_doc_photo" src="" alt="Doctor Photo" class="preview-thumb-img rounded-circle" style="width: 90px; height: 90px; object-fit: cover;">
                                                <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm" title="Change Image">
                                                    <i class="bi bi-pencil-fill text-white fs-10"></i>
                                                </span>
                                            </div>
                                            <span class="file-name-badge" id="photo_file_name">No file selected</span>
                                            <small class="text-primary fs-11 mt-1 fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Click to change photo</small>
                                        </div>
                                    </div>
                                    <small class="text-muted fs-11 d-block mt-2 text-center">Allowed: JPG, JPEG, PNG, WEBP</small>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Doctor Signature Upload Card (PNG ONLY) --}}
                        <div class="col-md-6">
                            <div class="card border h-100 shadow-sm rounded-3">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label fw-bold text-dark fs-13 mb-0">
                                            <i class="bi bi-pen-fill text-primary me-1"></i> Doctor Signature
                                        </label>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-10 fw-bold">PNG Only (Max 2MB)</span>
                                    </div>
                                    <p class="text-muted fs-11 mb-3">Official transparent PNG signature for prescription stamping.</p>

                                    <div class="upload-asset-card flex-grow-1" id="dropzone_sign">
                                        <input type="file" name="sign" id="doc_sign_input" accept="image/png, .png">
                                        
                                        {{-- Idle / Empty State --}}
                                        <div class="upload-idle-state" id="idle_sign">
                                            <div class="upload-icon-bubble mx-auto">
                                                <i class="bi bi-pencil"></i>
                                            </div>
                                            <div class="fw-semibold text-dark fs-13">Choose PNG Signature</div>
                                            <div class="text-muted fs-11 mt-0.5">Drag &amp; drop or click to browse</div>
                                            <div class="btn-choose-file">
                                                <i class="bi bi-file-earmark-image"></i> Browse PNG File
                                            </div>
                                        </div>

                                        {{-- Selected / Existing Preview State --}}
                                        <div class="preview-thumbnail-container d-none" id="preview_box_sign">
                                            <div class="position-relative d-inline-block">
                                                <img id="preview_doc_sign" src="" alt="Doctor Signature" class="preview-thumb-img" style="max-height: 80px; max-width: 170px; object-fit: contain; background: #fafafa;">
                                                <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm" title="Change Image">
                                                    <i class="bi bi-pencil-fill text-white fs-10"></i>
                                                </span>
                                            </div>
                                            <span class="file-name-badge" id="sign_file_name">No file selected</span>
                                            <small class="text-primary fs-11 mt-1 fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Click to change PNG signature</small>
                                        </div>
                                    </div>
                                    <small class="text-danger-emphasis fs-11 d-block mt-2 text-center fw-medium">
                                        <i class="bi bi-info-circle me-1"></i> Only <strong>.PNG</strong> format allowed (Transparent recommended)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white py-3 px-4 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-sm px-4 text-white fw-semibold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7b2ff7, #f107a3); padding: 7px 20px;">
                        <i class="bi bi-cloud-arrow-up-fill me-1.5"></i> Save &amp; Upload Documents
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add Doctor Form validation
        const docForm = document.getElementById('addClinicDoctorForm');
        if (docForm) {
            docForm.addEventListener('submit', function(e) {
                const name = document.getElementById('doctor_name').value;
                if (/[0-9]/.test(name)) {
                    e.preventDefault();
                    alert('Doctor name cannot contain numbers.');
                }
            });
        }

        // Upload Doctor Documents Modal Logic
        const uploadModal = document.getElementById('uploadDoctorDocModal');
        const uploadForm  = document.getElementById('uploadDoctorDocForm');

        const dropzonePhoto    = document.getElementById('dropzone_photo');
        const idlePhoto        = document.getElementById('idle_photo');
        const previewBoxPhoto  = document.getElementById('preview_box_photo');
        const previewPhoto     = document.getElementById('preview_doc_photo');
        const photoFileName    = document.getElementById('photo_file_name');
        const photoInput       = document.getElementById('doc_photo_input');

        const dropzoneSign     = document.getElementById('dropzone_sign');
        const idleSign         = document.getElementById('idle_sign');
        const previewBoxSign   = document.getElementById('preview_box_sign');
        const previewSign      = document.getElementById('preview_doc_sign');
        const signFileName     = document.getElementById('sign_file_name');
        const signInput        = document.getElementById('doc_sign_input');

        function setPhotoPreview(src, nameText, isExisting = false) {
            if (src && src !== '') {
                previewPhoto.src = src;
                previewBoxPhoto.classList.remove('d-none');
                idlePhoto.classList.add('d-none');
                dropzonePhoto.classList.add('has-file');
                photoFileName.textContent = nameText || (isExisting ? 'Existing Photo' : 'Selected Photo');
            } else {
                previewPhoto.src = '';
                previewBoxPhoto.classList.add('d-none');
                idlePhoto.classList.remove('d-none');
                dropzonePhoto.classList.remove('has-file');
                photoFileName.textContent = 'No file selected';
            }
        }

        function setSignPreview(src, nameText, isExisting = false) {
            if (src && src !== '') {
                previewSign.src = src;
                previewBoxSign.classList.remove('d-none');
                idleSign.classList.add('d-none');
                dropzoneSign.classList.add('has-file');
                signFileName.textContent = nameText || (isExisting ? 'Existing Signature' : 'Selected Signature');
            } else {
                previewSign.src = '';
                previewBoxSign.classList.add('d-none');
                idleSign.classList.remove('d-none');
                dropzoneSign.classList.remove('has-file');
                signFileName.textContent = 'No file selected';
            }
        }

        uploadModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            if (!btn) return;

            const docId        = btn.getAttribute('data-doctor-id');
            const docName      = btn.getAttribute('data-doctor-name');
            const docCode      = btn.getAttribute('data-doctor-code');
            const uploadUrl    = btn.getAttribute('data-upload-url');
            const currentPhoto = btn.getAttribute('data-current-photo');
            const currentSign  = btn.getAttribute('data-current-sign');

            uploadForm.setAttribute('action', uploadUrl);
            document.getElementById('modal_doc_name').textContent = 'Dr. ' + docName;
            document.getElementById('modal_doc_code').textContent = 'ID: ' + docCode;

            // Reset inputs
            photoInput.value = '';
            signInput.value  = '';

            // Setup Photo preview
            if (currentPhoto && currentPhoto !== '') {
                setPhotoPreview(currentPhoto, 'Current Uploaded Photo', true);
            } else {
                setPhotoPreview('', '');
            }

            // Setup Sign preview
            if (currentSign && currentSign !== '') {
                setSignPreview(currentSign, 'Current Uploaded Signature', true);
            } else {
                setSignPreview('', '');
            }
        });

        // Live file selection preview
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    setPhotoPreview(e.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        signInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate PNG only
                const fileName = file.name.toLowerCase();
                const fileType = file.type.toLowerCase();
                if (!fileName.endsWith('.png') && fileType !== 'image/png') {
                    alert('Doctor Signature must be in PNG format (.png only). Please select a valid PNG image.');
                    this.value = '';
                    setSignPreview('', '');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    setSignPreview(e.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
