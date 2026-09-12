@extends((Auth::check() && Auth::user()->role === 'admin') ? 'backend.include.layout' : 'middleend.include.layout')
@section('title', (Auth::check() && Auth::user()->role === 'admin') ? 'Clinic Permissions - Admin Portal' : 'Clinic Permissions - Onboarding Portal')

@section('content')
<style>
    .permission-header-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        padding: 1.4rem 1.6rem;
    }
    .permission-stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #e9edf4;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .permission-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
    }
    .permission-table-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        padding: 1.4rem 1.6rem;
    }
    .clinic-perm-table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        font-weight: 700;
        border-bottom: 2px solid #f1f1f1 !important;
        white-space: nowrap;
        padding: 14px 12px;
    }
    .clinic-perm-table td {
        vertical-align: middle;
        padding: 14px 12px;
        color: #374151;
        font-size: 0.9rem;
    }
    .member-id-badge {
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }
    .btn-toggle-mini {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        padding: 4px 12px !important;
        height: 26px !important;
        line-height: 1 !important;
        border-radius: 50rem !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out !important;
        text-decoration: none !important;
    }
    .btn-toggle-mini i {
        font-size: 12px !important;
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
    .btn-verify-mini {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        padding: 4px 12px !important;
        height: 26px !important;
        line-height: 1 !important;
        border-radius: 50rem !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out !important;
        text-decoration: none !important;
    }
    .btn-verify-mini i {
        font-size: 12px !important;
        margin-right: 4px !important;
        line-height: 1 !important;
    }
    .btn-verify-mini.is-verified {
        background-color: #0ab39c !important;
        border: 1px solid #0ab39c !important;
        color: #ffffff !important;
    }
    .btn-verify-mini.is-verified:hover {
        background-color: #099885 !important;
        border-color: #099885 !important;
        color: #ffffff !important;
    }
    .btn-verify-mini.is-unverified {
        background-color: #fff3cd !important;
        border: 1px solid #ffe69c !important;
        color: #997404 !important;
    }
    .btn-verify-mini.is-unverified:hover {
        background-color: #ffe69c !important;
        border-color: #ffc107 !important;
        color: #664d03 !important;
    }
    .clinic-avatar-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    /* Searchable Clinic Dropdown */
    .clinic-search-dropdown {
        position: relative;
        min-width: 330px;
    }
    .clinic-search-trigger {
        background: #f8fafc;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .clinic-search-trigger:hover, .clinic-search-trigger.active {
        border-color: #3b82f6;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .clinic-search-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border: 1px solid #e5e7eb;
        padding: 8px;
        z-index: 9999;
        display: none;
        max-height: 340px;
    }
    .clinic-search-menu.show {
        display: block;
    }
    .clinic-search-input-box {
        position: relative;
        margin-bottom: 8px;
    }
    .clinic-search-input-box i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    .clinic-search-input {
        width: 100%;
        padding: 7px 10px 7px 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        background: #f8fafc;
        outline: none;
    }
    .clinic-search-input:focus {
        border-color: #3b82f6;
        background: #ffffff;
    }
    .clinic-options-list {
        max-height: 240px;
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .clinic-option-item {
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #374151;
        transition: background 0.15s ease;
    }
    .clinic-option-item:hover, .clinic-option-item.selected {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .clinic-option-item.selected {
        font-weight: 700;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Flash Alerts -->
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

        <!-- Page Header -->
        <div class="permission-header-card mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock-fill text-primary"></i>
                        Hospital &amp; Clinic Permissions Control Center
                    </h4>
                    <p class="text-muted fs-13 mb-0">
                        Manage login verification and module visibility (Staff, Payment Category, Deleted Staff) for each hospital/clinic.
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('clinics.index') }}" class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold shadow-sm d-flex align-items-center gap-1.5">
                        <i class="bi bi-hospital"></i> Manage Clinics Directory
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="permission-stat-card">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center fs-22" style="width: 48px; height: 48px;">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-12 fw-semibold text-uppercase">Total Clinics</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalClinics ?? count($clinics) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="permission-stat-card">
                    <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center fs-22" style="width: 48px; height: 48px;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-12 fw-semibold text-uppercase">Verified Clinics</div>
                        <h4 class="fw-bold mb-0 text-success">{{ $verifiedCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="permission-stat-card">
                    <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center fs-22" style="width: 48px; height: 48px;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-12 fw-semibold text-uppercase">Staff Module Active</div>
                        <h4 class="fw-bold mb-0 text-info">{{ $staffModuleCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="permission-stat-card">
                    <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center fs-22" style="width: 48px; height: 48px;">
                        <i class="bi bi-credit-card-2-front-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-12 fw-semibold text-uppercase">Payment Category Active</div>
                        <h4 class="fw-bold mb-0 text-warning">{{ $paymentModuleCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="permission-table-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-toggles text-primary"></i> Clinic Permissions Matrix
                    </h5>
                    <small class="text-muted">Click on any badge below to instantly toggle permissions in real-time</small>
                </div>
                
                <!-- Search Form with Searchable Dropdown -->
                <form action="{{ route('clinics.permissions') }}" method="GET" id="clinicFilterForm" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="clinic_id" id="selectedClinicIdInput" value="{{ request('clinic_id') }}">

                    <div class="clinic-search-dropdown" id="clinicSearchDropdown">
                        <button type="button" class="clinic-search-trigger" id="clinicSearchTrigger">
                            <span class="d-flex align-items-center gap-2 text-truncate" id="selectedClinicDisplay">
                                <i class="bi bi-hospital text-primary"></i>
                                @php
                                    $selectedClinic = null;
                                    if(request('clinic_id') && isset($allClinicsList)) {
                                        $selectedClinic = $allClinicsList->firstWhere('id', request('clinic_id'));
                                    }
                                @endphp
                                @if($selectedClinic)
                                    <span class="fw-bold text-dark text-truncate">{{ $selectedClinic->name }}</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1.5 py-0.5 fs-10">{{ $selectedClinic->clinic_id }}</span>
                                @else
                                    <span class="text-muted">Select / Search Hospital or Clinic...</span>
                                @endif
                            </span>
                            <i class="bi bi-chevron-down text-muted fs-11 ms-2"></i>
                        </button>

                        <div class="clinic-search-menu" id="clinicSearchMenu">
                            <div class="clinic-search-input-box">
                                <i class="bi bi-search"></i>
                                <input type="text" class="clinic-search-input" id="clinicSearchInput" placeholder="Type clinic name, ID or code..." autocomplete="off">
                            </div>
                            <ul class="clinic-options-list" id="clinicOptionsList">
                                <li class="clinic-option-item {{ !request('clinic_id') ? 'selected' : '' }}" data-id="" data-name="All Clinics / Hospitals">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-grid-fill text-muted"></i>
                                        <span>All Clinics / Hospitals</span>
                                    </div>
                                    <span class="badge bg-light text-secondary border fs-10">Show All</span>
                                </li>
                                @foreach($allClinicsList ?? [] as $cl)
                                    <li class="clinic-option-item {{ request('clinic_id') == $cl->id ? 'selected' : '' }}" 
                                        data-id="{{ $cl->id }}" 
                                        data-name="{{ $cl->name }}"
                                        data-cid="{{ $cl->clinic_id }}"
                                        data-code="{{ $cl->clinic_code }}">
                                        <div class="d-flex align-items-center gap-2 text-truncate">
                                            <i class="bi bi-hospital-fill text-primary"></i>
                                            <span class="text-truncate">{{ $cl->name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                            <span class="badge bg-light text-dark border fs-10">{{ $cl->clinic_id }}</span>
                                            @if($cl->clinic_code)
                                                <span class="badge bg-secondary-subtle text-secondary fs-10">{{ $cl->clinic_code }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if(request('clinic_id') || request('search'))
                        <a href="{{ route('clinics.permissions') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1.5 rounded-3 d-flex align-items-center gap-1" title="Clear Filter">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="tabular-data table-responsive">
                <table class="table table-hover clinic-perm-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 130px;">CLINIC ID</th>
                            <th>CLINIC / HOSPITAL NAME</th>
                            <th class="text-center" style="width: 140px;">VERIFICATION</th>
                            <th class="text-center" style="width: 135px;">STAFF MODULE</th>
                            <th class="text-center" style="width: 150px;">PAYMENT CATEGORY</th>
                            <th class="text-center" style="width: 135px;">DELETED STAFF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clinics as $clinic)
                        @php
                            $hasMem = $clinic->has_member ?? true;
                            $hasPay = $clinic->has_payment_category ?? false;
                            $hasDelStaff = $clinic->has_deleted_staff ?? false;
                            $isVerified = $clinic->verified ?? false;
                            $initials = strtoupper(substr($clinic->name ?? 'C', 0, 2));
                        @endphp
                        <tr>
                            <td>
                                <span class="member-id-badge">{{ $clinic->clinic_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="clinic-avatar-box">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-13 d-flex align-items-center gap-2">
                                            {{ $clinic->name }}
                                            @if($clinic->clinic_code)
                                                <span class="badge bg-light text-secondary border px-1.5 py-0.5 fs-10">{{ $clinic->clinic_code }}</span>
                                            @endif
                                        </div>
                                        <small class="text-muted fs-11"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($clinic->address, 40) }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- 1. Verification Toggle -->
                            <td class="text-center" id="clinic-verify-col-{{ $clinic->id }}">
                                <button type="button" 
                                         class="btn-verify-mini {{ $isVerified ? 'is-verified' : 'is-unverified' }} clinic-verify-toggle-btn"
                                         data-id="{{ $clinic->id }}"
                                         data-current="{{ $isVerified ? 1 : 0 }}"
                                         title="{{ $isVerified ? 'Verified by ' . ($clinic->verified_by ?? 'Super Admin / Onboarding') . ($clinic->verified_at ? ' (' . \Carbon\Carbon::parse($clinic->verified_at)->format('d M Y') . ')' : '') . '. Click to toggle.' : 'Pending verification. Click to verify.' }}">
                                    @if($isVerified)
                                        <i class="bi bi-patch-check-fill"></i><span>Verified</span>
                                    @else
                                        <i class="bi bi-hourglass-split"></i><span>Unverified</span>
                                    @endif
                                </button>
                            </td>

                            <!-- 2. Staff Module Toggle -->
                            <td class="text-center" id="clinic-status-col-{{ $clinic->id }}">
                                <button type="button" 
                                         class="btn-toggle-mini {{ $hasMem ? 'is-visible' : 'is-hidden' }} clinic-member-toggle-btn"
                                         data-id="{{ $clinic->id }}"
                                         data-current="{{ $hasMem ? 1 : 0 }}"
                                         title="Click to toggle staff module visibility for this clinic">
                                    @if($hasMem)
                                        <i class="bi bi-eye-fill"></i><span>Visible</span>
                                    @else
                                        <i class="bi bi-eye-slash-fill"></i><span>Hidden</span>
                                    @endif
                                </button>
                            </td>

                            <!-- 3. Payment Category Toggle -->
                            <td class="text-center" id="clinic-pay-col-{{ $clinic->id }}">
                                <button type="button" 
                                         class="btn-toggle-mini {{ $hasPay ? 'is-visible' : 'is-hidden' }} clinic-pay-toggle-btn"
                                         data-id="{{ $clinic->id }}"
                                         data-current="{{ $hasPay ? 1 : 0 }}"
                                         title="Click to toggle payment category visibility for this clinic">
                                    @if($hasPay)
                                        <i class="bi bi-eye-fill"></i><span>Visible</span>
                                    @else
                                        <i class="bi bi-eye-slash-fill"></i><span>Hidden</span>
                                    @endif
                                </button>
                            </td>

                            <!-- 4. Deleted Staff Toggle -->
                            <td class="text-center" id="clinic-delstaff-col-{{ $clinic->id }}">
                                <button type="button" 
                                         class="btn-toggle-mini {{ $hasDelStaff ? 'is-visible' : 'is-hidden' }} clinic-delstaff-toggle-btn"
                                         data-id="{{ $clinic->id }}"
                                         data-current="{{ $hasDelStaff ? 1 : 0 }}"
                                         title="Click to toggle deleted staff visibility for this clinic">
                                    @if($hasDelStaff)
                                        <i class="bi bi-eye-fill"></i><span>Visible</span>
                                    @else
                                        <i class="bi bi-eye-slash-fill"></i><span>Hidden</span>
                                    @endif
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-hospital fs-1 d-block mb-2 text-muted opacity-50"></i>
                                No clinics found. Click <b><a href="{{ route('clinics.index') }}">Manage Clinics</a></b> to add a clinic.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($clinics, 'hasPages') && $clinics->hasPages())
            <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between">
                <div class="text-muted fs-13">
                    Showing {{ $clinics->firstItem() }} to {{ $clinics->lastItem() }} of {{ $clinics->total() }} clinics
                </div>
                <div>
                    {{ $clinics->links() }}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]') 
            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            : '{{ csrf_token() }}';

        function showNotification(message, isSuccess = true) {
            let toast = document.getElementById('ajaxToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'ajaxToast';
                toast.style.position = 'fixed';
                toast.style.bottom = '20px';
                toast.style.right = '20px';
                toast.style.zIndex = '99999';
                toast.style.minWidth = '280px';
                toast.style.padding = '12px 18px';
                toast.style.borderRadius = '10px';
                toast.style.color = '#fff';
                toast.style.fontSize = '13.5px';
                toast.style.fontWeight = '600';
                toast.style.boxShadow = '0 6px 20px rgba(0,0,0,0.15)';
                toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                document.body.appendChild(toast);
            }
            toast.style.backgroundColor = isSuccess ? '#0ab39c' : '#f06548';
            toast.innerHTML = (isSuccess ? '<i class="bi bi-check-circle-fill me-1.5"></i> ' : '<i class="bi bi-exclamation-triangle-fill me-1.5"></i> ') + message;
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
            }, 3000);
        }

        // 1. Clinic Verification Toggle
        document.querySelectorAll('.clinic-verify-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinics/${clinicId}/toggle-verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.verified) {
                            button.className = 'btn-verify-mini is-verified clinic-verify-toggle-btn';
                            button.innerHTML = '<i class="bi bi-patch-check-fill"></i><span>Verified</span>';
                            button.setAttribute('data-current', '1');
                            button.setAttribute('title', 'Verified by ' + (data.verified_by || 'Super Admin / Onboarding') + '. Click to toggle.');
                        } else {
                            button.className = 'btn-verify-mini is-unverified clinic-verify-toggle-btn';
                            button.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Unverified</span>';
                            button.setAttribute('data-current', '0');
                            button.setAttribute('title', 'Pending verification. Click to verify.');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Verification update failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 2. Clinic Staff Module toggle
        document.querySelectorAll('.clinic-member-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-member-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_member) {
                            button.className = 'btn-toggle-mini is-visible clinic-member-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-member-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 3. Clinic Payment Category toggle
        document.querySelectorAll('.clinic-pay-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-payment-category-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_payment_category) {
                            button.className = 'btn-toggle-mini is-visible clinic-pay-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-pay-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 4. Clinic Deleted Staff toggle
        document.querySelectorAll('.clinic-delstaff-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-deleted-staff-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_deleted_staff) {
                            button.className = 'btn-toggle-mini is-visible clinic-delstaff-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-delstaff-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // Searchable Clinic Dropdown Logic
        const trigger = document.getElementById('clinicSearchTrigger');
        const menu = document.getElementById('clinicSearchMenu');
        const searchInput = document.getElementById('clinicSearchInput');
        const optionsList = document.getElementById('clinicOptionsList');
        const hiddenInput = document.getElementById('selectedClinicIdInput');
        const form = document.getElementById('clinicFilterForm');

        if (trigger && menu) {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('show');
                trigger.classList.toggle('active');
                if (menu.classList.contains('show')) {
                    searchInput.value = '';
                    filterOptions('');
                    setTimeout(() => searchInput.focus(), 100);
                }
            });

            document.addEventListener('click', function (e) {
                if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('show');
                    trigger.classList.remove('active');
                }
            });

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();
                    filterOptions(query);
                });
            }

            function filterOptions(query) {
                const items = optionsList.querySelectorAll('.clinic-option-item');
                items.forEach(item => {
                    const name = (item.getAttribute('data-name') || '').toLowerCase();
                    const cid = (item.getAttribute('data-cid') || '').toLowerCase();
                    const code = (item.getAttribute('data-code') || '').toLowerCase();
                    if (!query || name.includes(query) || cid.includes(query) || code.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            optionsList.querySelectorAll('.clinic-option-item').forEach(item => {
                item.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    hiddenInput.value = id;
                    menu.classList.remove('show');
                    form.submit();
                });
            });
        }
    });
</script>
@endsection
