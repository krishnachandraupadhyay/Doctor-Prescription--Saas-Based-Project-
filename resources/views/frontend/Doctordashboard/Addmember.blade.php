@extends('frontend.include.layout')
@section('title', 'Clinic Staff Members - Doctor Portal')
@section('content')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(26, 35, 126, 0.06);
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #fff;
    }

    .card .card-header {
        background-color: #eef0ff;
        padding: 16px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        border: none;
    }

    .card .card-header .cardhead {
        font-weight: 700;
        color: #1a237e;
        font-size: 16px;
        letter-spacing: 0.2px;
    }

    .card .card-body {
        padding: 0;
    }

    .member-table {
        width: 100%;
        border-collapse: collapse;
    }

    .member-table thead th {
        background-color: #f8f9fc;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 22px;
        border-bottom: 1px solid #eef0f5;
        text-align: left;
    }

    .member-table tbody td {
        padding: 14px 22px;
        border-bottom: 1px solid #f1f2f6;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .member-table tbody tr:last-child td {
        border-bottom: none;
    }

    .member-table tbody tr:hover {
        background-color: #fafbff;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 10px;
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

    .member-name {
        font-weight: 600;
        color: #1a237e;
        display: block;
    }

    .member-email {
        font-size: 12px;
        color: #8a8fa3;
    }

    .member-id-badge {
        font-size: 12px;
        color: #4f46e5;
        background-color: #eef0ff;
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background-color: #e6f9ee;
        color: #1e9e5c;
    }

    .status-inactive {
        background-color: #fdeaea;
        color: #d9534f;
    }

    .action-icons a,
    .action-icons button.edit-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        color: #6b7280;
        margin-right: 4px;
        text-decoration: none;
        border: none;
        background: none;
        padding: 0;
        transition: background-color 0.15s ease;
        cursor: pointer;
    }

    .action-icons a:hover,
    .action-icons button.edit-icon:hover {
        background-color: #eef0ff;
        color: #4f46e5;
    }

    .action-icons a.delete-icon:hover {
        background-color: #fdeaea;
        color: #d9534f;
    }

    .empty-state {
        padding: 40px 22px;
        text-align: center;
        color: #9aa0ab;
        font-size: 14px;
    }

    /* ---- Modal styling ---- */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-header {
        background-color: #eef0ff;
        border: none;
        padding: 18px 24px;
    }

    .modal-header .modal-title {
        color: #1a237e;
        font-weight: 700;
        font-size: 17px;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-body label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        border-radius: 8px;
        border: 1px solid #e2e4ec;
        padding: 9px 12px;
        font-size: 14px;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .modal-footer {
        border: none;
        padding: 16px 24px 22px;
    }

    .btn-cancel {
        background-color: #f1f2f6;
        color: #555;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 14px;
    }

    .btn-save {
        background-color: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-save:hover {
        background-color: #4338ca;
        color: #fff;
    }
    .status-badge-btn {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s ease;
    }

    .status-badge-btn:hover {
        opacity: 0.8;
    }

    .status-badge-btn.status-active {
        background-color: #e6f9ee;
        color: #1e9e5c;
    }

    .status-badge-btn.status-inactive {
        background-color: #fdeaea;
        color: #d9534f;
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!------------------------------------------------------------------>
                <div class="card">
                    <div class="card-header">
                        <div class="cardhead">Clinic Staff &amp; Receptionists</div>
                    </div>

                    <div class="card-body">
                        @if(isset($members) && count($members) > 0)
                            <table class="member-table">
                                <thead>
                                    <tr>
                                        <th>Member ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr>
                                            <td>
                                                <span class="member-id-badge">{{ $member->member_id }}</span>
                                            </td>
                                            <td>
                                                <div class="member-info">
                                                    <div class="member-avatar">
                                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span class="member-name">{{ $member->name }}</span>
                                                        <span class="member-email">{{ $member->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ ucfirst($member->role) ?? '-' }}</td>
                                            <td>
                                                <button type="button"
                                                        class="status-badge-btn {{ ($member->status ?? '') == 'active' ? 'status-active' : 'status-inactive' }}"
                                                        data-id="{{ $member->id }}"
                                                        data-url="{{ route('member.toggleStatus', $member->id) }}">
                                                    {{ ($member->status ?? '') == 'active' ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d M Y') }}</td>
                                            <td class="action-icons">
                                                <button type="button"
                                                        class="edit-icon"
                                                        title="Edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editMemberModal"
                                                        data-update-url="{{ route('member.update', $member->id) }}"
                                                        data-name="{{ $member->name }}"
                                                        data-email="{{ $member->email }}"
                                                        data-role="{{ $member->role }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('member.delete', $member->id) }}" method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this member? You can restore them anytime from Deleted Staff.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-icon" title="Delete Member" style="border:none;background:none;padding:0;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-people" style="font-size: 28px; display:block; margin-bottom:8px;"></i>
                                No staff members assigned yet. (Staff is registered by Clinic Administration).
                            </div>
                        @endif
                    </div>
                </div>

<!-- ================= Edit Member Modal ================= -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editMemberForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_member_name">Name</label>
                        <input type="text" name="name" id="edit_member_name" class="form-control" placeholder="Enter member name" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_member_email" class="d-flex align-items-center justify-content-between">
                            <span>Email</span>
                            <small class="text-muted"><i class="bi bi-lock-fill"></i> Locked</small>
                        </label>
                        <input type="email" name="email" id="edit_member_email" class="form-control bg-light" placeholder="Email address" readonly>
                        <small class="text-muted fs-11" style="font-size: 11px; color: #6c757d;">Email cannot be edited. Contact Super Admin for changes.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_member_role">Role</label>
                        <select name="role" id="edit_member_role" class="form-select" required>
                            <option value="">Select role</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Add Member form validation ----
    var addForm = document.getElementById('addMemberForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            var nameInput = document.getElementById('member_name');
            if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
                e.preventDefault();
                alert('Member name must contain letters only (no numbers allowed).');
                return;
            }

            var password = document.getElementById('member_password').value;
            var confirmPassword = document.getElementById('member_password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password and Confirm Password do not match.');
            }
        });
    }

    var editMemberForm = document.getElementById('editMemberForm');
    if (editMemberForm) {
        editMemberForm.addEventListener('submit', function (e) {
            var nameInput = document.getElementById('edit_member_name');
            if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
                e.preventDefault();
                alert('Member name must contain letters only (no numbers allowed).');
            }
        });
    }

    // ---- Status toggle (Active/Inactive) ----
    document.querySelectorAll('.status-badge-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-url');

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error(data.message || 'Request failed with status ' + response.status);
                    }
                    return data;
                });
            })
            .then(function (data) {
                if (data.success) {
                    if (data.status === 'active') {
                        btn.classList.remove('status-inactive');
                        btn.classList.add('status-active');
                        btn.textContent = 'Active';
                    } else {
                        btn.classList.remove('status-active');
                        btn.classList.add('status-inactive');
                        btn.textContent = 'Inactive';
                    }
                }
            })
            .catch(function (error) {
                alert('Status update failed: ' + error.message);
                console.error(error);
            });
        });
    });

    // ---- Edit Member modal: fill form with row data ----
    var editModal = document.getElementById('editMemberModal');
    var editForm = document.getElementById('editMemberForm');

    editModal.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        if (!btn) return;

        var updateUrl = btn.getAttribute('data-update-url');
        var name = btn.getAttribute('data-name');
        var email = btn.getAttribute('data-email');
        var role = btn.getAttribute('data-role');

        editForm.setAttribute('action', updateUrl);
        document.getElementById('edit_member_name').value = name;
        document.getElementById('edit_member_email').value = email;
        document.getElementById('edit_member_role').value = role;
    });
});
</script>

@endsection