@extends('backend.include.layout')

@section('title', 'Doctor Password Manager')

@section('content')
<style>
    .medicine-list-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.4rem 1.6rem;
    }

    .medicine-list-card .card-header {
        background: transparent;
        border: none;
        padding: 0 0 0.8rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .medicine-list-card .card-header h5 {
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .medicine-count-badge {
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .medicine-table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        font-weight: 700;
        border-bottom: 2px solid #f1f1f1 !important;
        white-space: nowrap;
        padding: 14px 12px;
    }

    .medicine-table td {
        vertical-align: middle;
        padding: 14px 12px;
        color: #374151;
        font-size: 0.9rem;
    }

    .member-id-badge {
        background: #ecfdf5;
        color: #059669;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .status-badge {
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .status-badge.active {
        background: #ecfdf5;
        color: #059669;
    }

    .status-badge.inactive {
        background: #fef2f2;
        color: #dc3545;
    }

    .action-icon {
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 14px;
        transition: .2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .action-icon.key-icon {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 12.5px;
    }

    .action-icon.key-icon:hover {
        background: #4f46e5;
        color: #fff;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Doctor Password Manager
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Credential Administration</span> &bull; Reset and manage doctor login passwords
                </p>
            </div>
            <div>
                <a href="{{ url('/adddoctor') }}" class="btn btn-valex-light btn-sm">
                    <i class="bi bi-person-badge me-1"></i> Doctor Management
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">

                <div class="medicine-list-card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-key-fill text-primary"></i>
                            Doctor Password Management
                            <span class="medicine-count-badge">
                                {{ isset($doctor) ? count($doctor) : 0 }} total
                            </span>
                        </h5>
                    </div>
                    <hr class="mt-0 mb-3">

                    <div class="tabular-data table-responsive">
                        <table class="table table-hover medicine-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>DOCTOR ID</th>
                                    <th>DOCTOR NAME</th>
                                    <th>EMAIL ADDRESS</th>
                                    <th>CLINIC NAME</th>
                                    <th>STATUS</th>
                                    <th class="text-end pe-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctor as $doc)
                                @php
                                    $initials = strtoupper(substr($doc->name ?? 'Dr', 0, 2));
                                    $isActive = ($doc->status ?? 0) == 1;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="member-id-badge">{{ $doc->Doctor_Emp_id ?? 'DOC-'.str_pad($doc->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="valex-avatar-circle bg-primary-transparent text-primary" style="width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark fs-13">Dr. {{ $doc->name }}</h6>
                                                <small class="text-muted fs-11">{{ $doc->specialization ?? 'General Physician' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $doc->email }}</td>
                                    <td>{{ $doc->clinic_name ?? '-' }}</td>
                                    <td>
                                        @if($isActive)
                                            <span class="status-badge active">Active</span>
                                        @else
                                            <span class="status-badge inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="button"
                                                class="action-icon key-icon border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#changePasswordModal"
                                                data-employee-id="{{ $doc->id }}"
                                                data-employee-name="{{ $doc->name }}"
                                                onclick="openDoctorPassModal(this)">
                                            <i class="bi bi-key-fill"></i> Change Password
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No doctors found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: CHANGE DOCTOR PASSWORD                                             -->
<!-- ========================================================================= -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <form id="changePasswordForm" method="POST" action="{{ route('doctor.password.update.admin') }}">
                @csrf
                <div class="modal-header bg-primary text-white p-3">
                    <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="changePasswordModalLabel">
                        <i class="bi bi-key-fill"></i> Reset Password &bull; <span id="modalEmployeeName" class="fw-normal"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="employee_id" id="modalEmployeeId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="doc_new_pass" class="form-control" placeholder="Min 6 characters" required minlength="6">
                            <button class="btn btn-light border" type="button" onclick="toggleDoctorPass('doc_new_pass', 'doc_new_pass_icon')">
                                <i class="bi bi-eye-slash" id="doc_new_pass_icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                            <input type="password" name="password_confirmation" id="doc_confirm_pass" class="form-control" placeholder="Confirm new password" required minlength="6">
                            <button class="btn btn-light border" type="button" onclick="toggleDoctorPass('doc_confirm_pass', 'doc_confirm_pass_icon')">
                                <i class="bi bi-eye-slash" id="doc_confirm_pass_icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openDoctorPassModal(element) {
        const id = element.getAttribute('data-employee-id');
        const name = element.getAttribute('data-employee-name');
        document.getElementById('modalEmployeeId').value = id;
        document.getElementById('modalEmployeeName').textContent = 'Dr. ' + name;
    }

    function toggleDoctorPass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection