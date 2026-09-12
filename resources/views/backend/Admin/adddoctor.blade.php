@extends("backend.include.layout")

@section('title', 'Doctor Management')

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

    .medicine-list-card .card-header h5 i {
        color: #0162e8;
        font-size: 1.4rem;
    }

    .medicine-count-badge {
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .addmedicine a, .addmedicine button {
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #0162e8, #05c3fb);
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(1, 98, 232, 0.3);
    }

    .addmedicine a:hover, .addmedicine button:hover {
        background: linear-gradient(135deg, #0152c2, #04a9d8);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(1, 98, 232, 0.4);
    }

    /* ---------------- Table ---------------- */
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
        letter-spacing: 0.02em;
    }

    .status-badge {
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
        transition: .2s;
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
        padding: 7px 10px;
        font-size: 14px;
        transition: .2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin: 0 2px;
    }

    .action-icon.edit-icon {
        background: #f3f4f6;
        color: #374151;
    }

    .action-icon.edit-icon:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .action-icon.grant-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .action-icon.grant-icon:hover {
        background: #bbf7d0;
        color: #166534;
    }

    .action-icon.delete-icon {
        background: #fee2e2;
        color: #dc2626;
        cursor: pointer;
    }

    .action-icon.delete-icon:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    .valex-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
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
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-12">

                <!-- Main Card Container Matching Image Design -->
                <div class="medicine-list-card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-people-fill"></i>
                            Doctor Management
                            <span class="medicine-count-badge">
                                {{ isset($doctors) ? count($doctors) : 0 }} total
                            </span>
                        </h5>
                        <div class="addmedicine">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                <i class="bi bi-plus-lg"></i> Add Doctor
                            </button>
                        </div>
                    </div>
                    <hr class="mt-0 mb-3">

                    <div class="tabular-data table-responsive">
                        <table class="table table-hover medicine-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>DOCTOR ID</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>CLINIC NAME</th>
                                    <th>STATUS</th>
                                    <th>VERIFICATION</th>
                                    <th class="text-end pe-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctors as $key => $doctor)
                                @php
                                    $initials = strtoupper(substr($doctor->name ?? 'Dr', 0, 2));
                                    $isActive = ($doctor->status ?? 0) == 1;
                                    $isVerified = ($doctor->verified ?? 0) == 1;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="member-id-badge">{{ $doctor->Doctor_Emp_id ?? 'DOC-'.str_pad($doctor->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="valex-avatar-circle bg-primary-transparent text-primary">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark fs-13">Dr. {{ $doctor->name }}</h6>
                                                <small class="text-muted fs-11">{{ $doctor->specialization ?? 'General Physician' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $doctor->email }}</td>
                                    <td>{{ $doctor->clinic_name ?? '-' }}</td>
                                    <td>
                                        @if($isActive)
                                            <a href="{{ route('doctor.toggle_status', $doctor->id) }}"
                                               class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                               title="Click to Deactivate Doctor">
                                                <i class="bi bi-check-circle-fill me-1"></i> Active
                                            </a>
                                        @else
                                            <a href="{{ route('doctor.toggle_status', $doctor->id) }}"
                                               class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                               title="Click to Activate Doctor">
                                                <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isVerified)
                                            <a href="{{ route('verifydoctor', $doctor->id) }}"
                                               class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                               title="Click to Unverify Doctor">
                                                <i class="bi bi-shield-check me-1"></i> Verified
                                            </a>
                                        @else
                                            <a href="{{ route('verifydoctor', $doctor->id) }}"
                                               class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold text-decoration-none"
                                               title="Click to Verify Doctor">
                                                <i class="bi bi-hourglass-split me-1"></i> Pending
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        {{-- Toggle Status Button --}}
                                        <a href="{{ route('doctor.toggle_status', $doctor->id) }}"
                                           class="action-icon {{ $isActive ? 'edit-icon' : 'grant-icon' }}"
                                           title="{{ $isActive ? 'Deactivate Doctor' : 'Activate Doctor' }}">
                                            <i class="bi {{ $isActive ? 'bi-toggle-on text-success fs-16' : 'bi-toggle-off text-muted fs-16' }}"></i>
                                        </a>

                                        {{-- Verify Button --}}
                                        <a href="{{ route('verifydoctor', $doctor->id) }}"
                                           class="action-icon grant-icon"
                                           title="{{ $isVerified ? 'Doctor Verified (Click to Unverify)' : 'Click to Verify Doctor' }}">
                                            <i class="bi {{ $isVerified ? 'bi-patch-check-fill text-success' : 'bi-patch-check text-warning' }}"></i>
                                        </a>

                                        {{-- Edit Button (Opens Edit Modal) --}}
                                        <a href="#"
                                           class="action-icon edit-icon"
                                           title="Edit Doctor Details"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editDoctorModal"
                                           data-id="{{ $doctor->id }}"
                                           data-name="{{ $doctor->name }}"
                                           data-email="{{ $doctor->email }}"
                                           data-phone="{{ $doctor->phone }}"
                                           data-clinic="{{ $doctor->clinic_name }}"
                                           data-clinic-id="{{ $doctor->clinic_id }}"
                                           data-specialization="{{ $doctor->specialization }}"
                                           onclick="populateEditModal(this)">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        {{-- View Button --}}
                                        <a href="{{ route('viewdoctor', $doctor->id) }}" class="action-icon edit-icon" title="View Doctor Profile">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Soft Delete Button --}}
                                        <form action="{{ route('doctor.destroy', $doctor->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this doctor? You can restore them anytime from the Deleted Doctors page.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon delete-icon" title="Delete Doctor">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-people fs-2 d-block mb-2 text-muted opacity-50"></i>
                                        No doctors registered yet. Click <b>+ Add Doctor</b> to register a new doctor.
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
<!-- MODAL 1: ADD DOCTOR MODAL                                                 -->
<!-- ========================================================================= -->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="addDoctorModalLabel">
                    <i class="bi bi-person-plus-fill"></i> Add New Doctor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('doctor.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Doctor Name --}}
                        <div class="col-md-6">
                            <label for="doctor_name" class="form-label fw-semibold fs-13">Doctor Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="doctor_name"
                                       name="name"
                                       placeholder="e.g. Dr. John Doe"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="doctor_email" class="form-label fw-semibold fs-13">Doctor Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control"
                                       id="doctor_email"
                                       name="email"
                                       placeholder="e.g. doctor@clinic.com"
                                       required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="doctor_password" class="form-label fw-semibold fs-13">Login Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="doctor_password"
                                       name="password"
                                       placeholder="Min 8 characters"
                                       required>
                                <button class="btn btn-light border" type="button" onclick="togglePassVisibility('doctor_password', 'doctor_password_icon')">
                                    <i class="bi bi-eye-slash" id="doctor_password_icon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Clinic Selection (Registered Clinics) --}}
                        <div class="col-md-6">
                            <label for="doctor_clinic_id" class="form-label fw-semibold fs-13">Select Clinic <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <select class="form-select" id="doctor_clinic_id" name="clinic_id" required onchange="handleAdminClinicSelect(this)">
                                    <option value="">-- Choose Registered Clinic --</option>
                                    @if(isset($clinics) && count($clinics) > 0)
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" data-name="{{ $clinic->name }}" {{ (old('clinic_id') == $clinic->id || (isset($clinicUser) && $clinicUser && $clinicUser->id == $clinic->id)) ? 'selected' : '' }}>
                                                {{ $clinic->name }} ({{ $clinic->clinic_id }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" id="doctor_clinic_name" name="clinic_name" value="">
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="doctor_phone" class="form-label fw-semibold fs-13">Contact / Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel"
                                       class="form-control"
                                       id="doctor_phone"
                                       name="phone"
                                       placeholder="e.g. 9876543210"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            </div>
                        </div>

                        {{-- Specialization --}}
                        <div class="col-md-6">
                            <label for="doctor_specialization" class="form-label fw-semibold fs-13">Specialization</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-award"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="doctor_specialization"
                                       name="specialization"
                                       placeholder="e.g. Cardiologist / General Physician">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Save Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: EDIT DOCTOR MODAL                                                -->
<!-- ========================================================================= -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white fw-bold fs-15" id="editDoctorModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Doctor Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDoctorForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        {{-- Doctor Name --}}
                        <div class="col-md-6">
                            <label for="edit_doctor_name" class="form-label fw-semibold fs-13">Doctor Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="edit_doctor_name"
                                       name="name"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="edit_doctor_email" class="form-label fw-semibold fs-13">Doctor Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control"
                                       id="edit_doctor_email"
                                       name="email"
                                       required>
                            </div>
                        </div>

                        {{-- Clinic / Hospital Name --}}
                        <div class="col-md-6">
                            <label for="edit_doctor_clinic_id" class="form-label fw-semibold fs-13">Select Clinic</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
                                <select class="form-select" id="edit_doctor_clinic_id" name="clinic_id" onchange="handleAdminEditClinicSelect(this)">
                                    <option value="">-- Choose Registered Clinic --</option>
                                    @if(isset($clinics) && count($clinics) > 0)
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" data-name="{{ $clinic->name }}">
                                                {{ $clinic->name }} ({{ $clinic->clinic_id }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" id="edit_doctor_clinic" name="clinic_name" value="">
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="edit_doctor_phone" class="form-label fw-semibold fs-13">Contact / Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel"
                                       class="form-control"
                                       id="edit_doctor_phone"
                                       name="phone"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            </div>
                        </div>

                        {{-- Specialization --}}
                        <div class="col-md-12">
                            <label for="edit_doctor_specialization" class="form-label fw-semibold fs-13">Specialization</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-award"></i></span>
                                <input type="text"
                                       class="form-control"
                                       id="edit_doctor_specialization"
                                       name="specialization">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Update Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassVisibility(inputId, iconId) {
        const passInput = document.getElementById(inputId);
        const passIcon = document.getElementById(iconId);
        if (passInput.type === 'password') {
            passInput.type = 'text';
            passIcon.classList.remove('bi-eye-slash');
            passIcon.classList.add('bi-eye');
        } else {
            passInput.type = 'password';
            passIcon.classList.remove('bi-eye');
            passIcon.classList.add('bi-eye-slash');
        }
    }

    function handleAdminClinicSelect(selectEl) {
        const option = selectEl.options[selectEl.selectedIndex];
        const clinicName = option ? option.getAttribute('data-name') : '';
        const nameInput = document.getElementById('doctor_clinic_name');
        if (nameInput) {
            nameInput.value = clinicName || '';
        }
    }

    function handleAdminEditClinicSelect(selectEl) {
        const option = selectEl.options[selectEl.selectedIndex];
        const clinicName = option ? option.getAttribute('data-name') : '';
        const nameInput = document.getElementById('edit_doctor_clinic');
        if (nameInput) {
            nameInput.value = clinicName || '';
        }
    }

    function populateEditModal(element) {
        const id = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const email = element.getAttribute('data-email');
        const phone = element.getAttribute('data-phone');
        const clinic = element.getAttribute('data-clinic');
        const clinicId = element.getAttribute('data-clinic-id');
        const specialization = element.getAttribute('data-specialization');

        document.getElementById('edit_doctor_name').value = name || '';
        document.getElementById('edit_doctor_email').value = email || '';
        document.getElementById('edit_doctor_phone').value = phone || '';
        document.getElementById('edit_doctor_clinic').value = clinic || '';
        document.getElementById('edit_doctor_specialization').value = specialization || '';

        const editClinicSelect = document.getElementById('edit_doctor_clinic_id');
        if (editClinicSelect) {
            editClinicSelect.value = clinicId || '';
        }

        document.getElementById('editDoctorForm').action = '/editdoctor/' + id;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addDocForm = document.querySelector('#addDoctorModal form');
        if (addDocForm) {
            addDocForm.addEventListener('submit', function(e) {
                let isValid = true;
                let msgs = [];
                const nameInput = document.getElementById('doctor_name');
                const phoneInput = document.getElementById('doctor_phone');

                if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
                    isValid = false;
                    msgs.push('Doctor name must contain letters only (no numbers allowed).');
                }
                if (phoneInput && phoneInput.value.trim() !== '' && !/^[0-9]{10}$/.test(phoneInput.value.trim())) {
                    isValid = false;
                    msgs.push('Phone number must be exactly 10 digits.');
                }
                if (!isValid) {
                    e.preventDefault();
                    alert(msgs.join('\n'));
                }
            });
        }

        const editDocForm = document.getElementById('editDoctorForm');
        if (editDocForm) {
            editDocForm.addEventListener('submit', function(e) {
                let isValid = true;
                let msgs = [];
                const nameInput = document.getElementById('edit_doctor_name');
                const phoneInput = document.getElementById('edit_doctor_phone');

                if (nameInput && (/[0-9]/.test(nameInput.value.trim()) || !nameInput.value.trim())) {
                    isValid = false;
                    msgs.push('Doctor name must contain letters only (no numbers allowed).');
                }
                if (phoneInput && phoneInput.value.trim() !== '' && !/^[0-9]{10}$/.test(phoneInput.value.trim())) {
                    isValid = false;
                    msgs.push('Phone number must be exactly 10 digits.');
                }
                if (!isValid) {
                    e.preventDefault();
                    alert(msgs.join('\n'));
                }
            });
        }
    });
</script>
@endsection