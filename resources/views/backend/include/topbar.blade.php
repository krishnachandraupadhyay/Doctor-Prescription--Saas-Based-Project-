@php
    $isClinic = Auth::guard('clinic')->check();
    $clinicUser = $isClinic ? Auth::guard('clinic')->user() : null;

    $isMember = Auth::guard('member')->check();
    $memberUser = $isMember ? Auth::guard('member')->user() : null;
    $memberRole = $memberUser ? $memberUser->role : null;

    $isDoctor = Auth::guard('doctor')->check();
    $doctorUser = $isDoctor ? Auth::guard('doctor')->user() : null;

    $adminUser = Auth::user();

    $displayName = 'Administrator';
    $displayRole = 'Super Admin';
    $displayEmail = 'admin@portal.com';

    if ($isClinic && $clinicUser) {
        $displayName = $clinicUser->name;
        $displayRole = 'Clinic (' . ($clinicUser->clinic_id ?? 'Admin') . ')';
        $displayEmail = $clinicUser->email ?? '-';
    } elseif ($isMember && $memberUser) {
        $displayName = $memberUser->name;
        $displayRole = ucfirst($memberRole);
        $displayEmail = $memberUser->email ?? ($memberUser->member_id ?? '-');
    } elseif ($isDoctor && $doctorUser) {
        $displayName = 'Dr. ' . $doctorUser->name;
        $displayRole = $doctorUser->specialization ?? 'Doctor';
        $displayEmail = $doctorUser->email ?? '-';
    } elseif ($adminUser) {
        $displayName = $adminUser->name ?? 'Administrator';
        $displayRole = 'Super Admin';
        $displayEmail = $adminUser->email ?? 'admin@portal.com';
    }
@endphp

<div class="topbar-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Sidebar Hamburger Toggle -->
                <button type="button" class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow-none hamburger-icon me-3" id="topnav-hamburger-icon">
                    <i class='bx bx-menu fs-3xl'></i>
                </button>

                @if($isClinic && $clinicUser)
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fs-12 fw-semibold">
                            <i class="bi bi-hospital me-1.5"></i> {{ $clinicUser->name }} ({{ $clinicUser->clinic_id ?? 'CLN' }})
                        </span>
                    </div>
                @else
                    <!-- Valex Header Search Bar -->
                    <div class="valex-header-search d-none d-md-block">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control form-control-sm" placeholder="Search patients, records...">
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Fullscreen button -->
                <button type="button" class="btn valex-top-btn d-none d-sm-inline-flex" data-toggle="fullscreen" title="Toggle Fullscreen">
                    <i class='bx bx-fullscreen fs-xl'></i>
                </button>

                <!-- Notifications Dropdown -->
                @php
                    if ($isClinic && $clinicUser) {
                        $topbarReqs = \App\Models\DoctorCorrectionRequest::where('clinic_id', $clinicUser->id)->with('doctor')->latest()->take(6)->get();
                        $topbarPendingCount = \App\Models\DoctorCorrectionRequest::where('clinic_id', $clinicUser->id)->where('status', 'pending')->count();
                        $allRequestsRoute = route('clinic.doctor_requests');
                    } else {
                        $topbarReqs = \App\Models\DoctorCorrectionRequest::with(['doctor', 'clinic'])->latest()->take(6)->get();
                        $topbarPendingCount = \App\Models\DoctorCorrectionRequest::where('status', 'pending')->count();
                        $allRequestsRoute = route('superadmin.doctor_requests');
                    }
                @endphp
                <div class="dropdown topbar-head-dropdown">
                    <button type="button" class="btn valex-top-btn position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-xl'></i>
                        @if($topbarPendingCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 2px 5px;">
                                {{ $topbarPendingCount > 9 ? '9+' : $topbarPendingCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg border-0 overflow-hidden" aria-labelledby="page-header-notifications-dropdown" style="min-width: 330px;">
                        <div class="p-3 bg-primary text-white rounded-top d-flex align-items-center justify-content-between">
                            <h6 class="m-0 text-white fw-semibold fs-14">
                                <i class="bi bi-bell-fill me-1"></i> Notifications
                            </h6>
                            <span class="badge bg-white text-primary rounded-pill fs-11">
                                {{ $topbarPendingCount }} Pending
                            </span>
                        </div>
                        <div class="p-0" style="max-height: 300px; overflow-y: auto;">
                            @forelse($topbarReqs as $tReq)
                                <a href="{{ $allRequestsRoute }}" class="d-flex align-items-start gap-2.5 p-3 border-bottom text-decoration-none text-dark bg-hover-light">
                                    <div class="avatar-xs flex-shrink-0 mt-0.5">
                                        @if($tReq->status === 'approved')
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-14 p-1.5">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </span>
                                        @elseif($tReq->status === 'rejected')
                                            <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-14 p-1.5">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </span>
                                        @else
                                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-14 p-1.5">
                                                <i class="bi bi-hourglass-split"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fs-12 fw-bold text-dark">
                                                Dr. {{ $tReq->doctor->name ?? 'Doctor' }}
                                            </span>
                                            <small class="text-muted fs-10">{{ $tReq->created_at->diffForHumans(null, true) }}</small>
                                        </div>
                                        <p class="mb-0 fs-12 text-secondary text-truncate" style="max-width: 220px;">
                                            @if($tReq->status === 'approved')
                                                <span class="text-success fw-semibold">Approved:</span> {{ $tReq->field_label }} updated
                                            @elseif($tReq->status === 'rejected')
                                                <span class="text-danger fw-semibold">Rejected:</span> {{ $tReq->field_label }}
                                            @else
                                                <span class="text-warning fw-semibold">Correction:</span> {{ $tReq->field_label }} requested
                                            @endif
                                        </p>
                                        <small class="text-muted fs-10 d-block mt-0.5">
                                            <i class="bi bi-hospital me-1"></i>{{ $tReq->clinic->name ?? ($tReq->doctor->clinic_name ?? 'Clinic') }}
                                        </small>
                                    </div>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted fs-13">
                                    <i class="bi bi-bell-slash fs-2 d-block mb-1 opacity-50"></i>
                                    No correction requests or alerts yet.
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 text-center bg-light border-top">
                            <a href="{{ $allRequestsRoute }}" class="btn btn-sm btn-link text-primary fw-semibold p-0 fs-12">
                                View All Requests <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown topbar-user ms-2">
                    <button type="button" class="btn shadow-none p-0 d-flex align-items-center gap-2" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if($isMember)
                            <div class="valex-avatar-circle bg-primary-transparent text-primary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                        @else
                            <img class="rounded-circle header-profile-user" src="{{ asset('backend/assets/images/icons8-user-default-64.png') }}" alt="User Avatar">
                        @endif
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
                        @if(!$isMember)
                            <a class="dropdown-item py-2" href="{{ route('changepassword.edit', Auth::id() ?? 1) }}">
                                <i class="bi bi-shield-lock me-2 text-primary"></i> Change Password
                            </a>
                        @endif
                        @if(Auth::check() && !Auth::guard('clinic')->check() && !Auth::guard('member')->check() && !Auth::guard('doctor')->check())
                            <a class="dropdown-item py-2" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-sliders me-2 text-primary"></i> System Settings
                            </a>
                        @endif
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