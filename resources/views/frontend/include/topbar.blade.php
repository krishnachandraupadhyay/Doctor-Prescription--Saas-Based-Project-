<div class="topbar-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Sidebar Hamburger Toggle -->
                <button type="button" class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow-none hamburger-icon me-3" id="topnav-hamburger-icon">
                    <i class='bx bx-menu fs-3xl'></i>
                </button>

                <!-- Valex Header Search Bar -->
                <div class="valex-header-search d-none d-md-block">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control form-control-sm" placeholder="Search patients, medicine, records...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Fullscreen button -->
                <button type="button" class="btn valex-top-btn d-none d-sm-inline-flex" data-toggle="fullscreen" title="Toggle Fullscreen">
                    <i class='bx bx-fullscreen fs-xl'></i>
                </button>

                <!-- Notifications Dropdown -->
                @php
                    $curDoc = Auth::guard('doctor')->user();
                    $docCorrectionReqs = $curDoc 
                        ? \App\Models\DoctorCorrectionRequest::where('doctor_id', $curDoc->id)->with('clinic')->latest()->take(6)->get()
                        : collect();
                    $unreadDocReqsCount = $curDoc
                        ? \App\Models\DoctorCorrectionRequest::where('doctor_id', $curDoc->id)->where('is_read', false)->count()
                        : 0;
                @endphp
                <div class="dropdown topbar-head-dropdown" id="topbar-notifications-wrap">
                    <button type="button" class="btn valex-top-btn position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="handleNotificationDropdownOpen()">
                        <i class='bx bx-bell fs-xl'></i>
                        @if($unreadDocReqsCount > 0)
                            <span id="notif-badge-counter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 2px 5px;">
                                {{ $unreadDocReqsCount > 9 ? '9+' : $unreadDocReqsCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg border-0 overflow-hidden" aria-labelledby="page-header-notifications-dropdown" style="min-width: 320px;">
                        <div class="p-3 bg-primary text-white rounded-top d-flex align-items-center justify-content-between">
                            <h6 class="m-0 text-white fw-semibold fs-14">
                                <i class="bi bi-bell-fill me-1"></i> Notifications
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-white text-primary rounded-pill fs-11">Clinic Updates</span>
                                @if($unreadDocReqsCount > 0)
                                    <button type="button" onclick="markAllNotificationsAsRead(event)" class="btn btn-sm btn-link text-white-50 p-0 fs-11 text-decoration-none" title="Mark all as read">
                                        <i class="bi bi-check2-all"></i> Mark Read
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="p-0" id="notification-items-list" style="max-height: 300px; overflow-y: auto;">
                            @forelse($docCorrectionReqs as $dReq)
                                <a href="{{ route('frontend.profile.view') }}" class="d-flex align-items-start gap-2.5 p-3 border-bottom text-decoration-none text-dark bg-hover-light {{ !$dReq->is_read ? 'bg-light' : '' }}" onclick="markAllNotificationsAsRead()">
                                    <div class="avatar-xs flex-shrink-0 mt-0.5 position-relative">
                                        @if($dReq->status === 'approved')
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-14 p-1.5">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </span>
                                        @elseif($dReq->status === 'rejected')
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
                                            <span class="fs-12 fw-bold text-dark d-flex align-items-center gap-1">
                                                {{ $dReq->field_label }}
                                                @if(!$dReq->is_read)
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill fs-9 unread-dot" style="font-size: 8px; padding: 1px 4px;">New</span>
                                                @endif
                                            </span>
                                            <small class="text-muted fs-10">
                                                {{ $dReq->reviewed_at ? $dReq->reviewed_at->diffForHumans(null, true) : $dReq->created_at->diffForHumans(null, true) }}
                                            </small>
                                        </div>
                                        <p class="mb-0 fs-12 text-secondary text-break">
                                            @if($dReq->status === 'approved')
                                                <span class="text-success fw-semibold">Approved by {{ $dReq->reviewed_by ?: ($dReq->clinic->name ?? 'Clinic') }}:</span> Profile updated to "{{ $dReq->requested_value }}"
                                            @elseif($dReq->status === 'rejected')
                                                <span class="text-danger fw-semibold">Rejected by {{ $dReq->reviewed_by ?: ($dReq->clinic->name ?? 'Clinic') }}:</span> {{ $dReq->admin_notes }}
                                            @else
                                                <span class="text-warning fw-semibold">Submitted:</span> Awaiting review from {{ $dReq->clinic->name ?? 'Clinic' }}
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted fs-13">
                                    <i class="bi bi-bell-slash fs-2 d-block mb-1 opacity-50"></i>
                                    No new notifications from clinic.
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 text-center bg-light border-top">
                            <a href="{{ route('frontend.profile.view') }}" class="btn btn-sm btn-link text-primary fw-semibold p-0 fs-12">
                                View Profile Correction History <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    function handleNotificationDropdownOpen() {
                        markAllNotificationsAsRead();
                    }

                    function markAllNotificationsAsRead(event) {
                        if (event) event.stopPropagation();

                        const badge = document.getElementById('notif-badge-counter');
                        if (badge) {
                            badge.remove();
                        }

                        document.querySelectorAll('.unread-dot').forEach(el => el.remove());

                        fetch("{{ route('doctor.notifications.mark_read') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({})
                        }).catch(err => console.error("Notification mark-read error:", err));
                    }
                </script>

                <!-- Doctor Profile Dropdown -->
                <div class="dropdown topbar-user ms-2">
                    @php
                        $topbarDoctor = Auth::guard('doctor')->user() ?? Auth::user();
                        $topbarClinicPhoto = \App\Models\doctor_clinic_document::where('doctor_id', Auth::guard('doctor')->id())
                            ->when($topbarDoctor && $topbarDoctor->clinic_id, function($q) use ($topbarDoctor) {
                                $q->orWhere('clinic_id', $topbarDoctor->clinic_id);
                            })->value('photo');
                        $topbarAvatar = $topbarClinicPhoto 
                            ? asset($topbarClinicPhoto) 
                            : ($topbarDoctor && $topbarDoctor->image 
                                ? asset('storage/'.$topbarDoctor->image) 
                                : ($topbarDoctor && $topbarDoctor->logo ? asset('upload/logo/'.$topbarDoctor->logo) : asset('backend/assets/images/icons8-user-default-64.png')));
                    @endphp
                    <button type="button" class="btn shadow-none p-0 d-flex align-items-center gap-2" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ $topbarAvatar }}" alt="Doctor Avatar" onerror="this.src='{{ asset('backend/assets/images/icons8-user-default-64.png') }}'">
                        <div class="text-start d-none d-xl-block">
                            <span class="fw-semibold user-name-text d-block lh-1">Dr. {{ $topbarDoctor->name ?? 'Doctor' }}</span>
                            <span class="fs-11 user-name-sub-text text-primary">{{ $topbarDoctor->specialization ?? 'General Physician' }}</span>
                        </div>
                        <i class="bi bi-chevron-down fs-11 text-muted d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-0 fw-bold fs-13 text-dark">Dr. {{ $topbarDoctor->name ?? 'Doctor' }}</h6>
                            <small class="text-muted fs-11">{{ $topbarDoctor->email ?? '-' }}</small>
                        </div>
                        <a class="dropdown-item py-2" href="{{ route('frontend.profile.view') }}">
                            <i class="bi bi-person me-2 text-primary"></i> My Profile
                        </a>
                        <a class="dropdown-item py-2" href="{{ route('changepassword') }}">
                            <i class="bi bi-key me-2 text-warning"></i> Change Password
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