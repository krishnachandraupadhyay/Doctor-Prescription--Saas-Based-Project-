@extends("backend.include.layout")
@section('title', 'Onboarding Managers - Admin Console')

@section('content')
<!---------------------------------------->
<style>
    .medicine-list-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.2rem 1.4rem;
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
        font-weight: bold;
        font-size: 1.15rem;
        margin: 0;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .medicine-list-card .card-header h5 i {
        color: #2563eb;
    }

    .medicine-count-badge {
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .addmedicine a {
        border: none;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        background-color: #4f46e5;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .3s;
    }

    .addmedicine a:hover {
        background-color: #372fd3;
        color: #fff;
    }

    /* ---------------- Table ---------------- */
    .medicine-table th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6b7280;
        border-bottom: 2px solid #f1f1f1 !important;
        white-space: nowrap;
    }

    .medicine-table td {
        vertical-align: middle;
    }

    .medicine-name-cell {
        font-weight: 600;
        color: #111827;
    }

    .member-id-badge {
        background: #ecfdf5;
        color: #059669;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
        letter-spacing: 0.02em;
    }

    .status-badge {
        border-radius: 8px;
        padding: 4px 10px;
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
        padding: 6px 9px;
        font-size: 15px;
        transition: .2s;
        display: inline-flex;
        text-decoration: none;
    }

    .action-icon.edit-icon {
        background: #f3f4f6;
        color: #374151;
    }
    .action-icon.grant-icon{
        background:#cbf5dd;
        color:white;
    }
    .action-icon.grant-icon:hover{
        background:#aaf0c9;
        color:white;
    }

    .action-icon.edit-icon:hover {
        background: rgb(207, 217, 236);
        color: #111827;
    }

    .action-icon.delete-icon {
        background: #fef2f2;
        color: #dc3545;
        cursor: pointer;
    }

    .action-icon.delete-icon:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    /* ---------------- Pagination footer ---------------- */
    .pagination-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f1f1;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .pagination-footer nav .pagination {
        margin-bottom: 0;
    }

    .pagination-footer .page-link {
        border: 1px solid #e5e7eb;
        color: #374151;
        border-radius: 8px;
        margin: 0 2px;
        font-size: 0.85rem;
        padding: 0.4rem 0.7rem;
    }

    .pagination-footer .page-link:hover {
        background-color: #eef2ff;
        color: #4f46e5;
        border-color: #e5e7eb;
    }

    .pagination-footer .page-item.active .page-link {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }

    .pagination-footer .page-item.disabled .page-link {
        color: #c1c5cc;
        background-color: #fff;
    }

    @media(max-width:650px) {
        .medicine-list-card .card-header {
            gap: 0.6rem;
        }
        .tabular-data {
            width: 100%;
            overflow-x: auto;
        }
        .pagination-footer {
            justify-content: center;
        }
    }
    .status-badge {
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
    cursor: pointer;
    transition: .2s;
}

.status-badge.active {
    background: #ecfdf5;
    color: #059669;
}

.status-badge.active:hover {
    background: #d1fae5;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #dc3545;
}

