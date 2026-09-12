@extends("frontend.include.layout")

@section('title', 'Change Password - Doctor Portal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Breadcrumb Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Change Password
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Doctor Security</span> &bull; Manage your login credentials and security settings
                </p>
            </div>
            <div>
                <a href="{{ url('/doctor/dashboard') }}" class="btn btn-valex-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Full-Width Clean Valex Card -->
        <div class="row">
            <div class="col-12">
                <div class="valex-card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                    
                    @php
                        $loggedDoctor = Auth::guard('doctor')->user() ?? Auth::user();
                    @endphp

                    <div class="valex-card-header d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill fs-5 text-primary"></i>
                            <div>
                                <h5 class="valex-card-title mb-0 fw-bold fs-15">Doctor Password &amp; Security</h5>
                                <small class="text-muted fs-12">Ensure your doctor portal credentials are secure and updated regularly.</small>
                            </div>
                        </div>
                        <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fs-12 fw-semibold">
                            <i class="bi bi-person-badge-fill me-1"></i> Dr. {{ $loggedDoctor->name ?? 'Doctor' }}
                        </span>
                    </div>

                    <div class="valex-card-body p-4">
                        <form method="POST" action="{{ route('doctor.password.update') }}">
                            @csrf

                            <div class="row g-4">
                                
                                {{-- Current Password --}}
                                <div class="col-lg-4 col-md-6 col-12">
                                    <label for="doc_current_password" class="form-label fw-semibold fs-13 text-dark">
                                        Current Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                        <input type="password"
                                               class="form-control border-start-0 border-end-0"
                                               id="doc_current_password"
                                               name="current_password"
                                               placeholder="Enter current password"
                                               required
                                               autofocus>
                                        <button class="btn btn-light border border-start-0" type="button" onclick="toggleDoctorPasswordVisibility('doc_current_password', 'doc_current_pass_icon')">
                                            <i class="bi bi-eye-slash text-muted" id="doc_current_pass_icon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted fs-11 mt-1 d-block">Enter your existing doctor login password.</small>
                                </div>

                                {{-- New Password --}}
                                <div class="col-lg-4 col-md-6 col-12">
                                    <label for="doc_new_password" class="form-label fw-semibold fs-13 text-dark">
                                        New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                        <input type="password"
                                               class="form-control border-start-0 border-end-0"
                                               id="doc_new_password"
                                               name="new_password"
                                               placeholder="Enter new password"
                                               required minlength="8">
                                        <button class="btn btn-light border border-start-0" type="button" onclick="toggleDoctorPasswordVisibility('doc_new_password', 'doc_new_pass_icon')">
                                            <i class="bi bi-eye-slash text-muted" id="doc_new_pass_icon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted fs-11 mt-1 d-block">Must be at least 8 characters long.</small>
                                </div>

                                {{-- Confirm New Password --}}
                                <div class="col-lg-4 col-md-6 col-12">
                                    <label for="doc_new_password_confirmation" class="form-label fw-semibold fs-13 text-dark">
                                        Confirm New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                                        <input type="password"
                                               class="form-control border-start-0 border-end-0"
                                               id="doc_new_password_confirmation"
                                               name="new_password_confirmation"
                                               placeholder="Confirm new password"
                                               required minlength="8">
                                        <button class="btn btn-light border border-start-0" type="button" onclick="toggleDoctorPasswordVisibility('doc_new_password_confirmation', 'doc_confirm_pass_icon')">
                                            <i class="bi bi-eye-slash text-muted" id="doc_confirm_pass_icon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted fs-11 mt-1 d-block">Re-enter the new password exactly.</small>
                                </div>

                            </div>

                            <!-- Bottom Action Buttons -->
                            <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="reset" class="btn btn-light px-4">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-valex-primary px-4 fw-semibold shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Update Password
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleDoctorPasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
            icon.classList.remove('text-muted');
            icon.classList.add('text-primary');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
            icon.classList.remove('text-primary');
            icon.classList.add('text-muted');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('doctor.password.update') }}"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const newPass = document.getElementById('doc_new_password').value;
                const confirmPass = document.getElementById('doc_new_password_confirmation').value;

                if (newPass !== confirmPass) {
                    e.preventDefault();
                    alert('New Password and Confirm New Password do not match.');
                }
            });
        }
    });
</script>
@endsection
