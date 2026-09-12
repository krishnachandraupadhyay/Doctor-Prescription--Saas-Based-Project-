<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('receptionist.dashboard') }}" class="valex-brand-logo">
            <div class="valex-brand-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="valex-brand-text">
                Doc<span>Valex</span>
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

                {{-- MAIN MENU --}}
                <li class="menu-title"><span>Main</span></li>
                <li class="nav-item">
                    <a href="{{ route('receptionist.dashboard') }}" class="nav-link menu-link {{ request()->is('receptionist/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- PATIENTS --}}
                <li class="menu-title"><span>Patients</span></li>
                <li class="nav-item">
                    <a href="{{ route('receptionist.all_patients') }}" class="nav-link menu-link {{ request()->is('receptionist/all-patients*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>All Patients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('receptionist.patients') }}" class="nav-link menu-link {{ request()->is('receptionist/patients*') ? 'active' : '' }}">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Register Patient</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#today-queue-section" class="nav-link menu-link">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Today's Queue</span>
                    </a>
                </li>

                {{-- ACCOUNT --}}
                <li class="menu-title"><span>Account</span></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('member.logout') }}" id="sidebar-member-logout-form">
                        @csrf
                        <a href="javascript:void(0);"
                           onclick="document.getElementById('sidebar-member-logout-form').submit();"
                           class="nav-link menu-link text-danger">
                            <i class="bi bi-box-arrow-right text-danger"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <!-- Receptionist User Bottom Card -->
    <div class="sidebar-user">
        @php
            $recMember = Auth::guard('member')->user();
        @endphp
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                {{ strtoupper(substr($recMember->name ?? 'R', 0, 2)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <h6 class="text-white mb-0 fs-13 text-truncate fw-semibold">{{ $recMember->name ?? 'Receptionist' }}</h6>
                <small class="text-info fs-11 text-truncate d-block">
                    {{ ucfirst($recMember->role ?? 'Receptionist') }}
                    &bull; {{ $recMember->member_id ?? '' }}
                </small>
            </div>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
