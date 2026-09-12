@extends('clinic.include.layout')
@section('title', 'Clinic Profile & Documents - MediPortal')

@section('content')
<style>
    .doc-preview-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        transition: all 0.25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .doc-preview-card:hover {
        border-color: #0162e8;
        box-shadow: 0 6px 18px -4px rgba(1, 98, 232, 0.12);
        transform: translateY(-2px);
    }
    .doc-img-container {
        height: 160px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
    }
    .doc-img-container img {
        max-height: 140px;
        max-width: 90%;
        object-fit: contain;
        transition: transform 0.2s ease;
    }
    .doc-img-container:hover img {
        transform: scale(1.05);
    }
    .doc-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        padding: 20px;
        text-align: center;
    }
    .doc-zoom-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .doc-img-container:hover .doc-zoom-overlay {
        opacity: 1;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Clinic Profile &amp; Documents</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->name }}
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                View clinic contact details, official logos, seals, header &amp; footer branding documents.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- 1. Clinic Details Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2">
                        <h4 class="fw-bold mb-1 text-dark fs-16">
                            <i class="bi bi-building text-primary me-2"></i> Clinic Information
                        </h4>
                        <p class="text-muted fs-13 mb-0">Update clinic identity and contact information</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('clinic.profile.update') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-13">Clinic ID</label>
                                    <input type="text" class="form-control bg-light fw-bold" value="{{ $clinic->clinic_id }}" readonly disabled>
                                </div>

                                <div class="col-md-6">
                                    <label for="clinic_code" class="form-label fw-semibold fs-13">Clinic Code (Optional)</label>
                                    <input type="text" class="form-control" id="clinic_code" name="clinic_code" value="{{ old('clinic_code', $clinic->clinic_code) }}" placeholder="e.g. APX-01">
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold fs-13">Clinic Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $clinic->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold fs-13 d-flex align-items-center justify-content-between">
                                        <span>Clinic Email</span>
                                        <span class="badge bg-secondary-subtle text-secondary fs-11 fw-normal"><i class="bi bi-lock-fill me-1"></i>Superadmin Managed</span>
                                    </label>
                                    <input type="email" class="form-control bg-light" id="email" value="{{ $clinic->email }}" readonly disabled>
                                    <small class="text-muted fs-11"><i class="bi bi-info-circle me-1"></i>Email address can only be changed by Super Admin.</small>
                                </div>

                                <div class="col-md-12">
                                    <label for="phone" class="form-label fw-semibold fs-13">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" maxlength="10" minlength="10" pattern="[0-9]{10}" value="{{ old('phone', $clinic->phone) }}" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label fw-semibold fs-13">Clinic Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $clinic->address) }}</textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                        <i class="bi bi-check-lg me-1"></i> Save Clinic Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 2. Change Password Form --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2">
                        <h4 class="fw-bold mb-1 text-dark fs-16">
                            <i class="bi bi-shield-lock text-danger me-2"></i> Change Password
                        </h4>
                        <p class="text-muted fs-13 mb-0">Update your clinic portal login password</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('clinic.password.update') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label fw-semibold fs-13">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label fw-semibold fs-13">New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label fw-semibold fs-13">Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6" required>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-danger px-4 fw-semibold shadow-sm">
                                        <i class="bi bi-key me-1"></i> Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 3. SECTION: Clinic Documents & Branding Assets (View Only) --}}
            @php
                $isCustomizePrescription = strtolower(trim($clinic->prescription_type ?? 'fixed')) === 'customize';
            @endphp
            <div class="col-12 mt-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark fs-16">
                                    <i class="bi bi-file-earmark-image text-success me-2"></i> Clinic Documents &amp; Branding Assets
                                </h4>
                                <p class="text-muted fs-13 mb-0">
                                    Official branding assets, stamps, and prescription letterhead documents configured for this clinic.
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if($isCustomizePrescription)
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold" style="background: rgba(121, 40, 202, 0.1); color: #7928ca; border: 1px solid rgba(121, 40, 202, 0.25);">
                                        <i class="bi bi-sliders me-1"></i> Customize Layout
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                        <i class="bi bi-layout-text-window-reverse me-1"></i> Fixed Banner Layout
                                    </span>
                                @endif
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                    <i class="bi bi-shield-check me-1"></i> Verified Clinic Assets
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- Document 1: Clinic Logo / Photo --}}
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="doc-preview-card">
                                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                        <span class="fw-bold text-dark fs-13">
                                            <i class="bi bi-image text-primary me-1"></i> Clinic Logo / Photo
                                        </span>
                                        @if($clinicDocument && $clinicDocument->photo && file_exists(public_path($clinicDocument->photo)))
                                            <span class="badge bg-success-subtle text-success fs-11">Available</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fs-11">Not Available</span>
                                        @endif
                                    </div>

                                    <div class="doc-img-container" onclick="openDocPreview('{{ $clinicDocument && $clinicDocument->photo && file_exists(public_path($clinicDocument->photo)) ? asset($clinicDocument->photo) : '' }}', 'Clinic Logo / Photo')">
                                        @if($clinicDocument && $clinicDocument->photo && file_exists(public_path($clinicDocument->photo)))
                                            <img src="{{ asset($clinicDocument->photo) }}" alt="Clinic Logo">
                                            <div class="doc-zoom-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                                        @else
                                            <div class="doc-empty-state">
                                                <i class="bi bi-image fs-1 mb-1 opacity-50"></i>
                                                <span class="fs-12">No logo available</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3 bg-white mt-auto d-flex align-items-center justify-content-between">
                                        <small class="text-muted fs-11">Official Logo</small>
                                        @if($clinicDocument && $clinicDocument->photo && file_exists(public_path($clinicDocument->photo)))
                                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2.5 fs-12 rounded-pill" onclick="openDocPreview('{{ asset($clinicDocument->photo) }}', 'Clinic Logo / Photo')">
                                                <i class="bi bi-eye me-1"></i> View Full Image
                                            </button>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Document 2: Clinic Stamp & Seal --}}
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="doc-preview-card">
                                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                        <span class="fw-bold text-dark fs-13">
                                            <i class="bi bi-award text-success me-1"></i> Clinic Stamp &amp; Seal
                                        </span>
                                        @if($clinicDocument && $clinicDocument->clinic_stamp && file_exists(public_path($clinicDocument->clinic_stamp)))
                                            <span class="badge bg-success-subtle text-success fs-11">Available</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fs-11">Not Available</span>
                                        @endif
                                    </div>

                                    <div class="doc-img-container" onclick="openDocPreview('{{ $clinicDocument && $clinicDocument->clinic_stamp && file_exists(public_path($clinicDocument->clinic_stamp)) ? asset($clinicDocument->clinic_stamp) : '' }}', 'Clinic Official Stamp & Seal')">
                                        @if($clinicDocument && $clinicDocument->clinic_stamp && file_exists(public_path($clinicDocument->clinic_stamp)))
                                            <img src="{{ asset($clinicDocument->clinic_stamp) }}" alt="Clinic Stamp">
                                            <div class="doc-zoom-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                                        @else
                                            <div class="doc-empty-state">
                                                <i class="bi bi-patch-check fs-1 mb-1 opacity-50"></i>
                                                <span class="fs-12">No stamp available</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3 bg-white mt-auto d-flex align-items-center justify-content-between">
                                        <small class="text-muted fs-11">Official Stamp</small>
                                        @if($clinicDocument && $clinicDocument->clinic_stamp && file_exists(public_path($clinicDocument->clinic_stamp)))
                                            <button type="button" class="btn btn-sm btn-outline-success py-1 px-2.5 fs-12 rounded-pill" onclick="openDocPreview('{{ asset($clinicDocument->clinic_stamp) }}', 'Clinic Official Stamp & Seal')">
                                                <i class="bi bi-eye me-1"></i> View Full Image
                                            </button>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Document 3: Doctor / Authorized Signature --}}
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="doc-preview-card">
                                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                        <span class="fw-bold text-dark fs-13">
                                            <i class="bi bi-pen text-info me-1"></i> Authorized Signature
                                        </span>
                                        @if($clinicDocument && $clinicDocument->doctor_sign && file_exists(public_path($clinicDocument->doctor_sign)))
                                            <span class="badge bg-success-subtle text-success fs-11">Available</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fs-11">Not Available</span>
                                        @endif
                                    </div>

                                    <div class="doc-img-container" onclick="openDocPreview('{{ $clinicDocument && $clinicDocument->doctor_sign && file_exists(public_path($clinicDocument->doctor_sign)) ? asset($clinicDocument->doctor_sign) : '' }}', 'Authorized Signature')">
                                        @if($clinicDocument && $clinicDocument->doctor_sign && file_exists(public_path($clinicDocument->doctor_sign)))
                                            <img src="{{ asset($clinicDocument->doctor_sign) }}" alt="Doctor Sign">
                                            <div class="doc-zoom-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                                        @else
                                            <div class="doc-empty-state">
                                                <i class="bi bi-pen fs-1 mb-1 opacity-50"></i>
                                                <span class="fs-12">No signature available</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3 bg-white mt-auto d-flex align-items-center justify-content-between">
                                        <small class="text-muted fs-11">Official Signature</small>
                                        @if($clinicDocument && $clinicDocument->doctor_sign && file_exists(public_path($clinicDocument->doctor_sign)))
                                            <button type="button" class="btn btn-sm btn-outline-info py-1 px-2.5 fs-12 rounded-pill" onclick="openDocPreview('{{ asset($clinicDocument->doctor_sign) }}', 'Authorized Signature')">
                                                <i class="bi bi-eye me-1"></i> View Full Image
                                            </button>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!$isCustomizePrescription)
                                {{-- Document 4: Prescription Header (Only for Fixed banner layout) --}}
                                <div class="col-lg-6 col-12">
                                    <div class="doc-preview-card">
                                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                            <span class="fw-bold text-dark fs-13">
                                                <i class="bi bi-layout-text-window-reverse text-warning me-1"></i> Prescription Letterhead Header
                                            </span>
                                            @if($clinicDocument && $clinicDocument->header && file_exists(public_path($clinicDocument->header)))
                                                <span class="badge bg-success-subtle text-success fs-11">Available</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary fs-11">Not Available</span>
                                            @endif
                                        </div>

                                        <div class="doc-img-container" style="height: 160px;" onclick="openDocPreview('{{ $clinicDocument && $clinicDocument->header && file_exists(public_path($clinicDocument->header)) ? asset($clinicDocument->header) : '' }}', 'Prescription Header Banner')">
                                            @if($clinicDocument && $clinicDocument->header && file_exists(public_path($clinicDocument->header)))
                                                <img src="{{ asset($clinicDocument->header) }}" alt="Header Banner" style="max-height: 140px; width: 95%;">
                                                <div class="doc-zoom-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                                            @else
                                                <div class="doc-empty-state">
                                                    <i class="bi bi-layout-text-window-reverse fs-1 mb-1 opacity-50"></i>
                                                    <span class="fs-12">No header banner available</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-3 bg-white mt-auto d-flex align-items-center justify-content-between">
                                            <small class="text-muted fs-11">Prescription Top Header</small>
                                            @if($clinicDocument && $clinicDocument->header && file_exists(public_path($clinicDocument->header)))
                                                <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2.5 fs-12 rounded-pill" onclick="openDocPreview('{{ asset($clinicDocument->header) }}', 'Prescription Header Banner')">
                                                    <i class="bi bi-eye me-1"></i> View Full Banner
                                                </button>
                                            @else
                                                <span class="text-muted fs-12">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Document 5: Prescription Footer (Only for Fixed banner layout) --}}
                                <div class="col-lg-6 col-12">
                                    <div class="doc-preview-card">
                                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                            <span class="fw-bold text-dark fs-13">
                                                <i class="bi bi-layout-text-window text-secondary me-1"></i> Prescription Letterhead Footer
                                            </span>
                                            @if($clinicDocument && $clinicDocument->footer && file_exists(public_path($clinicDocument->footer)))
                                                <span class="badge bg-success-subtle text-success fs-11">Available</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary fs-11">Not Available</span>
                                            @endif
                                        </div>

                                        <div class="doc-img-container" style="height: 160px;" onclick="openDocPreview('{{ $clinicDocument && $clinicDocument->footer && file_exists(public_path($clinicDocument->footer)) ? asset($clinicDocument->footer) : '' }}', 'Prescription Footer Banner')">
                                            @if($clinicDocument && $clinicDocument->footer && file_exists(public_path($clinicDocument->footer)))
                                                <img src="{{ asset($clinicDocument->footer) }}" alt="Footer Banner" style="max-height: 140px; width: 95%;">
                                                <div class="doc-zoom-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                                            @else
                                                <div class="doc-empty-state">
                                                    <i class="bi bi-layout-text-window fs-1 mb-1 opacity-50"></i>
                                                    <span class="fs-12">No footer banner available</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-3 bg-white mt-auto d-flex align-items-center justify-content-between">
                                            <small class="text-muted fs-11">Prescription Bottom Footer</small>
                                            @if($clinicDocument && $clinicDocument->footer && file_exists(public_path($clinicDocument->footer)))
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 fs-12 rounded-pill" onclick="openDocPreview('{{ asset($clinicDocument->footer) }}', 'Prescription Footer Banner')">
                                                    <i class="bi bi-eye me-1"></i> View Full Banner
                                                </button>
                                            @else
                                                <span class="text-muted fs-12">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Revisit Policy Quick Settings Card --}}
            <div class="col-12 mt-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-4 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1 fs-16">Patient Revisit &amp; Follow-up Fee Rule</h5>
                                <p class="text-muted fs-13 mb-0">
                                    Current Status: 
                                    @if($clinic->has_revisit_rule)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Enabled (Free Follow-up within {{ $clinic->revisit_validity_days ?? 7 }} Days)
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill fw-semibold">
                                            <i class="bi bi-x-circle-fill me-1"></i> Disabled (Standard Fees Apply Always)
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('clinic.revisit_rule') }}" class="btn btn-outline-primary px-4 fw-semibold rounded-3">
                            <i class="bi bi-sliders me-1"></i> Configure Revisit Rule
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Document Preview Lightbox Modal -->
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4">
                <h5 class="modal-title fs-15 fw-bold" id="docPreviewModalLabel">Document Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <img id="modalPreviewImg" src="" alt="Document Preview" class="img-fluid rounded shadow-sm" style="max-height: 500px; object-fit: contain;">
            </div>
            <div class="modal-footer bg-white py-2.5 px-4 border-top">
                <a id="modalDownloadBtn" href="" download class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                    <i class="bi bi-download me-1"></i> Download File
                </a>
                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDocPreview(imgUrl, title) {
    if (!imgUrl) return;
    document.getElementById('modalPreviewImg').src = imgUrl;
    document.getElementById('modalDownloadBtn').href = imgUrl;
    document.getElementById('docPreviewModalLabel').textContent = title || 'Document Preview';
    const modal = new bootstrap.Modal(document.getElementById('docPreviewModal'));
    modal.show();
}
</script>
@endsection
