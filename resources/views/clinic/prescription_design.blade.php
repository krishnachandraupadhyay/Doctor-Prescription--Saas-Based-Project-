@extends('clinic.include.layout')
@section('title', 'Prescription Design & Branding - Clinic Portal')

@section('content')
<style>
    .valex-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #EEF3F1;
        box-shadow: 0 4px 16px -4px rgba(15, 59, 56, 0.08);
        overflow: hidden;
    }
    .valex-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FAFCFB;
        border-bottom: 1px solid #EEF3F1;
        padding: 16px 20px;
    }
    .layout-preview-card {
        border-radius: 14px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        transition: all 0.25s ease;
        background: #f8fafc;
        position: relative;
    }
    .layout-preview-card.active-layout {
        border-color: #7b2ff7;
        box-shadow: 0 8px 24px -4px rgba(123, 47, 247, 0.2);
    }
    .layout-active-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(123, 47, 247, 0.35);
        z-index: 2;
    }
    .asset-preview-box {
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        padding: 16px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .asset-preview-box:hover {
        border-color: #7b2ff7;
        background: #faf5ff;
    }
    .asset-img-container {
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .asset-img-container:hover {
        transform: scale(1.02);
    }
    .stamp-container {
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }
    .header-footer-container {
        width: 100%;
        max-height: 140px;
        overflow: hidden;
    }
    .header-footer-container img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }
    .zoom-hint-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #7b2ff7;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        margin-top: 8px;
    }
    .zoom-hint-btn:hover {
        text-decoration: underline;
    }
    .spec-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #eef2f6;
    }
    .spec-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        {{-- Page Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    {{-- Left: Title & Layout Type --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                            <i class="bi bi-file-earmark-medical-fill"></i>
                        </div>
                        <div>
                            @php
                                $presType = strtolower($clinic->prescription_type ?? 'fixed');
                                $isFixed = ($presType !== 'customize');
                            @endphp
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Prescription Design &amp; Uploaded Assets</h4>
                                <span class="badge {{ $isFixed ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} px-2.5 py-0.5 rounded-pill fs-11 fw-bold">
                                    <i class="bi {{ $isFixed ? 'bi-layout-text-window-reverse' : 'bi-sliders' }} me-1"></i>
                                    {{ ucfirst($clinic->prescription_type ?? 'Fixed') }} Layout Active
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                Official prescription template format and uploaded graphical branding assets configured for <strong>{{ $clinic->name }}</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Clinic ID & Badge --}}
                    <div class="d-flex align-items-center gap-2">
                        <div class="px-3 py-2 bg-light border rounded-3 text-dark fs-12 fw-medium d-flex align-items-center gap-1.5">
                            <i class="bi bi-hospital text-primary"></i>
                            <span>{{ $clinic->clinic_id ?? 'CLN-'.$clinic->id }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">

            {{-- 1. SELECTED PRESCRIPTION DESIGN & TEMPLATE --}}
            <div class="col-xl-5">
                <div class="valex-card h-100">
                    <div class="valex-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-palette-fill text-primary fs-16"></i>
                            <h5 class="fw-bold text-dark mb-0 fs-15">Selected Prescription Format</h5>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-11 fw-semibold">
                            Layout Type
                        </span>
                    </div>
                    <div class="p-3 p-md-4">
                        
                        {{-- Active Layout Preview --}}
                        <div class="layout-preview-card active-layout mb-3">
                            <div class="layout-active-badge">
                                <i class="bi bi-check-circle-fill me-1"></i> ACTIVE TEMPLATE
                            </div>
                            
                            @if($isFixed)
                                <img src="{{ asset('prescription_type/layout1.jpg') }}" alt="Fixed Prescription Layout" class="img-fluid w-100 img-lightbox" data-img-src="{{ asset('prescription_type/layout1.jpg') }}" data-img-title="Fixed Prescription Layout Preview" style="cursor: pointer; max-height: 280px; object-fit: contain; background: #fff;">
                            @else
                                <img src="{{ asset('prescription_type/layout2.jpg') }}" alt="Customize Prescription Layout" class="img-fluid w-100 img-lightbox" data-img-src="{{ asset('prescription_type/layout2.jpg') }}" data-img-title="Customize Prescription Layout Preview" style="cursor: pointer; max-height: 280px; object-fit: contain; background: #fff;">
                            @endif

                            <div class="p-3 bg-white border-top">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="fw-bold text-dark mb-0 fs-15">
                                        {{ $isFixed ? 'Fixed Graphical Header & Footer Layout' : 'Customize / Letterhead Layout' }}
                                    </h6>
                                    <a href="javascript:void(0);" class="zoom-hint-btn img-lightbox" data-img-src="{{ $isFixed ? asset('prescription_type/layout1.jpg') : asset('prescription_type/layout2.jpg') }}" data-img-title="{{ $isFixed ? 'Fixed Layout Template' : 'Customize Layout Template' }}">
                                        <i class="bi bi-arrows-fullscreen"></i> View Full
                                    </a>
                                </div>
                                <p class="text-muted fs-12 mb-0">
                                    @if($isFixed)
                                        Prescription uses high-resolution graphical Header banner at the top, official Clinic Stamp, and Footer banner at the bottom.
                                    @else
                                        Prescription formats clinic and doctor typography directly on standard letterhead with official Clinic Stamp.
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Layout Specifications --}}
                        <div class="d-flex flex-column gap-2">
                            <div class="spec-item">
                                <div class="spec-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-layout-text-window"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark fs-13">Header Area</span>
                                    <small class="text-muted fs-12">
                                        {{ $isFixed ? 'Graphical custom header banner image (Width: 100%)' : 'Dynamic typography header with clinic name and address' }}
                                    </small>
                                </div>
                            </div>

                            <div class="spec-item">
                                <div class="spec-icon bg-info-subtle text-info">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark fs-13">Clinic Stamp</span>
                                    <small class="text-muted fs-12">Official round or oval seal placed on prescription authorization</small>
                                </div>
                            </div>

                            <div class="spec-item">
                                <div class="spec-icon bg-success-subtle text-success">
                                    <i class="bi bi-layout-text-window-reverse"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark fs-13">Footer Area</span>
                                    <small class="text-muted fs-12">
                                        {{ $isFixed ? 'Graphical footer banner image with contact & emergency info' : 'Standard signature line & clinic phone details' }}
                                    </small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2. UPLOADED BRANDING DATA & IMAGES --}}
            <div class="col-xl-7">
                <div class="valex-card h-100">
                    <div class="valex-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-images text-primary fs-16"></i>
                            <h5 class="fw-bold text-dark mb-0 fs-15">Uploaded Clinic Branding Assets</h5>
                        </div>
                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 fs-11 fw-semibold">
                            Uploaded Documents
                        </span>
                    </div>

                    <div class="p-3 p-md-4">

                        {{-- 1. Official Clinic Logo --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="fw-bold text-dark fs-14 mb-0">
                                    <i class="bi bi-image-fill text-primary me-1.5"></i> Official Clinic Logo
                                </label>
                                @if($document && $document->photo && file_exists(public_path($document->photo)))
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Not Uploaded
                                    </span>
                                @endif
                            </div>

                            <div class="asset-preview-box">
                                @if($document && $document->photo && file_exists(public_path($document->photo)))
                                    <div class="asset-img-container stamp-container shadow-xs img-lightbox" data-img-src="{{ asset($document->photo) }}" data-img-title="Official Clinic Logo - {{ $clinic->name }}">
                                        <img src="{{ asset($document->photo) }}" alt="Clinic Logo" class="img-fluid p-2" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                    </div>
                                    <a href="javascript:void(0);" class="zoom-hint-btn img-lightbox" data-img-src="{{ asset($document->photo) }}" data-img-title="Official Clinic Logo">
                                        <i class="bi bi-zoom-in"></i> Click to Zoom Logo
                                    </a>
                                @else
                                    <div class="py-3 text-muted">
                                        <i class="bi bi-image text-muted opacity-50" style="font-size: 36px;"></i>
                                        <p class="fs-13 mb-0 mt-1">Clinic Logo image has not been uploaded yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 2. Official Clinic Stamp --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="fw-bold text-dark fs-14 mb-0">
                                    <i class="bi bi-award-fill text-primary me-1.5"></i> Official Clinic Stamp
                                </label>
                                @if($document && $document->clinic_stamp && file_exists(public_path($document->clinic_stamp)))
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Not Uploaded
                                    </span>
                                @endif
                            </div>

                            <div class="asset-preview-box">
                                @if($document && $document->clinic_stamp && file_exists(public_path($document->clinic_stamp)))
                                    <div class="asset-img-container stamp-container shadow-xs img-lightbox" data-img-src="{{ asset($document->clinic_stamp) }}" data-img-title="Official Clinic Stamp - {{ $clinic->name }}">
                                        <img src="{{ asset($document->clinic_stamp) }}" alt="Clinic Stamp" class="img-fluid p-2" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                    </div>
                                    <a href="javascript:void(0);" class="zoom-hint-btn img-lightbox" data-img-src="{{ asset($document->clinic_stamp) }}" data-img-title="Official Clinic Stamp">
                                        <i class="bi bi-zoom-in"></i> Click to Zoom Stamp
                                    </a>
                                @else
                                    <div class="py-3 text-muted">
                                        <i class="bi bi-award text-muted opacity-50" style="font-size: 36px;"></i>
                                        <p class="fs-13 mb-0 mt-1">Clinic Stamp image has not been uploaded yet by onboarding team.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- If Fixed Layout: Show Header & Footer Images --}}
                        @if($isFixed)
                            {{-- 2. Header Banner --}}
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="fw-bold text-dark fs-14 mb-0">
                                        <i class="bi bi-layout-top text-primary me-1.5"></i> Prescription Header Banner
                                    </label>
                                    @if($document && $document->header && file_exists(public_path($document->header)))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> Not Uploaded
                                        </span>
                                    @endif
                                </div>

                                <div class="asset-preview-box">
                                    @if($document && $document->header && file_exists(public_path($document->header)))
                                        <div class="asset-img-container header-footer-container shadow-xs img-lightbox" data-img-src="{{ asset($document->header) }}" data-img-title="Prescription Header Banner - {{ $clinic->name }}">
                                            <img src="{{ asset($document->header) }}" alt="Header Banner" class="img-fluid">
                                        </div>
                                        <a href="javascript:void(0);" class="zoom-hint-btn img-lightbox" data-img-src="{{ asset($document->header) }}" data-img-title="Prescription Header Banner">
                                            <i class="bi bi-zoom-in"></i> Click to Zoom Header Banner
                                        </a>
                                    @else
                                        <div class="py-3 text-muted">
                                            <i class="bi bi-layout-top text-muted opacity-50" style="font-size: 36px;"></i>
                                            <p class="fs-13 mb-0 mt-1">Header Banner has not been uploaded yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Footer Banner --}}
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="fw-bold text-dark fs-14 mb-0">
                                        <i class="bi bi-layout-bottom text-primary me-1.5"></i> Prescription Footer Banner
                                    </label>
                                    @if($document && $document->footer && file_exists(public_path($document->footer)))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-0.5 fs-11 fw-semibold">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> Not Uploaded
                                        </span>
                                    @endif
                                </div>

                                <div class="asset-preview-box">
                                    @if($document && $document->footer && file_exists(public_path($document->footer)))
                                        <div class="asset-img-container header-footer-container shadow-xs img-lightbox" data-img-src="{{ asset($document->footer) }}" data-img-title="Prescription Footer Banner - {{ $clinic->name }}">
                                            <img src="{{ asset($document->footer) }}" alt="Footer Banner" class="img-fluid">
                                        </div>
                                        <a href="javascript:void(0);" class="zoom-hint-btn img-lightbox" data-img-src="{{ asset($document->footer) }}" data-img-title="Prescription Footer Banner">
                                            <i class="bi bi-zoom-in"></i> Click to Zoom Footer Banner
                                        </a>
                                    @else
                                        <div class="py-3 text-muted">
                                            <i class="bi bi-layout-bottom text-muted opacity-50" style="font-size: 36px;"></i>
                                            <p class="fs-13 mb-0 mt-1">Footer Banner has not been uploaded yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- 3. CLINIC DOCTORS & SIGNATURE ASSETS --}}
        <div class="valex-card mb-4">
            <div class="valex-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pen-fill text-primary fs-16"></i>
                    <h5 class="fw-bold text-dark mb-0 fs-15">Clinic Doctors &amp; Authorization Signatures</h5>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 fs-12 fw-semibold">
                    Total Doctors: {{ count($doctors) }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table valex-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Doctor Name</th>
                            <th>Doctor ID</th>
                            <th>Specialization</th>
                            <th class="text-center" style="width: 140px;">Doctor Photo</th>
                            <th class="text-center" style="width: 160px;">Signature Asset</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doc)
                            @php
                                $docDoc = $doc->doctor_clinic_documents;
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="d-block fw-bold text-dark fs-14">Dr. {{ $doc->name }}</span>
                                    <small class="text-muted">{{ $doc->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-11 fw-semibold">{{ $doc->Doctor_Emp_id }}</span>
                                </td>
                                <td>
                                    <span class="text-secondary fs-13">{{ $doc->specialization ?? 'General Physician' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($docDoc && $docDoc->photo && file_exists(public_path($docDoc->photo)))
                                        <div class="asset-img-container rounded-circle mx-auto img-lightbox" style="width: 44px; height: 44px;" data-img-src="{{ asset($docDoc->photo) }}" data-img-title="Dr. {{ $doc->name }} - Photo">
                                            <img src="{{ asset($docDoc->photo) }}" alt="Doctor Photo" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @elseif($doc->image && file_exists(storage_path('app/public/'.$doc->image)))
                                        <div class="asset-img-container rounded-circle mx-auto img-lightbox" style="width: 44px; height: 44px;" data-img-src="{{ asset('storage/'.$doc->image) }}" data-img-title="Dr. {{ $doc->name }} - Photo">
                                            <img src="{{ asset('storage/'.$doc->image) }}" alt="Doctor Photo" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <span class="text-muted fs-12 fst-italic">No photo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($docDoc && $docDoc->doctor_sign && file_exists(public_path($docDoc->doctor_sign)))
                                        <div class="asset-img-container mx-auto img-lightbox shadow-xs p-1" style="width: 110px; height: 45px;" data-img-src="{{ asset($docDoc->doctor_sign) }}" data-img-title="Dr. {{ $doc->name }} - Signature">
                                            <img src="{{ asset($docDoc->doctor_sign) }}" alt="Doctor Sign" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </div>
                                    @else
                                        <span class="text-muted fs-12 fst-italic">No sign uploaded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($doc->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-11 fw-semibold">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-11 fw-semibold">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No doctors registered under this clinic yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>

<!-- ================= Fullscreen Image Lightbox Modal ================= -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg bg-dark" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header border-0 py-2 px-3 bg-black bg-opacity-50">
                <h6 class="modal-title text-white fs-14 fw-semibold" id="lightboxTitle">Image Preview</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 d-flex align-items-center justify-content-center" style="min-height: 350px;">
                <img src="" id="lightboxImage" class="img-fluid rounded shadow-sm" style="max-height: 75vh; max-width: 100%; object-fit: contain;" alt="Lightbox Preview">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const lightboxModalEl = document.getElementById('imageLightboxModal');
    const lightboxModal   = new bootstrap.Modal(lightboxModalEl);
    const lightboxImage   = document.getElementById('lightboxImage');
    const lightboxTitle   = document.getElementById('lightboxTitle');

    document.querySelectorAll('.img-lightbox').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            const src   = this.getAttribute('data-img-src');
            const title = this.getAttribute('data-img-title') || 'Image Preview';

            if (src) {
                lightboxImage.src = src;
                lightboxTitle.textContent = title;
                lightboxModal.show();
            }
        });
    });
});
</script>
@endsection
