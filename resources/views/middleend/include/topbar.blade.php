@php
    $onboardingUser = Auth::user();
    $displayName = $onboardingUser->name ?? 'Onboarding Member';
    $displayRole = 'Onboarding Manager';
    $displayEmail = $onboardingUser->email ?? 'onboarding@portal.com';
    $initials = strtoupper(substr($displayName, 0, 2));
@endphp

<div class="topbar-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Sidebar Hamburger Toggle -->
                <button type="button" class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow-none hamburger-icon me-3" id="topnav-hamburger-icon">
                    <i class='bx bx-menu fs-3xl'></i>
                </button>

                <!-- Header Search Bar -->
                <div class="valex-header-search d-none d-md-block">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control form-control-sm" placeholder="Search doctors, records...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Fullscreen button -->
                <button type="button" class="btn valex-top-btn d-none d-sm-inline-flex" data-toggle="fullscreen" title="Toggle Fullscreen">
                    <i class='bx bx-fullscreen fs-xl'></i>
                </button>

                <!-- Notifications Dropdown -->
                <div class="dropdown topbar-head-dropdown">
                    <button type="button" class="btn valex-top-btn position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-xl'></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg border-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 bg-primary text-white rounded-top d-flex align-items-center justify-content-between">
                            <h6 class="m-0 text-white fw-semibold fs-14">Notifications</h6>
                            <span class="badge bg-white text-primary rounded-pill fs-11">Onboarding</span>
                        </div>
                        <div class="p-3 text-center text-muted fs-13">
                            <i class="bi bi-bell-slash fs-2 d-block mb-1 opacity-50"></i>
                            No new unread notifications.
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown topbar-user ms-2">
                    <button type="button" class="btn shadow-none p-0 d-flex align-items-center gap-2" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="valex-avatar-circle bg-primary-transparent text-primary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                            {{ $initials }}
                        </div>
                        <div class="text-start d-none d-xl-block">
                            <span class="fw-semibold user-name-text d-block lh-1">{{ $displayName }}</span>
                            <span class="fs-11 user-name-sub-text text-primary">{{ $displayRole }}</span>
                        </div>
                        <i class="bi bi-chevron-down fs-11 text-muted d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $displayName }}</h6>
                            <small class="text-muted fs-11">{{ $displayEmail }}</small>
                        </div>
                        <a class="dropdown-item py-2" href="{{ route('onboarding.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill me-2 text-primary"></i> Dashboard
                        </a>
                        <a class="dropdown-item py-2" href="{{ route('adddoctor.onboarding') }}">
                            <i class="bi bi-person-plus-fill me-2 text-success"></i> Add Doctor
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2 border-0 bg-transparent w-100 text-start">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>
</div>