.status-badge.inactive:hover {
    background: #fee2e2;
}
</style>
<!---------------------------------------->
<div @class(['page-content', 'wrapper'])>
    <div @class(['container-fluid'])>
        <div @class(['row'])>
            <div @class(['col-xxl-12'])>

                <div @class(['medicine-list-card'])>
                    <div @class(['card-header'])>
                        <h5>
                            <i @class(['bi', 'bi-people-fill'])></i>
                            Onboarding Manager
                           <span @class(['medicine-count-badge'])>
                             {{ method_exists($users, 'total') ? $users->total() : $users->count() }} total
                             </span>
                        </h5>
                        <div @class(['addmedicine', 'd-flex', 'align-items-center', 'gap-2'])>
                           <a href="{{ url('/deletedonboarding') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1" style="background: #fff; color: #dc3545; border: 1px solid #dc3545;">
                              <i class="bi bi-trash3"></i> Deleted Members
                           </a>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#formModal">
                              <i @class(['bi', 'bi-plus-lg'])></i> Add Onboarding
                           </a>
                        </div>
                    </div>
                    <hr>
                    <div @class(['tabular-data', 'table-responsive'])>
                        <table @class(['table', 'table-hover', 'medicine-table'])>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th @class(['text-end'])>Action</th>
                            </tr>
                            @forelse($users as $user)
                            <tr>
                                <td><span @class(['member-id-badge'])>{{ $user->member_id }}</span></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->created_by }}</td>
                                <td>{{ $user->updated_by }}</td>
                                <td @class(['text-end'])>
                                    <a href="#"
                                       @class(['action-icon', 'edit-icon', 'editBtn'])
                                       data-id="{{ $user->id }}"
                                       data-name="{{ $user->name }}"
                                       data-email="{{ $user->email }}"
                                       data-phone="{{ $user->phone }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editModal">
                                        <i @class(['bi', 'bi-pencil-fill'])></i>
                                    </a>
                                    <a href="#" @class(['action-icon','grant-icon','grantbtn'])><i @class(['bi', 'bi-shield-check'])></i></a>


                                    <form action="{{ route('onboarding.delete', $user->id) }}"
                                          method="POST" @class(['d-inline', 'deleteForm'])>
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @class(['action-icon', 'delete-icon', 'deleteBtn', 'border-0'])>
                                            <i @class(['bi', 'bi-trash'])></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" @class(['text-center', 'text-muted', 'py-4'])>No onboarding members found.</td>
                            </tr>
                            @endforelse

                        </table>
                    </div>

                    <!-- ================= Pagination ================= -->
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                    <div @class(['pagination-footer'])>
                        <div @class(['pagination-info'])>
                            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} onboarding members
                        </div>
                        <nav>
                            {{ $users->links() }}
                        </nav>
                    </div>
                    @endif
                    <!-- ================= /Pagination ================= -->

                </div>

            </div>
        </div>
    </div>

<!------------Add User modal---------->
<div @class(['modal', 'fade']) id="formModal" tabindex="-1">
    <div @class(['modal-dialog', 'modal-lg'])>
        <div @class(['modal-content'])>

            <div @class(['modal-header'])>
                <h5 @class(['modal-title'])>Add Onboarding Member</h5>
                <button type="button" @class(['btn-close']) data-bs-dismiss="modal"></button>
            </div>

            <div @class(['modal-body'])>

                <form action="{{ route('onboarding.add') }}" method="POST" id="addUserForm">
                    @csrf

                    <div id="userContainer">

                        <div @class(['row', 'mb-3', 'user-row', 'border-bottom', 'pb-3'])>

                            <div @class(['col-12', 'col-md-6'])>
                                <label @class(['form-label'])>
                                    Name
                                    <span @class(['text-danger'])>*</span>
                                </label>
                                <input type="text"
                                       name="name[]"
                                       @class(['form-control'])
                                       placeholder="Enter name"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>

                            <div @class(['col-12', 'col-md-6', 'mt-3', 'mt-md-0'])>
                                <label @class(['form-label'])>
                                    Email
                                    <span @class(['text-danger'])>*</span>
                                </label>
                                <input type="email"
                                       name="email[]"
                                       @class(['form-control'])
                                       placeholder="Enter email"
                                       required>
                            </div>

                            <div @class(['col-12', 'col-md-6', 'mt-3'])>
                                <label @class(['form-label'])>
                                    Phone
                                    <span @class(['text-danger'])>*</span>
                                </label>
                                <input type="tel"
                                       name="phone[]"
                                       @class(['form-control'])
                                       placeholder="Enter 10-digit phone number"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                       required>
                            </div>

                            <div @class(['col-12', 'col-md-6', 'mt-3'])>
                                <label @class(['form-label'])>
                                    Password
                                    <span @class(['text-danger'])>*</span>
                                </label>
                                <div @class(['input-group'])>
                                    <input type="password"
                                           name="password[]"
                                           @class(['form-control', 'password-field'])
                                           placeholder="Enter password"
                                           required>
                                    <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                                        <i @class(['bi', 'bi-eye'])></i>
                                    </button>
                                </div>
                            </div>

                            <div @class(['col-12', 'col-md-6', 'mt-3'])>
                                <label @class(['form-label'])>
                                    Confirm Password
                                    <span @class(['text-danger'])>*</span>
                                </label>
                                <div @class(['input-group'])>
                                    <input type="password"
                                           name="password_confirmation[]"
                                           @class(['form-control', 'password-confirm-field'])
                                           placeholder="Re-enter password"
                                           required>
                                    <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                                        <i @class(['bi', 'bi-eye'])></i>
                                    </button>
                                </div>
                                <small @class(['text-danger', 'password-mismatch-msg']) style="display:none;">
                                    Password and Confirm Password do not match.
                                </small>
                            </div>

                            <div @class(['col-12', 'd-flex', 'justify-content-end', 'mt-2'])>
                                <button type="button"
                                        @class(['btn', 'btn-danger', 'btn-sm', 'removeRow'])
                                        style="display:none;">
                                    <i @class(['bi', 'bi-trash'])></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addRow" @class(['btn', 'btn-success'])>
                        <i @class(['bi', 'bi-plus-circle'])></i> Add More
                    </button>

                    <button type="submit" @class(['btn', 'btn-primary'])>
                        Save
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>


