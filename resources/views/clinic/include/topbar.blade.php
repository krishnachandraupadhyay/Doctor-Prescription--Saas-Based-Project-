@php
    $authClinic = Auth::guard('clinic')->user();
    $clinicName = $authClinic->name ?? 'Clinic Portal';
    $clinicId   = $authClinic->clinic_id ?? 'CLN-0000';
    $initials   = strtoupper(substr($clinicName, 0, 2));
@endphp

<div class="topbar-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Sidebar Hamburger Toggle -->
                <button type="button" class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow-none hamburger-icon me-3" id="topnav-hamburger-icon">
                    <i class='bx bx-menu fs-3xl'></i>
                </button>

                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fs-12 fw-semibold">
                        <i class="bi bi-hospital me-1.5"></i> {{ $clinicName }} ({{ $clinicId }})
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Fullscreen button -->
                <button type="button" class="btn valex-top-btn d-none d-sm-inline-flex" data-toggle="fullscreen" title="Toggle Fullscreen">
                    <i class='bx bx-fullscreen fs-xl'></i>
                </button>

                <!-- Clinic User Dropdown -->
                <div class="dropdown topbar-user ms-2">
                    <button type="button" class="btn shadow-none p-0 d-flex align-items-center gap-2" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-13" style="width: 36px; height: 36px;">
                            {{ $initials }}
                        </div>
                        <div class="text-start d-none d-xl-block">
                            <span class="fw-semibold user-name-text d-block lh-1">{{ $clinicName }}</span>
                            <span class="fs-11 user-name-sub-text text-primary">Clinic Admin</span>
                        </div>
                        <i class="bi bi-chevron-down fs-11 text-muted d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                        <h6 class="dropdown-header">Welcome {{ $clinicName }}!</h6>
                        <a class="dropdown-item" href="{{ route('clinic.profile') }}">
                            <i class="bi bi-person-gear text-muted fs-15 align-middle me-1"></i> Clinic Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('clinic.doctors') }}">
                            <i class="bi bi-person-badge text-muted fs-15 align-middle me-1"></i> Doctors List
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="topbar-clinic-logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right text-danger fs-15 align-middle me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
