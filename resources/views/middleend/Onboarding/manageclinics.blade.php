@extends((Auth::check() && Auth::user()->role === 'admin') ? 'backend.include.layout' : 'middleend.include.layout')
@section('title', (Auth::check() && Auth::user()->role === 'admin') ? 'Manage Clinics - Admin Portal' : 'Manage Clinics - Onboarding Portal')

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
    .addmedicine button {
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #0162e8, #05c3fb);
        color: #fff;
        font-weight: 600;
        font-size: 0.92rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(1, 98, 232, 0.3);
    }
    .addmedicine button:hover {
        background: linear-gradient(135deg, #0152c2, #04a9d8);
        color: #fff;
        transform: translateY(-2px);
    }
    .clinic-table th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        font-weight: 700;
        border-bottom: 2px solid #f1f1f1 !important;
        white-space: nowrap;
        padding: 14px 12px;
    }
    .clinic-table td {
        vertical-align: middle;
        padding: 14px 12px;
        color: #374151;
        font-size: 0.9rem;
    }
    .member-id-badge {
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-block;
    }
    .action-icon {
        border: none;
        border-radius: 8px;
        padding: 7px 10px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin: 0 2px;
        cursor: pointer;
    }
    .action-icon.edit-icon {
        background: #f3f4f6;
        color: #374151;
    }
    .action-icon.edit-icon:hover {
        background: #e5e7eb;
        color: #111827;
    }
    .action-icon.delete-icon {
        background: #fee2e2;
        color: #dc2626;
    }
    .action-icon.delete-icon:hover {
        background: #fecaca;
        color: #b91c1c;
    }
    .action-icon.pdf-icon {
        background: #eef2ff;
        color: #4f46e5;
    }
    .action-icon.pdf-icon:hover {
        background: #e0e7ff;
        color: #3730a3;
    }
    .btn-toggle-mini {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 3px 10px !important;
        height: 24px !important;
        line-height: 1 !important;
        border-radius: 50rem !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out !important;
        text-decoration: none !important;
    }
    .btn-toggle-mini i {
        font-size: 11px !important;
        margin-right: 4px !important;
        line-height: 1 !important;
    }
    .btn-toggle-mini.is-visible {
        background-color: #0ab39c !important;
        border: 1px solid #0ab39c !important;
        color: #ffffff !important;
    }
    .btn-toggle-mini.is-visible:hover {
        background-color: #099885 !important;
        border-color: #099885 !important;
        color: #ffffff !important;
    }
    .btn-toggle-mini.is-hidden {
        background-color: #f3f6f9 !important;
        border: 1px solid #d0d7de !important;
        color: #405189 !important;
    }
    .btn-toggle-mini.is-hidden:hover {
        background-color: #e2e8f0 !important;
        border-color: #b0bcd0 !important;
        color: #1e293b !important;
    }
    .btn-verify-mini {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 3px 10px !important;
        height: 24px !important;
        line-height: 1 !important;
        border-radius: 50rem !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out !important;
        text-decoration: none !important;
    }
    .btn-verify-mini i {
        font-size: 11px !important;
        margin-right: 4px !important;
        line-height: 1 !important;
    }
    .btn-verify-mini.is-verified {
        background-color: #0ab39c !important;
        border: 1px solid #0ab39c !important;
        color: #ffffff !important;
    }
    .btn-verify-mini.is-verified:hover {
        background-color: #099885 !important;
        border-color: #099885 !important;
        color: #ffffff !important;
    }
    .btn-verify-mini.is-unverified {
        background-color: #fff3cd !important;
        border: 1px solid #ffe69c !important;
        color: #997404 !important;
    }
    .btn-verify-mini.is-unverified:hover {
        background-color: #ffe69c !important;
        border-color: #ffc107 !important;
        color: #664d03 !important;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-12">

                <div class="medicine-list-card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-hospital-fill text-primary"></i>
                            Clinic Management
                            <span class="medicine-count-badge">
                                {{ isset($clinics) ? (method_exists($clinics, 'total') ? $clinics->total() : count($clinics)) : 0 }} total
                            </span>
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('clinics.permissions') }}" class="btn btn-outline-primary btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-none">
                                <i class="bi bi-shield-check"></i> Clinic Permissions
                            </a>
                            <div class="addmedicine">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#addClinicModal">
                                    <i class="bi bi-plus-lg"></i> Add Clinic
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-3">

                    <div class="tabular-data table-responsive">
                        <table class="table table-hover clinic-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>CLINIC ID</th>
                                    <th>CLINIC NAME</th>
                                    <th>EMAIL</th>
                                    <th>PHONE</th>
                                    <th>ADDRESS</th>
                                    <th>CODE</th>
                                    <th class="text-end pe-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clinics as $clinic)
                                <tr>
                                    <td>
                                        <span class="member-id-badge">{{ $clinic->clinic_id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-13">{{ $clinic->name }}</div>
                                    </td>
                                    <td>{{ $clinic->email }}</td>
                                    <td>{{ $clinic->phone }}</td>
                                    <td>{{ Str::limit($clinic->address, 35) }}</td>
                                    <td>
                                        @if($clinic->clinic_code)
                                             <span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $clinic->clinic_code }}</span>
                                        @else
                                            <span class="text-muted fs-11">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="button"
                                                class="action-icon edit-icon"
                                                title="Edit Clinic"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editClinicModal"
                                                data-id="{{ $clinic->id }}"
                                                data-name="{{ $clinic->name }}"
                                                data-email="{{ $clinic->email }}"
                                                data-phone="{{ $clinic->phone }}"
                                                data-address="{{ $clinic->address }}"
                                                data-code="{{ $clinic->clinic_code }}"
                                                onclick="populateEditClinicModal(this)">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        <a href="{{ route('prescription.type', $clinic->id) }}"
                                           class="action-icon pdf-icon"
                                           title="Prescription Layout">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>

                                        <form action="{{ route('clinics.delete', $clinic->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon delete-icon" title="Delete Clinic">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-hospital fs-1 d-block mb-2 text-muted opacity-50"></i>
                                        No clinics registered yet. Click <b>+ Add Clinic</b> to add a new clinic.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($clinics, 'hasPages') && $clinics->hasPages())
                    <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between">
                        <div class="text-muted fs-13">
                            Showing {{ $clinics->firstItem() }} to {{ $clinics->lastItem() }} of {{ $clinics->total() }} clinics
                        </div>
                        <div>
                            {{ $clinics->links() }}
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: ADD CLINIC MODAL                                                -->
<!-- ========================================================================= -->
<div class="modal fade" id="addClinicModal" tabindex="-1" aria-labelledby="addClinicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="addClinicModalLabel">
                    <i class="bi bi-hospital-fill"></i> Add New Clinic
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('clinics.store') }}" id="addClinicForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Clinic Name --}}
                        <div class="col-md-6">
                            <label for="clinic_name" class="form-label fw-semibold fs-13">Clinic Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="clinic_name"
                                       name="name"
                                       placeholder="e.g. Apex Health Clinic"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="clinic_email" class="form-label fw-semibold fs-13">Clinic Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control"
                                       id="clinic_email"
                                       name="email"
                                       placeholder="e.g. contact@apexclinic.com"
                                       required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="clinic_password" class="form-label fw-semibold fs-13">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="clinic_password"
                                       name="password"
                                       placeholder="Min 6 characters"
                                       required minlength="6">
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="col-md-6">
                            <label for="clinic_phone" class="form-label fw-semibold fs-13">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel"
                                       class="form-control"
                                       id="clinic_phone"
                                       name="phone"
                                       placeholder="e.g. 9876543210"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                       required>
                            </div>
                        </div>

                        {{-- Clinic Code (Optional) --}}
                        <div class="col-md-6">
                            <label for="clinic_code" class="form-label fw-semibold fs-13">Clinic Code <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="clinic_code"
                                       name="clinic_code"
                                       placeholder="e.g. APX-CLN-01">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <label for="clinic_address" class="form-label fw-semibold fs-13">Clinic Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light pt-2 align-items-start"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control"
                                          id="clinic_address"
                                          name="address"
                                          rows="2"
                                          placeholder="Enter full street address, city, state, pin code"
                                          required></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Save Clinic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: EDIT CLINIC MODAL                                                -->