<!------------Edit User modal (single user only)---------->
<div @class(['modal', 'fade']) id="editModal" tabindex="-1">
    <div @class(['modal-dialog'])>
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <h5 @class(['modal-title'])>Edit Onboarding Member</h5>
                <button type="button" @class(['btn-close']) data-bs-dismiss="modal"></button>
            </div>

            <div @class(['modal-body'])>
                <form action="" method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')

                    <div @class(['row', 'mb-3'])>

                        <div @class(['col-12'])>
                            <label @class(['form-label'])>
                                Name
                                <span @class(['text-danger'])>*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="edit_user_name"
                                   @class(['form-control'])
                                   placeholder="Enter name"
                                   oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                   required>
                        </div>

                        <div @class(['col-12', 'mt-3'])>
                            <label @class(['form-label'])>
                                Email
                                <span @class(['text-danger'])>*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="edit_user_email"
                                   @class(['form-control'])
                                   placeholder="Enter email"
                                   required>
                        </div>

                        <div @class(['col-12', 'mt-3'])>
                            <label @class(['form-label'])>
                                Phone
                                <span @class(['text-danger'])>*</span>
                            </label>
                            <input type="tel"
                                   name="phone"
                                   id="edit_user_phone"
                                   @class(['form-control'])
                                   placeholder="Enter 10-digit phone number"
                                   maxlength="10"
                                   minlength="10"
                                   pattern="[0-9]{10}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                   required>
                        </div>

                        <div @class(['col-12', 'mt-3'])>
                            <label @class(['form-label'])>
                                New Password
                                <small @class(['text-muted'])>(leave blank to keep current password)</small>
                            </label>
                            <div @class(['input-group'])>
                                <input type="password"
                                       name="password"
                                       id="edit_user_password"
                                       @class(['form-control', 'password-field'])
                                       placeholder="Enter new password">
                                <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                                    <i @class(['bi', 'bi-eye'])></i>
                                </button>
                            </div>
                        </div>

                        <div @class(['col-12', 'mt-3'])>
                            <label @class(['form-label'])>
                                Confirm New Password
                            </label>
                            <div @class(['input-group'])>
                                <input type="password"
                                       name="password_confirmation"
                                       id="edit_user_password_confirmation"
                                       @class(['form-control', 'password-confirm-field'])
                                       placeholder="Re-enter new password">
                                <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                                    <i @class(['bi', 'bi-eye'])></i>
                                </button>
                            </div>
                            <small @class(['text-danger', 'password-mismatch-msg']) style="display:none;">
                                Password and Confirm Password do not match.
                            </small>
                        </div>
                    </div>

                    <button type="submit" @class(['btn', 'btn-primary'])>
                        Update
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
    <!-- container-fluid -->
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

