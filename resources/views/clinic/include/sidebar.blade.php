@php
    $authClinic = Auth::guard('clinic')->user();
    $clinicName = $authClinic->name ?? 'Clinic Portal';
    $clinicId   = $authClinic->clinic_id ?? 'CLN-0000';
    $initials   = strtoupper(substr($clinicName, 0, 2));

    $pendingDoctorReqsCount = 0;
    if ($authClinic) {
        $docIds = \App\Models\Doctor::where('clinic_id', $authClinic->id)
            ->orWhere('clinic_name', $authClinic->name)
            ->pluck('id')
            ->toArray();
        $pendingDoctorReqsCount = \App\Models\DoctorCorrectionRequest::where(function($q) use ($authClinic, $docIds) {
            $q->where('clinic_id', $authClinic->id);
            if (!empty($docIds)) {
                $q->orWhereIn('doctor_id', $docIds);
            }
        })->where('status', 'pending')->count();
    }
@endphp

<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('clinic.dashboard') }}" class="valex-brand-logo">
            <div class="valex-brand-icon">
                <i class="bi bi-hospital-fill"></i>
            </div>
            <div class="valex-brand-text">
                Clinic<span>Portal</span>
            </div>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover text-white opacity-75" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" data-simplebar class="h-100">
        <div class="container-fluid px-0">

            <div id="two-column-menu"></div>
            
            <ul class="navbar-nav" id="navbar-nav">

                {{-- MAIN SECTION --}}
                <li class="menu-title"><span>Main</span></li>
                <li class="nav-item">
                    <a href="{{ route('clinic.dashboard') }}" class="nav-link menu-link {{ request()->is('clinic/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> 
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- CLINIC DOCTORS & REQUESTS --}}
                <li class="menu-title"><span>Medical Team</span></li>
                <li class="nav-item">
                    <a href="{{ route('clinic.doctors') }}" class="nav-link menu-link {{ request()->is('clinic/doctors*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> 
                        <span data-key="t-doctors">Clinic Doctors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clinic.doctor_requests') }}" class="nav-link menu-link {{ request()->is('clinic/doctor-requests*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-diff-fill"></i> 
                        <span data-key="t-doctor-requests">Doctor Requests</span>
                        @if($pendingDoctorReqsCount > 0)
                            <span class="badge bg-warning ms-auto fs-10 px-1.5 py-0.5 rounded-pill text-white">{{ $pendingDoctorReqsCount }}</span>
                        @endif
                    </a>
                </li>

                {{-- PATIENT RECORDS --}}
                <li class="menu-title"><span>Clinical Records</span></li>
                <li class="nav-item">
                    <a href="{{ route('clinic.patients') }}" class="nav-link menu-link {{ request()->is('clinic/patients*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> 
                        <span data-key="t-patients">Patient Directory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clinic.prescription_design') }}" class="nav-link menu-link {{ request()->is('clinic/prescription-design*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical-fill"></i> 
                        <span data-key="t-prescription-design">Prescription Design</span>
                    </a>
                </li>

                {{-- CLINIC STAFF MANAGEMENT --}}
                @if(!empty($authClinic->has_member) || !empty($authClinic->has_deleted_staff))
                <li class="menu-title"><span>Staff &amp; Team</span></li>
                @endif

                @if(!empty($authClinic->has_member))
                    @if(!empty($authClinic->has_deleted_staff))
                        <li class="nav-item">
                            <a href="#sidebarClinicStaff" 
                               class="nav-link menu-link {{ request()->is('clinic/staff*') || request()->is('clinic/deleted-staff*') ? '' : 'collapsed' }}" 
                               data-bs-toggle="collapse" 
                               role="button" 
                               aria-expanded="{{ request()->is('clinic/staff*') || request()->is('clinic/deleted-staff*') ? 'true' : 'false' }}" 
                               aria-controls="sidebarClinicStaff">
                                <i class="bi bi-people-fill"></i> 
                                <span data-key="t-clinic-staff">Clinic Staff</span>
                            </a>
                            <div class="collapse menu-dropdown {{ request()->is('clinic/staff*') || request()->is('clinic/deleted-staff*') ? 'show' : '' }}" id="sidebarClinicStaff">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('clinic.staff') }}" class="nav-link {{ request()->is('clinic/staff*') ? 'active' : '' }}" data-key="t-manage-staff">
                                            Manage Staff
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('clinic.deleted_staff') }}" class="nav-link {{ request()->is('clinic/deleted-staff*') ? 'active' : '' }}" data-key="t-deleted-staff">
                                            Deleted Staff
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('clinic.staff') }}" class="nav-link menu-link {{ request()->is('clinic/staff*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i> 
                                <span data-key="t-staff">Clinic Staff</span>
                            </a>
                        </li>
                    @endif
                @elseif(!empty($authClinic->has_deleted_staff))
                    <li class="nav-item">
                        <a href="{{ route('clinic.deleted_staff') }}" class="nav-link menu-link {{ request()->is('clinic/deleted-staff*') ? 'active' : '' }}">
                            <i class="bi bi-person-x-fill"></i> 
                            <span data-key="t-deleted-staff">Deleted Staff</span>
                        </a>
                    </li>
                @endif

                {{-- BILLING & SERVICES --}}
                <li class="menu-title"><span>Billing &amp; Services</span></li>
                @if(!empty($authClinic->has_payment_category))
                <li class="nav-item">
                    <a href="{{ route('clinic.payment_categories') }}" class="nav-link menu-link {{ request()->is('clinic/payment-categories*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i> 
                        <span data-key="t-payment-categories">Payment Categories</span>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('clinic.revisit_rule') }}" class="nav-link menu-link {{ request()->is('clinic/revisit-rule*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-repeat"></i> 
                        <span data-key="t-revisit-rule">Revisit / Follow-up Rule</span>
                    </a>
                </li>

                {{-- SETTINGS & ACCOUNT --}}
                <li class="menu-title"><span>Account &amp; Settings</span></li>
                <li class="nav-item">
                    <a href="{{ route('clinic.profile') }}" class="nav-link menu-link {{ request()->is('clinic/profile*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill"></i> 
                        <span data-key="t-profile">Clinic Profile</span>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-clinic-logout-form">
                        @csrf
                        <a href="javascript:void(0);" onclick="document.getElementById('sidebar-clinic-logout-form').submit();" class="nav-link menu-link text-danger">
                            <i class="bi bi-box-arrow-right text-danger"></i> 
                            <span data-key="t-logout">Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
    </div>

    <!-- Sidebar User Footer -->
    <div class="sidebar-user d-flex align-items-center gap-2">
        <div class="valex-avatar-circle bg-primary-transparent text-primary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
            {{ $initials }}
        </div>
        <div class="overflow-hidden flex-grow-1">
            <span class="d-block text-white fw-semibold fs-13 text-truncate lh-1">{{ $clinicName }}</span>
            <small class="text-white-50 fs-11 text-truncate d-block">{{ $clinicId }}</small>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
