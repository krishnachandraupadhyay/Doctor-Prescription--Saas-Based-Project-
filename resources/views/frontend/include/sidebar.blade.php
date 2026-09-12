<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('/doctor/dashboard') }}" class="valex-brand-logo">
            <div class="valex-brand-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="valex-brand-text">
                Doc<span>Portal</span>
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
                    <a href="{{ url('/doctor/dashboard') }}" class="nav-link menu-link {{ request()->is('doctor/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> 
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- PATIENTS & CLINIC --}}
                <li class="menu-title"><span>Patients &amp; Clinic</span></li>
                <li class="nav-item">
                    <a href="{{ route('Addpatient') }}" class="nav-link menu-link {{ request()->is('Addpatient*') ? 'active' : '' }}">
                        <i class="bi bi-person-plus-fill"></i> 
                        <span data-key="t-patient-list">Patient Registration</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('symptoms') }}" class="nav-link menu-link {{ request()->is('symptoms*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-pulse-fill"></i> 
                        <span data-key="t-symptoms">Chief Complaints</span>
                    </a>
                </li>

                {{-- PHARMACY & INVENTORY --}}
                <li class="menu-title"><span>Pharmacy</span></li>
                <li class="nav-item">
                    <a href="{{ route('listofmedicine') }}" class="nav-link menu-link {{ request()->is('*medicine*') ? 'active' : '' }}">
                        <i class="bi bi-capsule-pill"></i> 
                        <span data-key="t-medicine">Medicine List</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('downloadpdf') }}" class="nav-link menu-link {{ request()->is('*downloadpdf*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf-fill"></i> 
                        <span data-key="t-pdf">Download PDF</span>
                    </a>
                </li>

                {{-- SETTINGS & ACCOUNT --}}
                <li class="menu-title"><span>Settings</span></li>
                <li class="nav-item">
                    <a href="{{ route('frontend.profile.view') }}" class="nav-link menu-link {{ request()->is('*profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> 
                        <span data-key="t-profile">View Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('changepassword') }}" class="nav-link menu-link {{ request()->is('*changepassword*') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i> 
                        <span data-key="t-password">Change Password</span>
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-valex-logout-form">
                        @csrf
                        <a href="javascript:void(0);" onclick="document.getElementById('sidebar-valex-logout-form').submit();" class="nav-link menu-link text-danger">
                            <i class="bi bi-box-arrow-right text-danger"></i> 
                            <span data-key="t-logout">Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <!-- Doctor User Bottom Card -->
    <div class="sidebar-user">
        @php
            $loggedDoctor = Auth::guard('doctor')->user() ?? Auth::user();
            $clinicPhoto = \App\Models\doctor_clinic_document::where('doctor_id', Auth::guard('doctor')->id())
                ->when($loggedDoctor && $loggedDoctor->clinic_id, function($q) use ($loggedDoctor) {
                    $q->orWhere('clinic_id', $loggedDoctor->clinic_id);
                })->value('photo');
            $sidebarAvatar = $clinicPhoto 
                ? asset($clinicPhoto) 
                : ($loggedDoctor && $loggedDoctor->image 
                    ? asset('storage/'.$loggedDoctor->image) 
                    : ($loggedDoctor && $loggedDoctor->logo ? asset('upload/logo/'.$loggedDoctor->logo) : asset('backend/assets/images/icons8-user-default-64.png')));
        @endphp
        <div class="d-flex align-items-center gap-2">
            <img class="rounded-circle" src="{{ $sidebarAvatar }}" alt="Doctor Avatar" width="36" height="36" onerror="this.src='{{ asset('backend/assets/images/icons8-user-default-64.png') }}'">
            <div class="flex-grow-1 overflow-hidden">
                <h6 class="text-white mb-0 fs-13 text-truncate fw-semibold">Dr. {{ $loggedDoctor->name ?? 'Doctor' }}</h6>
                <small class="text-info fs-11 text-truncate d-block">{{ $loggedDoctor->specialization ?? 'General Physician' }}</small>
            </div>
            <a href="{{ route('frontend.profile.view') }}" class="text-white opacity-50 text-decoration-none" title="View Profile">
                <i class="bi bi-person-badge fs-14"></i>
            </a>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>