/* -------- Add more user rows (Add modal) -------- */
document.getElementById('addRow').addEventListener('click', function () {

    let container = document.getElementById('userContainer');

    let row = document.createElement('div');

    row.className = "row mb-3 user-row border-bottom pb-3";

    row.innerHTML = `
        <div @class(['col-12', 'col-md-6'])>
            <label @class(['form-label'])>Name <span @class(['text-danger'])>*</span></label>
            <input type="text"
                   name="name[]"
                   @class(['form-control'])
                   placeholder="Enter name"
                   oninput="this.value = this.value.replace(/[0-9]/g, '')"
                   required>
        </div>

        <div @class(['col-12', 'col-md-6', 'mt-3', 'mt-md-0'])>
            <label @class(['form-label'])>Email <span @class(['text-danger'])>*</span></label>
            <input type="email"
                   name="email[]"
                   @class(['form-control'])
                   placeholder="Enter email"
                   required>
        </div>

        <div @class(['col-12', 'col-md-6', 'mt-3'])>
            <label @class(['form-label'])>Phone <span @class(['text-danger'])>*</span></label>
            <input type="tel"
                   name="phone[]"
                   @class(['form-control'])
                   placeholder="Enter 10-digit phone number"
                   maxlength="10"
                   minlength="10"
                   pattern="[0-9]{10}"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                   required>
        </div>

        <div @class(['col-12', 'col-md-6', 'mt-3'])>
            <label @class(['form-label'])>Password <span @class(['text-danger'])>*</span></label>
            <div @class(['input-group'])>
                <input type="password"
                       name="password[]"
                       @class(['form-control', 'password-field'])
                       placeholder="Enter password"
                       required>
                <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                    <i @class(['bi', 'bi-eye'])></i>
                </button>
            </div>
        </div>

        <div @class(['col-12', 'col-md-6', 'mt-3'])>
            <label @class(['form-label'])>Confirm Password <span @class(['text-danger'])>*</span></label>
            <div @class(['input-group'])>
                <input type="password"
                       name="password_confirmation[]"
                       @class(['form-control', 'password-confirm-field'])
                       placeholder="Re-enter password"
                       required>
                <button type="button" @class(['btn', 'btn-outline-secondary', 'togglePassword'])>
                    <i @class(['bi', 'bi-eye'])></i>
                </button>
            </div>
            <small @class(['text-danger', 'password-mismatch-msg']) style="display:none;">
                Password and Confirm Password do not match.
            </small>
        </div>

        <div @class(['col-12', 'd-flex', 'justify-content-end', 'mt-2'])>
            <button type="button" @class(['btn', 'btn-danger', 'btn-sm', 'removeRow'])>
                <i @class(['bi', 'bi-trash'])></i> Remove
            </button>
        </div>
    `;

    container.appendChild(row);

    toggleRemoveButtons();
});


/* -------- Remove a row (Add modal) -------- */
document.addEventListener('click', function (e) {

    if (e.target.closest('.removeRow')) {
        e.target.closest('.user-row').remove();
        toggleRemoveButtons();
    }

});


/* -------- Show remove button only when there is more than 1 row -------- */
function toggleRemoveButtons() {
    const rows = document.querySelectorAll('#userContainer .user-row');
    rows.forEach(function (row) {
        const btn = row.querySelector('.removeRow');
        btn.style.display = rows.length > 1 ? 'inline-flex' : 'none';
    });
}


/* -------- Show/Hide password toggle (add + edit modal) -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.togglePassword');
    if (!btn) return;

    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }

});


/* -------- Password & Name/Phone validation before submit (Add modal, every row) -------- */
document.getElementById('addUserForm').addEventListener('submit', function (e) {

    let hasMismatch = false;
    let invalidFormat = false;
    let formatMsgs = [];

    document.querySelectorAll('#userContainer .user-row').forEach(function (row) {

        const nameInput     = row.querySelector('input[name="name[]"]');
        const phoneInput    = row.querySelector('input[name="phone[]"]');
        const passwordInput = row.querySelector('.password-field');
        const confirmInput  = row.querySelector('.password-confirm-field');
        const mismatchMsg   = row.querySelector('.password-mismatch-msg');

        if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
            invalidFormat = true;
            formatMsgs.push('Name must contain letters only (no numbers allowed).');
        }

        if (phoneInput && !/^[0-9]{10}$/.test(phoneInput.value.trim())) {
            invalidFormat = true;
            formatMsgs.push('Phone number must be exactly 10 digits.');
        }

        if (passwordInput.value !== confirmInput.value) {
            hasMismatch = true;
            mismatchMsg.style.display = 'block';
            confirmInput.classList.add('is-invalid');
        } else {
            mismatchMsg.style.display = 'none';
            confirmInput.classList.remove('is-invalid');
        }

    });

    if (invalidFormat) {
        e.preventDefault();
        alert(formatMsgs.join('\n'));
        return;
    }

    if (hasMismatch) {
        e.preventDefault();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Password mismatch',
                text: 'Password and Confirm Password must match in every row.',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert('Password and Confirm Password must match in every row.');
        }
    }

});


