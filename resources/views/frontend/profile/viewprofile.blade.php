@extends("frontend.include.layout")
@section('title', 'Doctor Profile & Credentials - Doctor Portal')

@section('content')
<style>
    /* ==========================================================================
       VIEW DOCTOR PROFILE STYLES
       ========================================================================== */
    .profile-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0369a1 100%);
        border-radius: 16px;
        padding: 2rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .profile-hero-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .doctor-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 20px;
        border: 4px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        background: #ffffff;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .doctor-avatar-wrapper:hover {
        transform: scale(1.04);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
    }

    .doctor-avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-zoom-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        background: rgba(15, 23, 42, 0.7);
        color: #ffffff;
        font-size: 10px;
        text-align: center;
        padding: 2px 0;
        backdrop-filter: blur(4px);
    }

    .valex-stat-box {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 12px 18px;
        text-align: center;
    }

    .valex-stat-box .stat-num {
        font-size: 22px;
        font-weight: 700;
        color: #38bdf8;
        line-height: 1.2;
    }

    .valex-stat-box .stat-lbl {
        font-size: 11.5px;
        color: #cbd5e1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Section Cards */
    .valex-profile-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .valex-profile-header {
        padding: 16px 22px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .valex-profile-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14.5px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0;
    }

    .info-group {
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        height: 100%;
        transition: border-color 0.2s;
    }

    .info-group:hover {
        border-color: #cbd5e1;
    }

    /* Tabular Documents */
    .valex-doc-table {
        margin: 0;
    }

    .valex-doc-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .valex-doc-table td {
        padding: 14px 18px;
        vertical-align: middle;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }

    .valex-doc-table tr:hover td {
        background-color: #f8fafc;
    }

    .btn-icon-action {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 34px !important;
        height: 34px !important;
        padding: 0 !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        line-height: 1 !important;
        transition: all 0.15s ease-in-out !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    }

    .btn-icon-action:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12) !important;
    }

    .doc-thumbnail-box {
        width: 80px;
        height: 52px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        padding: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .doc-thumbnail-box:hover {
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        transform: scale(1.05);
    }

    .doc-thumbnail-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .doc-thumbnail-box .zoom-icon-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .doc-thumbnail-box:hover .zoom-icon-overlay {
        opacity: 1;
    }

    /* Checkerboard background for transparent PNGs (stamps/signatures) */
    .checkerboard-bg {
        background-color: #ffffff;
        background-image: linear-gradient(45deg, #f1f5f9 25%, transparent 25%), 
                          linear-gradient(-45deg, #f1f5f9 25%, transparent 25%), 
                          linear-gradient(45deg, transparent 75%, #f1f5f9 75%), 
                          linear-gradient(-45deg, transparent 75%, #f1f5f9 75%);
        background-size: 16px 16px;
        background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
    }

    /* Modal Viewer */
    .doc-modal-preview-box {
        min-height: 320px;
        max-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-radius: 12px;
        overflow: auto;
    }

    .doc-modal-preview-box img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        {{-- Top Breadcrumb --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h4 class="mb-1 text-dark fw-bold">Doctor Profile</h4>
                <p class="text-muted fs-13 mb-0">View professional credentials. If any information is incorrect, submit a correction request to your clinic.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3.5 py-1.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#raiseCorrectionModal">
                    <i class="bi bi-pencil-square"></i> Request Profile Correction
                </button>
                <a href="{{ route('changepassword') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-semibold">
                    <i class="bi bi-key me-1"></i> Change Password
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 1. HERO DOCTOR BANNER --}}
        @php
            // Extract Doctor Profile Photo
            $photoPath = null;
            if (!empty($clinicDoc->photo) && file_exists(public_path($clinicDoc->photo))) {
                $photoPath = asset($clinicDoc->photo);
            } elseif (!empty($doctor->photoDocument->document_file) && file_exists(public_path($doctor->photoDocument->document_file))) {
                $photoPath = asset($doctor->photoDocument->document_file);
            } elseif (!empty($doctor->logo) && file_exists(public_path('upload/logo/' . $doctor->logo))) {
                $photoPath = asset('upload/logo/' . $doctor->logo);
            }
        @endphp

        <div class="profile-hero-card">
            <div class="row align-items-center g-4">
                
                {{-- Avatar --}}
                <div class="col-auto">
                    <div class="doctor-avatar-wrapper" onclick="{{ $photoPath ? "viewDocModal('Doctor Profile Photo', '{$photoPath}', 'Identity & Avatar', 'Official Doctor Avatar')" : '' }}" title="{{ $photoPath ? 'Click to view photo full size' : 'No photo uploaded' }}">
                        @if($photoPath)
                            <img src="{{ $photoPath }}" alt="{{ $doctor->name }}">
                            <div class="avatar-zoom-badge">
                                <i class="bi bi-zoom-in me-1"></i> Click to Zoom
                            </div>
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-primary text-white">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Name & Main Info --}}
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h3 class="fw-bold mb-0 text-white">{{ $doctor->name }}</h3>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold">
                            {{ $doctor->Doctor_Emp_id ?? 'DOC-N/A' }}
                        </span>
                        @if($doctor->status == 1 || $doctor->status === true)
                            <span class="badge bg-success px-2.5 py-1 rounded-pill fs-11 fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-patch-check-fill"></i> Active &amp; Verified
                            </span>
                        @else
                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fs-11 fw-bold">
                                <i class="bi bi-clock-history"></i> Verification Pending
                            </span>
                        @endif
                    </div>

                    <p class="text-info-emphasis fs-14 fw-semibold mb-2">
                        <i class="bi bi-mortarboard-fill me-1"></i> {{ $doctor->qualification ?? 'MBBS' }}
                        @if($doctor->specialization || $doctor->specialisation)
                            <span class="mx-1">•</span> <i class="bi bi-heart-pulse-fill me-1"></i> {{ $doctor->specialization ?? $doctor->specialisation }}
                        @endif
                    </p>

                    <div class="d-flex flex-wrap gap-3 text-slate-300 fs-13">
                        <span class="d-inline-flex align-items-center gap-1 text-light">
                            <i class="bi bi-hospital text-info"></i> {{ $doctor->clinic_name ?? 'Doctor Clinic' }}
                        </span>
                        <span class="d-inline-flex align-items-center gap-1 text-light">
                            <i class="bi bi-envelope text-info"></i> {{ $doctor->email }}
                        </span>
                        <span class="d-inline-flex align-items-center gap-1 text-light">
                            <i class="bi bi-telephone text-info"></i> {{ $doctor->phone ?? $doctor->Phone ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Stat Counters --}}
                <div class="col-xl-auto col-12">
                    <div class="d-flex flex-wrap gap-2 justify-content-xl-end">
                        <div class="valex-stat-box">
                            <div class="stat-num">{{ $totalPatients ?? 0 }}</div>
                            <div class="stat-lbl">Patients Treated</div>
                        </div>
                        <div class="valex-stat-box">
                            <div class="stat-num">{{ $totalPrescriptions ?? 0 }}</div>
                            <div class="stat-lbl">Rx Generated</div>
                        </div>
                        <div class="valex-stat-box">
                            <div class="stat-num text-warning">{{ $doctor->Experience ?? '5+' }}</div>
                            <div class="stat-lbl">Yrs Experience</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 2. PROFESSIONAL & CLINIC DETAILS GRID --}}
        <div class="valex-profile-card">
            <div class="valex-profile-header">
                <h5 class="valex-profile-title">
                    <i class="bi bi-person-lines-fill text-primary"></i> Doctor Identity &amp; Clinic Credentials
                </h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fs-12 fw-semibold">
                    Profile Data
                </span>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    
                    {{-- Doctor ID --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-person-badge text-primary me-1"></i> Doctor Emp ID</div>
                            <div class="info-value text-primary">{{ $doctor->Doctor_Emp_id ?? 'DOC000001' }}</div>
                        </div>
                    </div>

                    {{-- Full Name --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-person text-secondary me-1"></i> Full Name</div>
                            <div class="info-value">{{ $doctor->name }}</div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-envelope text-secondary me-1"></i> Email Address</span>
                                <small class="text-muted fs-11"><i class="bi bi-lock-fill"></i> Super Admin</small>
                            </div>
                            <div class="info-value">{{ $doctor->email }}</div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-telephone text-secondary me-1"></i> Contact Phone</div>
                            <div class="info-value">{{ $doctor->phone ?? $doctor->Phone ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Qualification --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-mortarboard text-secondary me-1"></i> Qualification</div>
                            <div class="info-value">{{ $doctor->qualification ?? 'MBBS' }}</div>
                        </div>
                    </div>

                    {{-- Specialization --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-heart-pulse text-secondary me-1"></i> Specialization</div>
                            <div class="info-value">{{ $doctor->specialization ?? $doctor->specialisation ?? 'General Physician' }}</div>
                        </div>
                    </div>

                    {{-- Clinic Name --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-hospital text-secondary me-1"></i> Clinic / Hospital Name</div>
                            <div class="info-value text-dark fw-bold">{{ $doctor->clinic_name ?? 'Doctor Clinic' }}</div>
                        </div>
                    </div>

                    {{-- Prescription Template Mode --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-file-earmark-medical text-secondary me-1"></i> Prescription Mode</div>
                            <div class="info-value">
                                @if($doctor->prescription_type === 'customize')
                                    <span class="badge bg-purple-transparent text-purple px-2.5 py-1 rounded-pill" style="color: #7928ca; background: rgba(121, 40, 202, 0.1);">
                                        <i class="bi bi-sliders me-1"></i> Layout 2 (Custom Stamp/Sign)
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-layout-text-window-reverse me-1"></i> Layout 1 (Fixed Banner)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Clinic Address --}}
                    <div class="col-xl-6 col-lg-8 col-md-12">
                        <div class="info-group">
                            <div class="info-label"><i class="bi bi-geo-alt text-secondary me-1"></i> Clinic Address</div>
                            <div class="info-value">{{ $doctor->clinic_address ?? 'Not specified' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. PROFILE CORRECTION REQUESTS HISTORY --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                <div>
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i> Profile Correction Requests to Clinic Admin
                    </h5>
                    <small class="text-muted">Track status of requests submitted to correct profile information</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#raiseCorrectionModal">
                    <i class="bi bi-plus-circle me-1"></i> New Request
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 950px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 150px;">Date &amp; Time</th>
                                <th style="width: 140px;">Field Name</th>
                                <th>What's Wrong ("Kya Galat Hai")</th>
                                <th>Requested ("Kya Hona Chahiye")</th>
                                <th>Reason / Note</th>
                                <th style="width: 110px;">Status</th>
                                <th>Clinic Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($correctionRequests ?? [] as $req)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark fs-13">{{ $req->created_at->format('d M Y') }}</div>
                                        <small class="text-muted fs-11">{{ $req->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-12 fw-semibold">
                                            {{ $req->field_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-danger fw-medium text-decoration-line-through fs-13">
                                            {{ $req->current_value ?: '(Empty / Unset)' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold fs-13">
                                            {{ $req->requested_value }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-12">{{ $req->reason ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($req->status === 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-circle-fill"></i> Approved
                                            </span>
                                        @elseif($req->status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-x-circle-fill"></i> Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-hourglass-split"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->admin_notes)
                                            <div class="text-dark fs-12 fw-medium">{{ $req->admin_notes }}</div>
                                            @if($req->reviewed_at)
                                                <small class="text-muted fs-10">Reviewed on {{ $req->reviewed_at->format('d M Y, h:i A') }}</small>
                                            @endif
                                        @elseif($req->status === 'pending')
                                            <span class="text-muted fs-12 fst-italic">Awaiting Clinic Admin Review</span>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-clipboard-check fs-2 text-muted opacity-50 d-block mb-1"></i>
                                        No correction requests submitted yet.
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

<!-- ==========================================================================
     RAISE PROFILE CORRECTION REQUEST MODAL
     ========================================================================== -->
<div class="modal fade" id="raiseCorrectionModal" tabindex="-1" aria-labelledby="raiseCorrectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('frontend.profile.correction_request') }}" method="POST" id="raiseCorrectionForm">
                @csrf
                <div class="modal-header bg-primary text-white py-3 px-4 border-0">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square fs-5 text-white"></i>
                        <h5 class="modal-title fw-bold text-white mb-0" id="raiseCorrectionModalLabel">Request Profile Correction</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted fs-12 mb-3">
                        Clinic Administration will verify your requested change and update your profile accordingly.
                    </p>

                    {{-- Field Selection --}}
                    <div class="mb-3">
                        <label for="req_field_name" class="form-label fw-semibold text-dark fs-13">Which Field is Incorrect? <span class="text-danger">*</span></label>
                        <select name="field_name" id="req_field_name" class="form-select" required>
                            <option value="" disabled selected>-- Select Field --</option>
                            <option value="name" data-val="{{ $doctor->name }}">Doctor Name</option>
                            <option value="phone" data-val="{{ $doctor->phone }}">Phone Number</option>
                            <option value="specialization" data-val="{{ $doctor->specialization }}">Specialization</option>
                            <option value="qualification" data-val="{{ $doctor->qualification }}">Qualification</option>
                            <option value="registration_number" data-val="{{ $doctor->registration_number }}">Registration / License No.</option>
                            <option value="clinic_name" data-val="{{ $doctor->clinic_name }}">Clinic Name</option>
                            <option value="clinic_address" data-val="{{ $doctor->clinic_address }}">Clinic Address</option>
                            <option value="other" data-val="">Other Information</option>
                        </select>
                        <small class="text-muted fs-11 mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Note: Email address changes require contacting Super Admin directly.</small>
                    </div>

                    {{-- Current Value (Kya Galat Hai) --}}
                    <div class="mb-3">
                        <label for="req_current_value" class="form-label fw-semibold text-dark fs-13">Current Value in Profile ("Kya Galat Hai")</label>
                        <input type="text" name="current_value" id="req_current_value" class="form-control bg-light" placeholder="Auto-filled or specify current value">
                    </div>

                    {{-- Requested Value (Kya Hona Chahiye) --}}
                    <div class="mb-3">
                        <label for="req_requested_value" class="form-label fw-semibold text-dark fs-13">Correct / Requested Value ("Kya Hona Chahiye") <span class="text-danger">*</span></label>
                        <input type="text" name="requested_value" id="req_requested_value" class="form-control" placeholder="Enter correct data..." required>
                    </div>

                    {{-- Reason / Details --}}
                    <div class="mb-2">
                        <label for="req_reason" class="form-label fw-semibold text-dark fs-13">Reason / Explanation <span class="text-danger">*</span></label>
                        <textarea name="reason" id="req_reason" rows="3" class="form-control" placeholder="Explain why this change is needed (e.g. typographical error, new qualification, updated phone)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-light border px-3 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var fieldSelect = document.getElementById('req_field_name');
    var currentValInput = document.getElementById('req_current_value');

    if (fieldSelect && currentValInput) {
        fieldSelect.addEventListener('change', function () {
            var selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
            var val = selectedOption.getAttribute('data-val');
            currentValInput.value = val || '';
        });
    }
});
</script>

@endsection