<!-- ========================================================================= -->
<div class="modal fade" id="editClinicModal" tabindex="-1" aria-labelledby="editClinicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="editClinicModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Clinic Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editClinicForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Clinic Name --}}
                        <div class="col-md-6">
                            <label for="edit_clinic_name" class="form-label fw-semibold fs-13">Clinic Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="edit_clinic_name"
                                       name="name"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="edit_clinic_email" class="form-label fw-semibold fs-13">Clinic Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control"
                                       id="edit_clinic_email"
                                       name="email"
                                       required>
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div class="col-md-6">
                            <label for="edit_clinic_password" class="form-label fw-semibold fs-13">New Password <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="edit_clinic_password"
                                       name="password"
                                       placeholder="Leave blank to keep existing password">
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="col-md-6">
                            <label for="edit_clinic_phone" class="form-label fw-semibold fs-13">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel"
                                       class="form-control"
                                       id="edit_clinic_phone"
                                       name="phone"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                       required>
                            </div>
                        </div>

                        {{-- Clinic Code --}}
                        <div class="col-md-6">
                            <label for="edit_clinic_code" class="form-label fw-semibold fs-13">Clinic Code <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="edit_clinic_code"
                                       name="clinic_code">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <label for="edit_clinic_address" class="form-label fw-semibold fs-13">Clinic Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light pt-2 align-items-start"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control"
                                          id="edit_clinic_address"
                                          name="address"
                                          rows="2"
                                          required></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Update Clinic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container for Notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="liveToast" class="toast align-items-center text-white bg-dark border-0 shadow-lg rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fs-13 py-2 px-3" id="toastMessage">
                Notification message
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    function populateEditClinicModal(element) {
        const id      = element.getAttribute('data-id');
        const name    = element.getAttribute('data-name');
        const email   = element.getAttribute('data-email');
        const phone   = element.getAttribute('data-phone');
        const address = element.getAttribute('data-address');
        const code    = element.getAttribute('data-code');

        document.getElementById('edit_clinic_name').value    = name || '';
        document.getElementById('edit_clinic_email').value   = email || '';
        document.getElementById('edit_clinic_phone').value   = phone || '';
        document.getElementById('edit_clinic_address').value = address || '';
        document.getElementById('edit_clinic_code').value    = code || '';
        document.getElementById('edit_clinic_password').value = '';

        document.getElementById('editClinicForm').action = '/clinics/update/' + id;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        function showNotification(msg, isSuccess = true) {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastMessage');
            if (!toastEl || !toastBody) return;

            toastBody.innerText = msg;
            toastEl.className = 'toast align-items-center text-white border-0 shadow-lg rounded-3 ' + (isSuccess ? 'bg-success' : 'bg-danger');

            const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
            toast.show();
        }

        // 1. Clinic Member module toggle
        document.querySelectorAll('.clinic-member-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-member-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_member) {
                            button.className = 'btn-toggle-mini is-visible clinic-member-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-member-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 2. Clinic Payment category toggle
        document.querySelectorAll('.clinic-pay-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-payment-category-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_payment_category) {
                            button.className = 'btn-toggle-mini is-visible clinic-pay-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-pay-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 3. Clinic Deleted Staff toggle
        document.querySelectorAll('.clinic-delstaff-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinic/${clinicId}/toggle-deleted-staff-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.has_deleted_staff) {
                            button.className = 'btn-toggle-mini is-visible clinic-delstaff-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-fill"></i><span>Visible</span>';
                            button.setAttribute('data-current', '1');
                        } else {
                            button.className = 'btn-toggle-mini is-hidden clinic-delstaff-toggle-btn';
                            button.innerHTML = '<i class="bi bi-eye-slash-fill"></i><span>Hidden</span>';
                            button.setAttribute('data-current', '0');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Status change failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        // 4. Clinic Verification toggle
        document.querySelectorAll('.clinic-verify-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const clinicId = this.getAttribute('data-id');
                const button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px;"></span>';

                fetch(`/clinics/${clinicId}/toggle-verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    if (data.success) {
                        if (data.verified) {
                            button.className = 'btn-verify-mini is-verified clinic-verify-toggle-btn';
                            button.innerHTML = '<i class="bi bi-patch-check-fill"></i><span>Verified</span>';
                            button.setAttribute('data-current', '1');
                            button.setAttribute('title', 'Verified by ' + (data.verified_by || 'Super Admin / Onboarding') + '. Click to toggle.');
                        } else {
                            button.className = 'btn-verify-mini is-unverified clinic-verify-toggle-btn';
                            button.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Unverified</span>';
                            button.setAttribute('data-current', '0');
                            button.setAttribute('title', 'Pending verification. Click to verify.');
                        }
                        showNotification(data.message, true);
                    } else {
                        button.innerHTML = 'Error';
                        showNotification('Verification update failed.', false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    button.disabled = false;
                    button.innerHTML = 'Retry';
                    showNotification('Network error occurred.', false);
                });
            });
        });

        const addForm = document.getElementById('addClinicForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const phone = document.getElementById('clinic_phone').value.trim();
                if (!/^[0-9]{10}$/.test(phone)) {
                    e.preventDefault();
                    alert('Phone number must be exactly 10 digits.');
                }
            });
        }

        const editForm = document.getElementById('editClinicForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const phone = document.getElementById('edit_clinic_phone').value.trim();
                if (!/^[0-9]{10}$/.test(phone)) {
                    e.preventDefault();
                    alert('Phone number must be exactly 10 digits.');
                }
            });
        }
    });
</script>
@endsection
