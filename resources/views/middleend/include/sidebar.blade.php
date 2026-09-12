@php
    $onboardingUser = Auth::user();
    $displayName = $onboardingUser->name ?? 'Onboarding Member';
    $displayRole = 'Onboarding Manager';
    $initials = strtoupper(substr($displayName, 0, 2));
@endphp

<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('onboarding.dashboard') }}" class="valex-brand-logo">
            <div class="valex-brand-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="valex-brand-text">
                Onboarding<span>Portal</span>
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
                    <a href="{{ route('onboarding.dashboard') }}" class="nav-link menu-link {{ request()->is('onboarding/dashboard*') || request()->is('onboarding') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> 
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- CLINIC MANAGEMENT --}}
                <li class="menu-title"><span>Clinics</span></li>
                <li class="nav-item">
                    <a href="{{ route('clinics.index') }}" class="nav-link menu-link {{ request()->is('clinics') ? 'active' : '' }}">
                        <i class="bi bi-hospital-fill"></i> 
                        <span data-key="t-clinics">Manage Clinics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clinics.permissions') }}" class="nav-link menu-link {{ request()->is('clinics/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> 
                        <span data-key="t-clinic-permissions">Clinic Permissions</span>
                    </a>
                </li>

                {{-- DOCTOR MANAGEMENT --}}
                <li class="menu-title"><span>Doctors Management</span></li>
                <li class="nav-item">
                    <a href="{{ route('/manage.doctor') }}" class="nav-link menu-link {{ request()->is('manage.doctor*') || request()->is('doctor/*/edit') || request()->is('doctor/*/view') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> 
                        <span data-key="t-manage-doctors">Manage Doctors</span>
                    </a>
                </li>

                {{-- PRESCRIPTION & CLINIC SETUP --}}
                <li class="menu-title"><span>Prescription &amp; Layout</span></li>
                <li class="nav-item">
                    <a href="{{ route('prescription.type') }}" class="nav-link menu-link {{ request()->is('prescription.type*') ? 'active' : '' }}">
                        <i class="bi bi-journal-medical"></i> 
                        <span data-key="t-prescription-type">Prescription Type</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('/prescrpt_header_footer') }}" class="nav-link menu-link {{ request()->is('prescrpt_header_footer*') ? 'active' : '' }}">
                        <i class="bi bi-card-heading"></i> 
                        <span data-key="t-header-footer">Header &amp; Footer</span>
                    </a>
                </li>

                {{-- CLINICAL CONFIGURATION --}}
                <li class="menu-title"><span>Clinical Setup</span></li>
                <li class="nav-item">
                    <a href="{{ route('payment.method') }}" class="nav-link menu-link {{ request()->is('payment.method*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-2-front-fill"></i> 
                        <span data-key="t-payment-method">Payment Methods</span>
                    </a>
                </li>

                {{-- ACCOUNT & LOGOUT --}}
                <li class="menu-title"><span>Account</span></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-onboarding-logout-form">
                        @csrf
                        <a href="javascript:void(0);" onclick="document.getElementById('sidebar-onboarding-logout-form').submit();" class="nav-link menu-link text-danger">
                            <i class="bi bi-box-arrow-right text-danger"></i> 
                            <span>Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
    </div>

    <!-- Sidebar User Footer -->
    <div class="sidebar-user d-flex align-items-center gap-2">
        <div class="valex-avatar-circle bg-primary-transparent text-primary rounded-circle" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
            {{ $initials }}
        </div>
        <div class="overflow-hidden flex-grow-1">
            <span class="d-block text-white fw-semibold fs-13 text-truncate lh-1">{{ $displayName }}</span>
            <small class="text-white-50 fs-11 text-truncate d-block">{{ $displayRole }}</small>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>