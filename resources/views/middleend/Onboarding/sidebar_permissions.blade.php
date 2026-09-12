@extends('middleend.include.layout')
@section('title', 'Doctor Sidebar Permissions - Onboarding Portal')

@section('content')
<style>
.btn-toggle-mini {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    padding: 5px 14px !important;
    height: 28px !important;
    line-height: 1 !important;
    border-radius: 50rem !important;
    cursor: pointer !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.2s ease-in-out !important;
    text-decoration: none !important;
}
.btn-toggle-mini i {
    font-size: 12px !important;
    margin-right: 5px !important;
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
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(10, 179, 156, 0.3) !important;
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
    transform: translateY(-1px);
}
.doc-id-badge {
    background: #eef2ff;
    color: #4338ca;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
}
.permission-stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 14px 18px;
    border: 1px solid #e9edf4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
}
.permission-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-toggles2 text-primary me-2"></i> Doctor Sidebar Permissions &amp; Access Controls
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Onboarding Console</span> &bull; Control menu visibility of Staff Module, Payment Categories, and Deleted Staff in Doctor's portal sidebar
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('/manage.doctor') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fs-13 fw-semibold">
                    <i class="bi bi-people me-1"></i> Manage Doctors
                </a>
                <a href="{{ route('adddoctor.onboarding') }}" class="btn btn-sm btn-primary rounded-pill px-3 fs-13 fw-semibold shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Doctor
                </a>
            </div>
        </div>

        <!-- 4 Stat Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="permission-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Total Doctors</span>
                        <h4 class="fw-bold text-dark mb-0 fs-18">{{ $allDoctorsCount }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="permission-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Staff Module Visible</span>
                        <h4 class="fw-bold text-success mb-0 fs-18">{{ $staffVisibleCount }} <span class="fs-12 text-muted fw-normal">/ {{ $allDoctorsCount }}</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="permission-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Payment Category</span>
                        <h4 class="fw-bold text-info mb-0 fs-18">{{ $paymentVisibleCount }} <span class="fs-12 text-muted fw-normal">/ {{ $allDoctorsCount }}</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="permission-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Deleted Staff Visible</span>
                        <h4 class="fw-bold text-warning mb-0 fs-18">{{ $deletedStaffVisibleCount }} <span class="fs-12 text-muted fw-normal">/ {{ $allDoctorsCount }}</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Action Toolbar: Left = Search, Right = Info -->
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                        <!-- Left Side: Search Bar -->
                        <form method="GET" action="{{ route('doctor.sidebar.permissions') }}" class="d-flex align-items-center flex-grow-1" style="max-width: 420px;">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control bg-light border-start-0 ps-0 fs-13" 
                                       placeholder="Search doctor name, ID, clinic, email..." 
                                       value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('doctor.sidebar.permissions') }}" class="btn btn-light border border-start-0 border-end-0 text-muted" title="Clear Search">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                                <button class="btn btn-primary px-3 fs-13 fw-semibold" type="submit">Search</button>
                            </div>
                        </form>

                        <div class="text-muted fs-12 d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-info-circle text-primary me-1"></i> Toggling updates doctor sidebar immediately</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3 mb-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Doctor Details</th>
                                        <th class="py-3">Clinic / Specialization</th>
                                        <th class="py-3 text-center" style="width: 140px;">Staff Module</th>
                                        <th class="py-3 text-center" style="width: 155px;">Payment Category</th>
                                        <th class="py-3 text-center" style="width: 140px;">Deleted Staff</th>
                                        <th class="pe-4 py-3 text-end" style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 fs-13">
                                    @forelse($doctors as $index => $doctor)
                                        @php
                                            $hasMem = $doctor->has_member ?? true;
                                            $hasPay = $doctor->has_payment_category ?? false;
                                            $hasDelStaff = $doctor->has_deleted_staff ?? false;
                                            $isSuperAdmin = in_array(strtolower($doctor->created_by ?? ''), ['super admin', 'admin', 'superadmin', '']) || is_null($doctor->created_by);
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div>
                                                        <span class="fw-bold text-dark d-block fs-14">Dr. {{ $doctor->name }}</span>
                                                        <div class="d-flex align-items-center gap-1 mt-0.5 flex-wrap">
                                                            <span class="doc-id-badge">{{ $doctor->Doctor_Emp_id ?? 'DOC-'.$doctor->id }}</span>
                                                            @if($isSuperAdmin)
                                                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold" style="background:#f3e8ff; color:#7e22ce; border:1px solid #e9d5ff;">
                                                                    <i class="bi bi-shield-check me-0.5"></i> Super Admin
                                                                </span>
                                                            @else
                                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                                                                    <i class="bi bi-person-check me-0.5"></i> Onboarding
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted d-block mt-0.5"><i class="bi bi-envelope me-1"></i>{{ $doctor->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark d-block">{{ $doctor->clinic_name ?? 'N/A' }}</span>
                                                <small class="text-muted"><i class="bi bi-award me-1"></i>{{ $doctor->specialization ?? 'General Physician' }}</small>
                                            </td>

                                            {{-- Toggle 1: Staff Module --}}
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-toggle-mini js-toggle-btn {{ $hasMem ? 'is-visible' : 'is-hidden' }}"
                                                        data-url="{{ route('doctor.toggle-member-access', $doctor->id) }}"
                                                        data-field="has_member"
                                                        data-doc-name="Dr. {{ $doctor->name }}"
                                                        data-visible="{{ $hasMem ? '1' : '0' }}"
                                                        title="Click to toggle Staff Module in sidebar">
                                                    <i class="bi {{ $hasMem ? 'bi-eye-fill' : 'bi-eye-slash-fill' }}"></i>
                                                    <span class="label-text">{{ $hasMem ? 'Visible' : 'Hidden' }}</span>
                                                </button>
                                            </td>

                                            {{-- Toggle 2: Payment Category --}}
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-toggle-mini js-toggle-btn {{ $hasPay ? 'is-visible' : 'is-hidden' }}"
                                                        data-url="{{ route('doctor.toggle-payment-category-access', $doctor->id) }}"
                                                        data-field="has_payment_category"
                                                        data-doc-name="Dr. {{ $doctor->name }}"
                                                        data-visible="{{ $hasPay ? '1' : '0' }}"
                                                        title="Click to toggle Payment Category in sidebar">
                                                    <i class="bi {{ $hasPay ? 'bi-eye-fill' : 'bi-eye-slash-fill' }}"></i>
                                                    <span class="label-text">{{ $hasPay ? 'Visible' : 'Hidden' }}</span>
                                                </button>
                                            </td>

                                            {{-- Toggle 3: Deleted Staff --}}
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-toggle-mini js-toggle-btn {{ $hasDelStaff ? 'is-visible' : 'is-hidden' }}"
                                                        data-url="{{ route('doctor.toggle-deleted-staff-access', $doctor->id) }}"
                                                        data-field="has_deleted_staff"
                                                        data-doc-name="Dr. {{ $doctor->name }}"
                                                        data-visible="{{ $hasDelStaff ? '1' : '0' }}"
                                                        title="Click to toggle Deleted Staff in sidebar">
                                                    <i class="bi {{ $hasDelStaff ? 'bi-eye-fill' : 'bi-eye-slash-fill' }}"></i>
                                                    <span class="label-text">{{ $hasDelStaff ? 'Visible' : 'Hidden' }}</span>
                                                </button>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="pe-4 text-end">
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <a href="{{ route('onboarding.view.doctor', $doctor->id) }}" class="btn btn-sm btn-icon btn-light text-primary" title="View Doctor Profile">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('doctor.onboarding.edit', $doctor->id) }}" class="btn btn-sm btn-icon btn-light text-secondary" title="Edit Doctor">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="bi bi-people display-4 text-muted opacity-50 mb-3 d-block"></i>
                                                    <h5 class="fw-bold text-dark">No doctors found</h5>
                                                    <p class="text-muted fs-13">Try searching with a different doctor name or add a new doctor.</p>
                                                    <a href="{{ route('adddoctor.onboarding') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">
                                                        <i class="bi bi-plus-circle me-1"></i> Add Doctor
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($doctors->hasPages())
                        <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
                            <span class="text-muted fs-12">
                                Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} doctors
                            </span>
                            <div>
                                {{ $doctors->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- AJAX Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fs-13 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5" id="toastIcon"></i>
                <span id="toastMessage">Permission updated successfully.</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') 
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        : '{{ csrf_token() }}';

    const toastEl = document.getElementById('liveToast');
    const toast = toastEl ? new bootstrap.Toast(toastEl, { delay: 3500 }) : null;
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');

    function showToast(message, isSuccess = true) {
        if (!toast || !toastEl) return;
        toastEl.className = 'toast align-items-center text-white border-0 shadow-lg ' + (isSuccess ? 'bg-success' : 'bg-danger');
        if (toastIcon) {
            toastIcon.className = 'bi ' + (isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill') + ' fs-5';
        }
        if (toastMessage) {
            toastMessage.textContent = message;
        }
        toast.show();
    }

    document.querySelectorAll('.js-toggle-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const btn = this;
            if (btn.disabled) return;

            const url = btn.dataset.url;
            const field = btn.dataset.field;
            const docName = btn.dataset.docName || 'Doctor';
            const currentlyVisible = btn.dataset.visible === '1';

            // Disable while request is active
            btn.disabled = true;
            btn.style.opacity = '0.6';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(res => {
                if (!res.ok) throw new Error('Server returned error: ' + res.status);
                return res.json();
            })
            .then(data => {
                btn.disabled = false;
                btn.style.opacity = '1';

                if (data.success) {
                    let isNowVisible = false;
                    if (data[field] !== undefined) {
                        isNowVisible = Boolean(data[field]);
                    } else {
                        isNowVisible = !currentlyVisible;
                    }

                    btn.dataset.visible = isNowVisible ? '1' : '0';

                    if (isNowVisible) {
                        btn.className = 'btn btn-toggle-mini js-toggle-btn is-visible';
                        btn.innerHTML = '<i class="bi bi-eye-fill"></i> <span class="label-text">Visible</span>';
                    } else {
                        btn.className = 'btn btn-toggle-mini js-toggle-btn is-hidden';
                        btn.innerHTML = '<i class="bi bi-eye-slash-fill"></i> <span class="label-text">Hidden</span>';
                    }

                    showToast(data.message || (docName + ' permission updated.'), true);
                } else {
                    showToast(data.message || 'Could not update status.', false);
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.style.opacity = '1';
                showToast('An error occurred while updating permissions.', false);
            });
        });
    });
});
</script>
@endpush
