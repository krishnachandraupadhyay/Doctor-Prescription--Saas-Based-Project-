@php
    $isClinic = Auth::guard('clinic')->check();
    $clinicUser = $isClinic ? Auth::guard('clinic')->user() : null;

    $isMember = Auth::guard('member')->check();
    $memberUser = $isMember ? Auth::guard('member')->user() : null;
    $memberRole = $memberUser ? $memberUser->role : null;
    $adminUser = Auth::user();
@endphp

<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        @if($isClinic)
            <a href="{{ url('/Admindashboard') }}" class="valex-brand-logo">
                <div class="valex-brand-icon">
                    <i class="bi bi-hospital-fill"></i>
                </div>
                <div class="valex-brand-text">
                    Clinic<span>Portal</span>
                </div>
            </a>
        @elseif($memberRole === 'receptionist')
            <a href="{{ route('receptionist.dashboard') }}" class="valex-brand-logo">
                <div class="valex-brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="valex-brand-text">
                    Doc<span>Portal</span>
                </div>
            </a>
        @elseif($memberRole === 'staff')
            <a href="{{ route('staff.dashboard') }}" class="valex-brand-logo">
                <div class="valex-brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="valex-brand-text">
                    Doc<span>Portal</span>
                </div>
            </a>
        @else
            <a href="{{ url('/Admindashboard') }}" class="valex-brand-logo">
                @php
                    $sysLogo = \App\Models\SystemSetting::get('system_logo');
                    $sysName = \App\Models\SystemSetting::get('system_name', 'DocPortal');
                @endphp
                @if(!empty($sysLogo) && file_exists(public_path($sysLogo)))
                    <img src="{{ asset($sysLogo) }}" alt="{{ $sysName }}" style="max-height: 36px; max-width: 140px; object-fit: contain;">
                @else
                    <div class="valex-brand-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="valex-brand-text">
                        {{ $sysName }}
                    </div>
                @endif
            </a>
        @endif
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover text-white opacity-75" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" data-simplebar class="h-100">
        <div class="container-fluid px-0">

            <div id="two-column-menu"></div>
            
            <ul class="navbar-nav" id="navbar-nav">

                @if($memberRole === 'receptionist')
                    {{-- RECEPTIONIST SIDEBAR MENU --}}
                    <li class="menu-title"><span>Main</span></li>
                    <li class="nav-item">
                        <a href="{{ route('receptionist.dashboard') }}" class="nav-link menu-link {{ request()->is('receptionist/dashboard*') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> 
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Patient Desk</span></li>
                    <li class="nav-item">
                        <a href="{{ route('receptionist.patients') }}" class="nav-link menu-link {{ request()->is('receptionist/patients*') ? 'active' : '' }}">
                            <i class="bi bi-person-plus-fill"></i> 
                            <span>Register Patient</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/receptionist/dashboard#today-queue-section') }}" class="nav-link menu-link">
                            <i class="bi bi-people-fill"></i> 
                            <span>Today's Queue</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Account</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="sidebar-member-logout-form">
                            @csrf
                            <a href="javascript:void(0);" onclick="document.getElementById('sidebar-member-logout-form').submit();" class="nav-link menu-link text-danger">
                                <i class="bi bi-box-arrow-right text-danger"></i> 
                                <span>Logout</span>
                            </a>
                        </form>
                    </li>

                @elseif($memberRole === 'staff')
                    {{-- STAFF SIDEBAR MENU --}}
                    <li class="menu-title"><span>Main</span></li>
                    <li class="nav-item">
                        <a href="{{ route('staff.dashboard') }}" class="nav-link menu-link {{ request()->is('staff/dashboard*') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> 
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Clinic Desk</span></li>
                    <li class="nav-item">
                        <a href="{{ url('/staff/dashboard#today-queue-section') }}" class="nav-link menu-link">
                            <i class="bi bi-people-fill"></i> 
                            <span>Patient Queue</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.dashboard') }}" class="nav-link menu-link {{ request()->is('staff/physical-exam*') ? 'active' : '' }}">
                            <i class="bi bi-heart-pulse-fill"></i> 
                            <span>Physical Examination</span>
                        </a>
                    </li>

                    <li class="menu-title"><span>Account</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="sidebar-staff-logout-form">
                            @csrf
                            <a href="javascript:void(0);" onclick="document.getElementById('sidebar-staff-logout-form').submit();" class="nav-link menu-link text-danger">
                                <i class="bi bi-box-arrow-right text-danger"></i> 
                                <span>Logout</span>
                            </a>
                        </form>
                    </li>

                @else
                    {{-- ADMIN SIDEBAR MENU --}}
                    <li class="menu-title"><span>Main</span></li>
                    <li class="nav-item">
                        <a href="{{ url('/Admindashboard') }}" class="nav-link menu-link {{ request()->is('Admindashboard*') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> 
                            <span data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>


                    {{-- CLINIC MANAGEMENT --}}
                    <li class="menu-title"><span>Clinics</span></li>
                    <li class="nav-item">
                        <a href="{{ route('clinics.index') }}" class="nav-link menu-link {{ request()->is('clinics*') ? 'active' : '' }}">
                            <i class="bi bi-hospital-fill"></i> 
                            <span data-key="t-clinics">Manage Clinics</span>
                        </a>
                    </li>

                    {{-- DOCTORS MANAGEMENT --}}
                    <li class="menu-title"><span>Doctors Management</span></li>
                    <li class="nav-item">
                        <a href="#sidebarDoctors" class="nav-link menu-link {{ request()->is('adddoctor*') || request()->is('managedoctor*') || request()->is('deleteddoctor*') || request()->is('admin/doctor-requests*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('adddoctor*') || request()->is('managedoctor*') || request()->is('deleteddoctor*') || request()->is('admin/doctor-requests*') ? 'true' : 'false' }}" aria-controls="sidebarDoctors">
                            <i class="bi bi-person-badge-fill"></i> 
                            <span data-key="t-doctors">Doctors</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('adddoctor*') || request()->is('managedoctor*') || request()->is('deleteddoctor*') || request()->is('admin/doctor-requests*') ? 'show' : '' }}" id="sidebarDoctors">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('/adddoctor') }}" class="nav-link {{ request()->is('adddoctor*') ? 'active' : '' }}" data-key="t-add-doctor">Add Doctor</a> 
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/managedoctor') }}" class="nav-link {{ request()->is('managedoctor*') ? 'active' : '' }}" data-key="t-manage-doctors">Manage Doctors</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('superadmin.doctor_requests') }}" class="nav-link {{ request()->is('admin/doctor-requests*') ? 'active' : '' }}" data-key="t-correction-requests">
                                        Correction Requests
                                        @php
                                            $superAdminPendingReqs = \App\Models\DoctorCorrectionRequest::where('status', 'pending')->count();
                                        @endphp
                                        @if($superAdminPendingReqs > 0)
                                            <span class="badge badge-pill bg-danger ms-auto fs-10">{{ $superAdminPendingReqs }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/deleteddoctor') }}" class="nav-link {{ request()->is('deleteddoctor*') ? 'active' : '' }}" data-key="t-deleted-doctors">Deleted Doctors</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- ONBOARDING MANAGERS --}}
                    <li class="menu-title"><span>Onboarding</span></li>
                    <li class="nav-item">
                        <a href="#sidebarOnboarding" class="nav-link menu-link {{ request()->is('onboarding*') || request()->is('deletedonboarding*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('onboarding*') || request()->is('deletedonboarding*') ? 'true' : 'false' }}" aria-controls="sidebarOnboarding">
                            <i class="bi bi-people-fill"></i> 
                            <span data-key="t-onboarding">Onboarding Managers</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('onboarding*') || request()->is('deletedonboarding*') ? 'show' : '' }}" id="sidebarOnboarding">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('/onboarding') }}" class="nav-link {{ request()->is('onboarding*') ? 'active' : '' }}" data-key="t-manage-onboarding">Manage Onboarding</a> 
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/deletedonboarding') }}" class="nav-link {{ request()->is('deletedonboarding*') ? 'active' : '' }}" data-key="t-deleted-onboarding">Deleted Onboarding</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- CLINICAL MASTERS --}}
                    <li class="menu-title"><span>Clinical Masters</span></li>
                    <li class="nav-item">
                        <a href="#sidebarMasters" class="nav-link menu-link {{ request()->is('medicine*') || request()->is('admin.symptoms*') || request()->is('diagnosistest*') || request()->is('suggestion*') || request()->is('medicinemaster*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('medicine*') || request()->is('admin.symptoms*') || request()->is('diagnosistest*') || request()->is('suggestion*') || request()->is('medicinemaster*') ? 'true' : 'false' }}" aria-controls="sidebarMasters">
                            <i class="bi bi-database-fill-gear"></i> 
                            <span data-key="t-masters">Masters</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('medicine*') || request()->is('admin.symptoms*') || request()->is('diagnosistest*') || request()->is('suggestion*') || request()->is('medicinemaster*') ? 'show' : '' }}" id="sidebarMasters">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('/medicine') }}" class="nav-link {{ request()->is('medicine*') ? 'active' : '' }}" data-key="t-medicine-master">Medicine Master</a> 
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.symptoms') }}" class="nav-link {{ request()->is('admin.symptoms*') ? 'active' : '' }}" data-key="t-symptoms-master">Symptoms Master</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('diagnosistest') }}" class="nav-link {{ request()->is('diagnosistest*') ? 'active' : '' }}" data-key="t-test-master">Diagnosis Test Master</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/suggestion') }}" class="nav-link {{ request()->is('suggestion*') ? 'active' : '' }}" data-key="t-advice-master">Advice / Suggestion Master</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/medicinemaster') }}" class="nav-link {{ request()->is('medicinemaster*') ? 'active' : '' }}" data-key="t-medicine-rel-master">Formulary (Dose, Unit, Interval)</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- CLINICAL RECORDS & SERVICES --}}
                    <li class="menu-title"><span>Clinical Records</span></li>
                    <li class="nav-item">
                        <a href="{{ url('/patientdetails') }}" class="nav-link menu-link {{ request()->is('patientdetails*') ? 'active' : '' }}">
                            <i class="bi bi-person-lines-fill"></i> 
                            <span data-key="t-patients-dash">Patient Records</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/prescription/design') }}" class="nav-link menu-link {{ request()->is('*prescription*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-medical-fill"></i> 
                            <span data-key="t-prescriptions">Prescription Design</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/medicinedetails') }}" class="nav-link menu-link {{ request()->is('medicinedetails*') ? 'active' : '' }}">
                            <i class="bi bi-capsule-pill"></i> 
                            <span data-key="t-medicine-inventory">Medicine Directory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/paymentscategory') }}" class="nav-link menu-link {{ request()->is('paymentscategory*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-2-front-fill"></i> 
                            <span data-key="t-payments">Payment Categories</span>
                        </a>
                    </li>

                    {{-- SECURITY & SETTINGS --}}
                    <li class="menu-title"><span>Settings</span></li>
                    <li class="nav-item">
                        <a href="{{ route('clinics.permissions') }}" class="nav-link menu-link {{ request()->is('clinics/permissions*') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i> 
                            <span data-key="t-clinic-permissions">Clinic Permissions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/Doctor.changePassword') }}" class="nav-link menu-link {{ request()->is('*Doctor.changePassword*') ? 'active' : '' }}">
                            <i class="bi bi-key-fill"></i> 
                            <span data-key="t-doc-pwd">Doctor Passwords</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('changepassword.edit', Auth::id() ?? 1) }}" class="nav-link menu-link {{ request()->is('*changepassword*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock"></i> 
                            <span data-key="t-my-pwd">Change Password</span>
                        </a>
                    </li>
                    {{-- SYSTEM CONFIGURATION --}}
                    <li class="menu-title"><span>System Configuration</span></li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                            <i class="bi bi-sliders"></i> 
                            <span data-key="t-system-settings">System Settings</span>
                        </a>
                    </li>
                    {{-- SUBSCRIPTIONS & PLANS --}}
                    <li class="menu-title"><span>SaaS Plans</span></li>
                    <li class="nav-item">
                        <a href="#sidebarSubscriptions" class="nav-link menu-link {{ request()->is('admin/subscriptions*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/subscriptions*') ? 'true' : 'false' }}" aria-controls="sidebarSubscriptions">
                            <i class="bi bi-credit-card-2-front-fill"></i> 
                            <span data-key="t-subscriptions">Subscriptions &amp; Plans</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/subscriptions*') ? 'show' : '' }}" id="sidebarSubscriptions">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.subscriptions.plans.index') }}" class="nav-link {{ request()->is('admin/subscriptions/plans*') ? 'active' : '' }}" data-key="t-sub-plans">Subscription Plans</a> 
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="nav-link {{ request()->is('admin/subscriptions/subscribers*') ? 'active' : '' }}" data-key="t-subscribers">Manage Subscribers</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.subscriptions.features.index') }}" class="nav-link {{ request()->is('admin/subscriptions/features*') ? 'active' : '' }}" data-key="t-sub-features">Feature Catalog</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="sidebar-admin-valex-logout-form">
                            @csrf
                            <a href="javascript:void(0);" onclick="document.getElementById('sidebar-admin-valex-logout-form').submit();" class="nav-link menu-link text-danger">
                                <i class="bi bi-box-arrow-right text-danger"></i> 
                                <span data-key="t-logout">Logout</span>
                            </a>
                        </form>
                    </li>
                @endif

            </ul>
        </div>
    </div>

    <!-- User Bottom Card -->
    <div class="sidebar-user">
        @if($isClinic)
            <div class="d-flex align-items-center gap-2">
                <div class="valex-avatar-circle bg-primary text-white" style="width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                    {{ strtoupper(substr($clinicUser->name ?? 'C', 0, 2)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="text-white mb-0 fs-13 text-truncate fw-semibold">{{ $clinicUser->name ?? 'Clinic' }}</h6>
                    <small class="text-info fs-11 text-truncate d-block">{{ $clinicUser->clinic_id ?? 'Clinic Admin' }}</small>
                </div>
            </div>
        @elseif($isMember)
            <div class="d-flex align-items-center gap-2">
                <div class="valex-avatar-circle bg-primary-transparent text-primary" style="width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                    {{ strtoupper(substr($memberUser->name ?? 'M', 0, 2)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="text-white mb-0 fs-13 text-truncate fw-semibold">{{ $memberUser->name ?? 'Member' }}</h6>
                    <small class="text-info fs-11 text-truncate d-block">{{ ucfirst($memberRole) }}</small>
                </div>
            </div>
        @else
            <div class="d-flex align-items-center gap-2">
                <img class="rounded-circle" src="{{ asset('backend/assets/images/icons8-user-default-64.png') }}" alt="Admin Avatar" width="36" height="36">
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="text-white mb-0 fs-13 text-truncate fw-semibold">{{ $adminUser->name ?? 'Administrator' }}</h6>
                    <small class="text-info fs-11 text-truncate d-block">Super Admin</small>
                </div>
                <a href="{{ route('changepassword.edit', Auth::id() ?? 1) }}" class="text-white opacity-50 text-decoration-none" title="Security Settings">
                    <i class="bi bi-gear fs-14"></i>
                </a>
            </div>
        @endif
    </div>

    <div class="sidebar-background"></div>
</div>