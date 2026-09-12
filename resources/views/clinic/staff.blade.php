@extends('clinic.include.layout')
@section('title', 'Clinic Staff Management - Clinic Portal')

@section('content')
<style>
    .valex-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #EEF3F1;
        box-shadow: 0 4px 16px -4px rgba(15, 59, 56, 0.08);
        overflow: hidden;
    }
    .valex-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FAFCFB;
        border-bottom: 1px solid #EEF3F1;
        padding: 16px 20px;
    }
    .valex-table th {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #718096;
        font-weight: 700;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .valex-table td {
        font-size: 13.5px;
        color: #1a202c;
        vertical-align: middle;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .member-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #e7eaff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    .member-id-badge {
        font-size: 12px;
        color: #4f46e5;
        background-color: #eef0ff;
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 600;
    }
    .status-badge-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s ease;
    }
    .status-badge-btn:hover {
        opacity: 0.85;
    }
    .status-badge-btn.status-active {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-badge-btn.status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .action-btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        border: none;
        background: none;
        transition: all 0.15s ease;
    }
    .action-btn-icon:hover {
        background-color: #eef0ff;
        color: #4f46e5;
    }
    .action-btn-icon.delete-btn:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .modal-header-gradient {
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        color: #fff;
    }
    .modal-header-gradient .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Page Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    {{-- Left: Clinic Staff Info --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Clinic Staff Directory</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->name }}
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                Manage Receptionists and Clinical Support Staff assigned to this clinic.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @if(!empty($clinic->has_deleted_staff))
                            <a href="{{ route('clinic.deleted_staff') }}" class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3 fw-semibold d-flex align-items-center gap-1.5">
                                <i class="bi bi-trash3"></i> Deleted Staff
                            </a>
                        @endif
                        <button type="button" class="btn btn-primary btn-sm px-3.5 py-2 rounded-3 fw-semibold shadow-sm d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="bi bi-person-plus-fill"></i> Add Staff Member
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Staff Table Card --}}
        <div class="valex-card mb-4">
            <div class="valex-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-primary fs-16"></i>
                    <h5 class="fw-bold text-dark mb-0 fs-15">Active Clinic Staff</h5>
                </div>
                <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-12 fw-semibold">
                    Total Staff: {{ count($members) }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table valex-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Staff ID</th>
                            <th>Staff Name &amp; Email</th>
                            <th>Role</th>
                            <th>Assigned Doctor</th>
                            <th>Status</th>
                            <th>Verification</th>
                            <th>Joined Date</th>
                            <th class="text-center" style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        @php
                            $isVerified = ($member->verified ?? 0) == 1;
                        @endphp
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="member-id-badge">{{ $member->member_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="member-avatar">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark fs-14">{{ $member->name }}</span>
                                            <small class="text-muted">{{ $member->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(strtolower($member->role) === 'receptionist')
                                        <span class="badge" style="background:#EDE9FE; color:#6D28D9; border:1px solid #DDD6FE; border-radius:12px; padding:4px 10px; font-size:11.5px;">
                                            <i class="bi bi-person-badge me-1"></i> Receptionist
                                        </span>
                                    @else
                                        <span class="badge" style="background:#E0F2FE; color:#0369A1; border:1px solid #BAE6FD; border-radius:12px; padding:4px 10px; font-size:11.5px;">
                                            <i class="bi bi-person-workspace me-1"></i> Staff
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->doctor)
                                        <span class="fw-semibold text-secondary fs-13">
                                            <i class="bi bi-person-badge text-primary me-1"></i>Dr. {{ $member->doctor->name }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-12">Clinic Wide</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="status-badge-btn {{ ($member->status ?? '') == 'active' ? 'status-active' : 'status-inactive' }}"
                                            data-id="{{ $member->id }}"
                                            data-url="{{ route('clinic.staff.toggleStatus', $member->id) }}"
                                            title="Click to toggle status">
                                        <i class="bi {{ ($member->status ?? '') == 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ ($member->status ?? '') == 'active' ? 'Active' : 'Inactive' }}</span>
                                    </button>
                                </td>
                                <td>
                                    @if($isVerified)
                                        <a href="{{ route('clinic.staff.verify', $member->id) }}"
                                           class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none staff-verify-btn"
                                           data-url="{{ route('clinic.staff.toggle-verify', $member->id) }}"
                                           title="{{ $member->verified_by ? 'Verified by ' . $member->verified_by . '. Click to unverify.' : 'Verified by Clinic. Click to unverify.' }}">
                                            <i class="bi bi-shield-check me-1"></i><span>Verified</span>
                                        </a>
                                    @else
                                        <a href="{{ route('clinic.staff.verify', $member->id) }}"
                                           class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none staff-verify-btn"
                                           data-url="{{ route('clinic.staff.toggle-verify', $member->id) }}"
                                           title="Pending verification by Clinic. Click to verify.">
                                            <i class="bi bi-hourglass-split me-1"></i><span>Pending</span>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fs-12">{{ \Carbon\Carbon::parse($member->created_at)->format('d M Y') }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        {{-- Verify Icon --}}
                                        <a href="{{ route('clinic.staff.verify', $member->id) }}"
                                           class="action-btn-icon staff-verify-icon-btn"
                                           style="background: {{ $isVerified ? '#ecfdf5' : '#fffbeb' }}; color: {{ $isVerified ? '#059669' : '#d97706' }};"
                                           data-url="{{ route('clinic.staff.toggle-verify', $member->id) }}"
                                           title="{{ $isVerified ? 'Click to Unverify' : 'Click to Verify' }}">
                                            <i class="bi {{ $isVerified ? 'bi-patch-check-fill' : 'bi-patch-check' }}"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn-icon edit-btn"
                                                title="Edit Staff Member"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStaffModal"
                                                data-update-url="{{ route('clinic.staff.update', $member->id) }}"
                                                data-name="{{ $member->name }}"
                                                data-email="{{ $member->email }}"
                                                data-role="{{ $member->role }}"
                                                data-doctor-id="{{ $member->doctor_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('clinic.staff.destroy', $member->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this staff member? You can restore them from Deleted Staff.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-icon delete-btn" title="Delete Member">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-people text-muted" style="font-size: 42px; opacity: 0.4;"></i>
                                        <h6 class="fw-bold text-secondary mt-3">No Staff Members Added Yet</h6>
                                        <p class="text-muted fs-13 mb-3">Click the button below to add receptionists or support staff to this clinic.</p>
                                        <button type="button" class="btn btn-primary btn-sm px-3 rounded-2" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                            <i class="bi bi-person-plus-fill me-1"></i> Add First Staff Member
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>

<!-- ================= Add Staff Modal ================= -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="{{ route('clinic.staff.store') }}" method="POST" id="addStaffForm">
                @csrf
                <div class="modal-header modal-header-gradient py-3">
                    <h5 class="modal-title fs-16 fw-bold" id="addStaffModalLabel">
                        <i class="bi bi-person-plus-fill me-1"></i> Add New Staff Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary fs-13">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="add_member_name" class="form-control" placeholder="Enter staff name" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary fs-13">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="add_member_email" class="form-control" placeholder="Enter email address" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="add_member_password" class="form-control" placeholder="Min 6 chars" required minlength="6">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="add_member_password_confirmation" class="form-control" placeholder="Re-enter password" required minlength="6">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Staff Role <span class="text-danger">*</span></label>
                            <select name="role" id="add_member_role" class="form-select" required>
                                <option value="receptionist" selected>Receptionist</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Assigned Doctor</label>
                            <select name="doctor_id" id="add_member_doctor" class="form-select">
                                @if(count($doctors) > 0)
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}">Dr. {{ $doc->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">Clinic Default</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm px-3 text-white fw-semibold rounded-2" style="background: linear-gradient(135deg, #7b2ff7, #f107a3);">
                        <i class="bi bi-check-lg me-1"></i> Save Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= Edit Staff Modal ================= -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form action="" method="POST" id="editStaffForm">
                @csrf
                <div class="modal-header modal-header-gradient py-3">
                    <h5 class="modal-title fs-16 fw-bold" id="editStaffModalLabel">
                        <i class="bi bi-pencil-square me-1"></i> Edit Staff Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary fs-13">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_member_name" class="form-control" placeholder="Enter staff name" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary fs-13 d-flex align-items-center justify-content-between">
                            <span>Email Address</span>
                            <span class="badge bg-secondary-subtle text-secondary fs-11 fw-normal"><i class="bi bi-lock-fill me-1"></i>Locked</span>
                        </label>
                        <input type="email" name="email" id="edit_member_email" class="form-control bg-light" placeholder="Email address" readonly>
                        <small class="text-muted fs-11"><i class="bi bi-info-circle me-1"></i>Email cannot be edited. Contact Super Admin for email modifications.</small>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Staff Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_member_role" class="form-select" required>
                                <option value="receptionist">Receptionist</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary fs-13">Assigned Doctor</label>
                            <select name="doctor_id" id="edit_member_doctor" class="form-select">
                                @if(count($doctors) > 0)
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}">Dr. {{ $doc->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">Clinic Default</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm px-3 text-white fw-semibold rounded-2" style="background: linear-gradient(135deg, #7b2ff7, #f107a3);">
                        <i class="bi bi-check-lg me-1"></i> Update Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Add Staff validation ----
    const addForm = document.getElementById('addStaffForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            const pass = document.getElementById('add_member_password').value;
            const confirmPass = document.getElementById('add_member_password_confirmation').value;
            if (pass !== confirmPass) {
                e.preventDefault();
                alert('Password and Confirm Password do not match.');
            }
        });
    }

    // ---- Status toggle ----
    document.querySelectorAll('.status-badge-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const url = btn.getAttribute('data-url');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            btn.style.opacity = '0.5';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.style.opacity = '1';
                if (data.success) {
                    if (data.status === 'active') {
                        btn.className = 'status-badge-btn status-active';
                        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i><span>Active</span>';
                    } else {
                        btn.className = 'status-badge-btn status-inactive';
                        btn.innerHTML = '<i class="bi bi-x-circle-fill"></i><span>Inactive</span>';
                    }
                }
            })
            .catch(err => {
                btn.style.opacity = '1';
                console.error(err);
                alert('Status update failed.');
            });
        });
    });

    // ---- Verification toggle ----
    document.querySelectorAll('.staff-verify-btn, .staff-verify-icon-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = btn.getAttribute('data-url');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            btn.style.opacity = '0.5';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.style.opacity = '1';
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Verification toggle failed.');
                }
            })
            .catch(err => {
                btn.style.opacity = '1';
                console.error(err);
                window.location.reload();
            });
        });
    });

    // ---- Edit modal fill ----
    const editModal = document.getElementById('editStaffModal');
    const editForm = document.getElementById('editStaffForm');

    editModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if (!btn) return;

        const updateUrl = btn.getAttribute('data-update-url');
        const name      = btn.getAttribute('data-name');
        const email     = btn.getAttribute('data-email');
        const role      = btn.getAttribute('data-role');
        const doctorId  = btn.getAttribute('data-doctor-id');

        editForm.setAttribute('action', updateUrl);
        document.getElementById('edit_member_name').value = name;
        document.getElementById('edit_member_email').value = email;
        document.getElementById('edit_member_role').value = role;
        if (document.getElementById('edit_member_doctor') && doctorId) {
            document.getElementById('edit_member_doctor').value = doctorId;
        }
    });
});
</script>
@endsection
