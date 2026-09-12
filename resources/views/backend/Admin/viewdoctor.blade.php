@extends('backend.include.layout')

@section('title', 'Doctor Profile & Documents')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Doctor Profile & Total Data
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">{{ $doctor->Doctor_Emp_id ?? 'DOC-'.$doctor->id }}</span> &bull; Detailed clinic, statistical overview & document records
                </p>
            </div>
            <div>
                <a href="{{ url('/adddoctor') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Doctors List
                </a>
            </div>
        </div>

        @php
            $isDoctorVerified = ($doctor->Verified == 1 || $doctor->verified == 1);
            $memberList = isset($members) ? $members : collect();
            $receptCount = $memberList->where('role', 'receptionist')->count();
            $staffMemberCount = $memberList->where('role', 'staff')->count();
        @endphp

        <!-- Doctor Details Card (Doctor Comprehensive Profile) -->
        <div class="row g-4 mb-4">
            <div class="col-xl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-0">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <span class="text-uppercase text-muted fw-bold fs-12 letter-spacing-1">
                            <i class="bi bi-person-badge me-1 text-primary"></i> Doctor Comprehensive Profile
                        </span>
                        <div>
                            @if(!$isDoctorVerified)
                                <a href="{{ route('verifydoctor', $doctor->id) }}" class="btn btn-sm btn-success rounded-pill px-3.5 py-1.5 fw-semibold shadow-xs">
                                    <i class="bi bi-check-circle me-1"></i> Verify Doctor
                                </a>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-12 rounded-pill fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified Doctor
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @php
                            $docPhoto = null;
                            if ($doctor->photoDocument && $doctor->photoDocument->document_file) {
                                $docPhoto = asset($doctor->photoDocument->document_file);
                            } elseif ($clinicDoc && $clinicDoc->photo) {
                                $docPhoto = asset($clinicDoc->photo);
                            } elseif ($doctor->logo) {
                                $docPhoto = asset('upload/logo/'.$doctor->logo);
                            }
                            $initials = strtoupper(substr($doctor->name ?? 'Dr', 0, 2));
                        @endphp

                        <!-- Doctor Bio Header -->
                        <div class="d-flex flex-wrap align-items-center gap-4 mb-4 pb-4 border-bottom">
                            @if($docPhoto)
                                <img src="{{ $docPhoto }}"
                                     alt="Dr. {{ $doctor->name }}"
                                     class="rounded-circle shadow-sm object-fit-cover border border-3 border-light"
                                     width="84" height="84"
                                     onerror="this.style.display='none'; document.getElementById('fallback-avatar').style.display='flex';">
                                <div id="fallback-avatar" class="bg-primary-subtle text-primary rounded-circle fs-2 fw-bold align-items-center justify-content-center" style="display:none; width:84px; height:84px;">
                                    {{ $initials }}
                                </div>
                            @else
                                <div class="bg-primary-subtle text-primary rounded-circle fs-2 fw-bold d-flex align-items-center justify-content-center" style="width:84px; height:84px;">
                                    {{ $initials }}
                                </div>
                            @endif

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h3 class="mb-0 fw-bold text-dark">Dr. {{ $doctor->name }}</h3>
                                    @if(($doctor->Verified ?? 0) == 1)
                                        <i class="bi bi-patch-check-fill text-primary fs-5" title="Verified Doctor"></i>
                                    @endif
                                </div>
                                <p class="text-primary fw-medium mb-1 fs-15">
                                    {{ $doctor->specialization ?? 'General Physician' }}
                                    @if($doctor->qualification)
                                        <span class="text-muted fw-normal">&bull; {{ $doctor->qualification }}</span>
                                    @endif
                                </p>
                                <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                                    <span class="badge bg-light text-dark border fs-11 px-2.5 py-1.5">
                                        Member ID: <strong>{{ $doctor->Doctor_Emp_id ?? 'DOC-'.str_pad($doctor->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                    </span>
                                    @if($doctor->Experience)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle fs-11 px-2.5 py-1.5">
                                            <i class="bi bi-briefcase me-1"></i> {{ $doctor->Experience }} Experience
                                        </span>
                                    @endif
                                    @if($doctor->created_by)
                                        <span class="badge bg-secondary-subtle text-secondary fs-11 px-2.5 py-1.5">
                                            <i class="bi bi-person-gear me-1"></i> Onboarded By: {{ $doctor->creatorName ?? $doctor->created_by }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Details Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Hospital / Clinic Name</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-hospital me-1 text-primary"></i> {{ $doctor->clinic_name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Email Address</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-envelope me-1 text-info"></i> {{ $doctor->email ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Contact Phone</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-telephone me-1 text-success"></i> {{ $doctor->phone ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Registration / License No.</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-card-heading me-1 text-warning"></i> {{ $doctor->registration_number ?? $doctor->license_number ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Clinic Address</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $doctor->clinic_address ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block mb-1 fs-11 fw-semibold text-uppercase">Member Registered Date</small>
                                    <span class="fw-bold text-dark fs-14"><i class="bi bi-calendar-event me-1 text-purple"></i> {{ $doctor->created_at ? $doctor->created_at->format('d M, Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        @if(count($paymentCategories) > 0)
                            <!-- Consultation Fees Table / Badges -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tags me-1 text-primary"></i> Consultation & Payment Categories</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Fee Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paymentCategories as $category)
                                                <tr>
                                                    <td class="fw-medium text-dark">{{ $category->name ?? $category->category_name ?? 'Category' }}</td>
                                                    <td class="fw-bold text-success">₹{{ number_format($category->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- KPI / Total Data Summary Cards (3 Cards per Row, 2 Lines) -->
        <div class="row g-3 mb-4">
            <!-- Total Patients -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border border-primary-subtle shadow-sm rounded-4 h-100 overflow-hidden" style="background: #edf5ff !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-primary fw-bold text-uppercase fs-12">Total Patients</span>
                            <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-people-fill fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-2">{{ number_format($totalPatients) }}</h2>
                        <p class="mb-0 text-muted fs-12"><i class="bi bi-person-check me-1 text-primary"></i> Under Dr. {{ $doctor->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Prescriptions -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100 overflow-hidden" style="background: #ebfaf0 !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-success fw-bold text-uppercase fs-12">Prescriptions</span>
                            <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-file-earmark-medical-fill fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-2">{{ number_format($totalPrescriptions) }}</h2>
                        <p class="mb-0 text-muted fs-12"><i class="bi bi-journal-medical me-1 text-success"></i> Consultations Done</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue / Total Earnings -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border border-warning-subtle shadow-sm rounded-4 h-100 overflow-hidden" style="background: #fffdf0 !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-warning fw-bold text-uppercase fs-12">Total Earnings</span>
                            <div class="bg-warning text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-currency-rupee fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-2">₹{{ number_format($totalRevenue ?? 0, 2) }}</h2>
                        <p class="mb-0 text-muted fs-12"><i class="bi bi-cash-coin me-1 text-warning"></i> Total Revenue Earned</p>
                    </div>
                </div>
            </div>

            <!-- Fee Categories -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border border-info-subtle shadow-sm rounded-4 h-100 overflow-hidden" style="background: #e8f9fd !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-info fw-bold text-uppercase fs-12">Fee Categories</span>
                            <div class="bg-info text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-cash-stack fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-2">{{ number_format(count($paymentCategories)) }}</h2>
                        <p class="mb-0 text-muted fs-12"><i class="bi bi-tags me-1 text-info"></i> Active Pricing Plans</p>
                    </div>
                </div>
            </div>

            <!-- Staff & Receptionist Team -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border shadow-sm rounded-4 h-100 overflow-hidden" style="background: #fbf7ff !important; border-color: #e9d5ff !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase fs-12" style="color: #7e22ce;">Clinic Team</span>
                            <div class="text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #9333ea;">
                                <i class="bi bi-person-workspace fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-2">{{ number_format(count($memberList)) }}</h2>
                        <p class="mb-0 text-muted fs-12">
                            <span class="fw-semibold text-purple" style="color: #7e22ce;">{{ $receptCount }} Receptionist{{ $receptCount != 1 ? 's' : '' }}</span> &bull; 
                            <span class="fw-semibold text-info">{{ $staffMemberCount }} Staff</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status & Verification -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card border border-secondary-subtle shadow-sm rounded-4 h-100 overflow-hidden" style="background: #f4f2ff !important;">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase fs-12" style="color: #6c5ffc;">Account Status</span>
                            <div class="text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #6c5ffc;">
                                <i class="bi bi-shield-check fs-5 text-white"></i>
                            </div>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark fs-4">
                            @if($doctor->status == 1)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fs-13 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 fs-13 rounded-pill"><i class="bi bi-pause-circle-fill me-1"></i> Inactive</span>
                            @endif
                        </h2>
                        <p class="mb-0 text-muted fs-12 mt-2">
                            @if($isDoctorVerified)
                                <span class="text-success fw-medium"><i class="bi bi-patch-check-fill me-1"></i> Doctor Verified</span>
                            @else
                                <span class="text-warning fw-medium"><i class="bi bi-exclamation-triangle me-1"></i> Verification Pending</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

            <!-- Doctor Staff & Receptionists Section -->
            <div class="col-xl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 px-4 border-bottom">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-people-fill me-2 text-primary"></i> Doctor's Staff & Receptionist Team
                            </h5>
                            <small class="text-muted">Staff and Receptionists connected with Dr. {{ $doctor->name }}</small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-12">
                                Total: {{ count($memberList) }} Members
                            </span>
                            <span class="badge px-3 py-1.5 rounded-pill fs-12 fw-bold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe;">
                                <i class="bi bi-person-workspace me-1"></i> Receptionists: {{ $receptCount }}
                            </span>
                            <span class="badge px-3 py-1.5 rounded-pill fs-12 fw-bold" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                <i class="bi bi-person-badge-fill me-1"></i> Staff: {{ $staffMemberCount }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if(count($memberList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 border-0">
                                    <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 50px;">#</th>
                                            <th class="py-3">Member Name</th>
                                            <th class="py-3">Role / Designation</th>
                                            <th class="py-3">Member ID</th>
                                            <th class="pe-4 py-3 text-end">Email Address</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 fs-13">
                                        @foreach($memberList as $index => $member)
                                            @php
                                                $memberInitials = strtoupper(substr($member->name ?? 'M', 0, 2));
                                                $isReceptionist = strtolower($member->role) === 'receptionist';
                                                $isStaff = strtolower($member->role) === 'staff';
                                            @endphp
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        @if($isReceptionist)
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-xs"
                                                                 style="width: 38px; height: 38px; background: #ede9fe; color: #7c3aed; font-size: 13px;">
                                                                {{ $memberInitials }}
                                                            </div>
                                                        @elseif($isStaff)
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-xs"
                                                                 style="width: 38px; height: 38px; background: #e0f2fe; color: #0284c7; font-size: 13px;">
                                                                {{ $memberInitials }}
                                                            </div>
                                                        @else
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-xs bg-light text-dark"
                                                                 style="width: 38px; height: 38px; font-size: 13px;">
                                                                {{ $memberInitials }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <span class="fw-bold text-dark d-block fs-14">{{ $member->name }}</span>
                                                            <small class="text-muted fs-11">
                                                                <i class="bi bi-person-check me-1"></i> Added by: {{ $member->created_by ?? 'Doctor' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($isReceptionist)
                                                        <span class="badge px-3 py-1.5 rounded-pill fs-12 fw-bold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe;">
                                                            <i class="bi bi-person-workspace me-1"></i> Receptionist
                                                        </span>
                                                    @elseif($isStaff)
                                                        <span class="badge px-3 py-1.5 rounded-pill fs-12 fw-bold" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                                            <i class="bi bi-person-badge-fill me-1"></i> Staff (Nurse / Assistant)
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 rounded-pill fs-12 fw-bold">
                                                            {{ ucfirst($member->role) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-dark font-monospace fs-12 bg-light px-2 py-1 rounded border">
                                                        {{ $member->member_id }}
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <span class="text-dark fs-13">
                                                        <i class="bi bi-envelope me-1 text-muted"></i> {{ $member->email ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-people text-muted fs-2"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">No Staff or Receptionist Added</h6>
                                <p class="text-muted fs-12 mb-0">Dr. {{ $doctor->name }} has not registered any staff or receptionist members yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Doctor Documents Table Section -->
            <div class="col-xl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Doctor Documents & Assets
                            </h5>
                            <small class="text-muted">Total {{ count($documentsList) }} uploaded documents & clinic branding files</small>
                        </div>
                        <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-12">
                            Total: {{ count($documentsList) }} Files
                        </span>
                    </div>

                    <div class="card-body p-0">
                        @if(count($documentsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 border-0">
                                    <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 50px;">#</th>
                                            <th class="py-3">Document Title</th>
                                            <th class="py-3">Category / Type</th>
                                            <th class="py-3">Doc / Reg. Number</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="pe-4 py-3 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 fs-13">
                                        @foreach($documentsList as $index => $doc)
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        @php
                                                            $ext = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION));
                                                            $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                        @endphp

                                                        @if($isImg)
                                                            <img src="{{ asset($doc['file']) }}"
                                                                 alt="{{ $doc['title'] }}"
                                                                 class="rounded-3 border shadow-xs object-fit-cover"
                                                                 style="cursor: pointer;"
                                                                 width="38" height="38"
                                                                 onclick="previewDocument('{{ addslashes($doc['title']) }}', '{{ asset($doc['file']) }}', true)"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="bg-primary-subtle text-primary rounded-3 align-items-center justify-content-center" style="display:none; width: 38px; height: 38px;">
                                                                <i class="bi bi-file-earmark-image fs-5"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                                <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <span class="fw-bold text-dark d-block fs-13">{{ $doc['title'] }}</span>
                                                            <small class="text-muted fs-11"><i class="bi bi-paperclip me-1"></i>{{ basename($doc['file']) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary-subtle text-dark border fs-11 px-2.5 py-1 rounded-pill">
                                                        {{ $doc['type'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-secondary font-monospace fs-12">
                                                        {{ $doc['number'] ?: 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($doc['status'] == 'Uploaded' || $doc['status'] == 'Completed')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                            <i class="bi bi-check-circle-fill me-1"></i> {{ $doc['status'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                            <i class="bi bi-clock-history me-1"></i> {{ $doc['status'] }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 fw-bold shadow-xs text-white"
                                                            onclick="previewDocument('{{ addslashes($doc['title']) }}', '{{ asset($doc['file']) }}', {{ $isImg ? 'true' : 'false' }})">
                                                        <i class="bi bi-arrows-angle-expand me-1.5"></i> View File
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-folder-x text-muted display-4 d-block mb-2"></i>
                                <h6 class="text-muted fw-bold">No Documents Uploaded Yet</h6>
                                <p class="text-muted fs-12 mb-0">This doctor has not uploaded any official documents or clinic branding files.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Document Large Preview Modal -->
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text fs-4 text-info"></i>
                    <div>
                        <h5 class="modal-title fs-15 fw-bold text-white mb-0" id="docPreviewModalLabel">Document Large Preview</h5>
                        <small class="text-white-50 fs-11" id="docPreviewSubTitle">Document Viewer</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="docPreviewDownloadBtn" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3 fs-12 fw-semibold">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Full Screen / New Tab
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-3 bg-secondary-subtle text-center d-flex align-items-center justify-content-center" style="min-height: 520px; max-height: 82vh; overflow-y: auto;">
                <img id="docPreviewImg" src="" alt="Document Preview" class="img-fluid rounded-3 shadow" style="max-height: 76vh; width: auto; object-fit: contain; display: none;">
                <iframe id="docPreviewIframe" src="" style="width: 100%; height: 76vh; border: none; border-radius: 8px; display: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function previewDocument(title, url, isImg) {
        document.getElementById('docPreviewModalLabel').innerText = title;
        document.getElementById('docPreviewSubTitle').innerText = url.split('/').pop();
        document.getElementById('docPreviewDownloadBtn').setAttribute('href', url);

        const imgEl = document.getElementById('docPreviewImg');
        const iframeEl = document.getElementById('docPreviewIframe');

        if (isImg) {
            imgEl.src = url;
            imgEl.style.display = 'inline-block';
            iframeEl.style.display = 'none';
            iframeEl.src = '';
        } else {
            iframeEl.src = url;
            iframeEl.style.display = 'block';
            imgEl.style.display = 'none';
            imgEl.src = '';
        }

        const modalEl = document.getElementById('docPreviewModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endsection