/* -------- Edit modal: fill fields from clicked row's data-* -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.editBtn');
    if (!btn) return;

    const id    = btn.getAttribute('data-id');
    const name  = btn.getAttribute('data-name');
    const email = btn.getAttribute('data-email');
    const phone = btn.getAttribute('data-phone');

    document.getElementById('edit_user_name').value  = name;
    document.getElementById('edit_user_email').value = email;
    document.getElementById('edit_user_phone').value = phone;

    // Password fields hamesha khaali rakho jab modal khule
    document.getElementById('edit_user_password').value = '';
    document.getElementById('edit_user_password_confirmation').value = '';

    document.getElementById('editUserForm').action =
        "{{ url('onboarding/update') }}/" + id;

});


/* -------- Password & Name/Phone validation before submit (Edit modal) -------- */
document.getElementById('editUserForm').addEventListener('submit', function (e) {

    const nameInput     = document.getElementById('edit_user_name');
    const phoneInput    = document.getElementById('edit_user_phone');

    if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
        e.preventDefault();
        alert('Name must contain letters only (no numbers allowed).');
        return;
    }

    if (phoneInput && !/^[0-9]{10}$/.test(phoneInput.value.trim())) {
        e.preventDefault();
        alert('Phone number must be exactly 10 digits.');
        return;
    }

    const passwordInput = document.getElementById('edit_user_password');
    const confirmInput  = document.getElementById('edit_user_password_confirmation');
    const mismatchMsg   = document.querySelector('#editUserForm .password-mismatch-msg');

    if (passwordInput.value === '' && confirmInput.value === '') {
        mismatchMsg.style.display = 'none';
        confirmInput.classList.remove('is-invalid');
        return;
    }

    if (passwordInput.value !== confirmInput.value) {
        e.preventDefault();
        mismatchMsg.style.display = 'block';
        confirmInput.classList.add('is-invalid');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Password mismatch',
                text: 'Password and Confirm Password must match.',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert('Password and Confirm Password must match.');
        }
    } else {
        mismatchMsg.style.display = 'none';
        confirmInput.classList.remove('is-invalid');
    }

});


/* -------- Deactivate / Activate: confirm before submitting -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.deactivateBtn');
    if (!btn) return;

    e.preventDefault();
    const form = btn.closest('.deactivateForm');
    const isDeactivating = btn.textContent.trim().toLowerCase() === 'deactivate';

    Swal.fire({
        title: isDeactivating ? 'Deactivate this user?' : 'Activate this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: isDeactivating ? '#f0ad4e' : '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: isDeactivating ? 'Yes, deactivate' : 'Yes, activate'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

});


/* -------- Delete: confirm before submitting -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;

    e.preventDefault();
    e.stopPropagation();

    const form = btn.closest('.deleteForm');

    Swal.fire({
        title: 'Are you sure?',
        text: "This onboarding member will be deleted permanently.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

});

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: @json(session('success')),
    timer: 2000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: @json(session('error')),
    timer: 2500,
    showConfirmButton: false
});
@endif


document.addEventListener('click', function (e) {

    // Status Toggle
    const statusBtn = e.target.closest('.statusToggleBtn');
    if (statusBtn) {
        e.preventDefault();
        const form = statusBtn.closest('.deactivateForm');
        const isCurrentlyActive = statusBtn.textContent.trim().toLowerCase() === 'active';

        Swal.fire({
            title: isCurrentlyActive ? 'Deactivate this user?' : 'Activate this user?',
            text: isCurrentlyActive
                ? 'This user will be marked Inactive.'
                : 'This user will be marked Active.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: isCurrentlyActive ? '#f0ad4e' : '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isCurrentlyActive ? 'Yes, deactivate' : 'Yes, activate'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return;
    }

    // Soft Delete
    const delBtn = e.target.closest('.deleteBtn');
    if (delBtn) {
        e.preventDefault();
        const form = delBtn.closest('.deleteForm');

        Swal.fire({
            title: 'Delete this Onboarding Member?',
            text: 'This member will be moved to the Deleted Onboarding list and will not be able to log in.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete member'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return;
    }

});
</script>
@endsection
