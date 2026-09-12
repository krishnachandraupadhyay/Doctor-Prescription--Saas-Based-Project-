<div class="topbar-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Sidebar Toggle -->
                <button type="button" class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow-none hamburger-icon me-3" id="topnav-hamburger-icon">
                    <i class='bx bx-menu fs-3xl'></i>
                </button>
                <div class="valex-header-search d-none d-md-block">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control form-control-sm" placeholder="Search patients...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Fullscreen -->
                <button type="button" class="btn valex-top-btn d-none d-sm-inline-flex" data-toggle="fullscreen">
                    <i class='bx bx-fullscreen fs-xl'></i>
                </button>

                <!-- Receptionist Profile Dropdown -->
                <div class="dropdown topbar-user ms-2">
                    @php $recUser = Auth::guard('member')->user(); @endphp
                    <button type="button" class="btn shadow-none p-0 d-flex align-items-center gap-2"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold header-profile-user"
                             style="font-size:13px;">
                            {{ strtoupper(substr($recUser->name ?? 'R', 0, 2)) }}
                        </div>
                        <div class="text-start d-none d-xl-block">
                            <span class="fw-semibold user-name-text d-block lh-1">{{ $recUser->name ?? 'Receptionist' }}</span>
                            <span class="fs-11 user-name-sub-text text-primary">{{ ucfirst($recUser->role ?? 'Receptionist') }}</span>
                        </div>
                        <i class="bi bi-chevron-down fs-11 text-muted d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:200px;">
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-0 fw-semibold fs-13">{{ $recUser->name ?? 'Receptionist' }}</h6>
                            <small class="text-muted">{{ $recUser->member_id ?? '' }}</small>
                        </div>
                        <a class="dropdown-item py-2" href="{{ route('receptionist.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('member.logout') }}" id="topbar-member-logout-form">
                            @csrf
                            <a class="dropdown-item text-danger py-2" href="javascript:void(0);"
                               onclick="document.getElementById('topbar-member-logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
