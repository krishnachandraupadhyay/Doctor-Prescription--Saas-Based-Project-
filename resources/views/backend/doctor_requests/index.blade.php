@extends('backend.include.layout')
@section('title', 'Doctor Profile Correction Requests - Super Admin')

@section('content')
<style>
.kpi-stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 16px 20px;
    border: 1px solid #e9edf4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
}
.kpi-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.filter-pill {
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 50rem;
    color: #495057;
    background: #f3f6f9;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.filter-pill:hover, .filter-pill.active {
    background: #405189;
    border-color: #405189;
    color: #ffffff;
}
.valex-avatar-mini {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #eef2ff;
    color: #4338ca;
    font-weight: 700;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}
.valex-table {
    min-width: 1250px;
    width: 100%;
}
.valex-table th {
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #878a99;
    font-weight: 700;
    padding: 13px 16px;
    border-bottom: 1px solid #eef0f5;
    background-color: #f8f9fa;
    white-space: nowrap;
}
.valex-table td {
    padding: 14px 16px;
    font-size: 13.5px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f7;
}
.valex-table tbody tr:hover {
    background-color: #fcfdfe;
}
.val-diff-box {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12.5px;
    line-height: 1.4;
    min-width: 180px;
    word-break: break-word;
}
.val-diff-box.current {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #991b1b;
}
.val-diff-box.requested {
    background: #ecfdf5;
    border: 1px solid #d1fae5;
    color: #065f46;
}
</style>

<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Top Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 text-dark fw-bold">
                    <i class="bi bi-file-earmark-diff text-primary me-2"></i> Doctor Correction Requests (All Clinics)
                </h4>
                <p class="text-muted fs-13 mb-0">Live monitoring of doctor profile correction requests submitted across all hospitals and clinics.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('clinics.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-hospital me-1"></i> Manage Clinics
                </a>
                <a href="{{ url('/managedoctor') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                    <i class="bi bi-person-badge me-1"></i> Manage Doctors
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-info-circle-fill fs-5"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 4 KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="kpi-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Total Requests</span>
                        <h4 class="fw-bold text-dark mb-0 fs-18">{{ $totalCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Pending Approval</span>
                        <h4 class="fw-bold text-warning mb-0 fs-18">{{ $pendingCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Approved</span>
                        <h4 class="fw-bold text-success mb-0 fs-18">{{ $approvedCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-stat-card d-flex align-items-center gap-3">
                    <div class="avatar-sm rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center fs-20 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-11 text-uppercase fw-semibold d-block">Rejected</span>
                        <h4 class="fw-bold text-danger mb-0 fs-18">{{ $rejectedCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            
            <!-- Filter Bar & Search -->
            <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('superadmin.doctor_requests', ['status' => 'all', 'search' => request('search')]) }}" class="filter-pill {{ ($status ?? 'all') === 'all' ? 'active' : '' }}">
                        All Requests ({{ $totalCount ?? 0 }})
                    </a>
                    <a href="{{ route('superadmin.doctor_requests', ['status' => 'pending', 'search' => request('search')]) }}" class="filter-pill {{ ($status ?? '') === 'pending' ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Pending ({{ $pendingCount ?? 0 }})
                    </a>
                    <a href="{{ route('superadmin.doctor_requests', ['status' => 'approved', 'search' => request('search')]) }}" class="filter-pill {{ ($status ?? '') === 'approved' ? 'active' : '' }}">
                        <i class="bi bi-check-circle"></i> Approved ({{ $approvedCount ?? 0 }})
                    </a>
                    <a href="{{ route('superadmin.doctor_requests', ['status' => 'rejected', 'search' => request('search')]) }}" class="filter-pill {{ ($status ?? '') === 'rejected' ? 'active' : '' }}">
                        <i class="bi bi-x-circle"></i> Rejected ({{ $rejectedCount ?? 0 }})
                    </a>
                </div>

                <form method="GET" action="{{ route('superadmin.doctor_requests') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
                    <div class="input-group input-group-sm" style="width: 260px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Search doctor, clinic..." value="{{ request('search') }}">
                    </div>
                    @if(request('search'))
                        <a href="{{ route('superadmin.doctor_requests', ['status' => $status]) }}" class="btn btn-sm btn-light border" title="Clear Search">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table valex-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" style="min-width: 140px;">Date &amp; Time</th>
                                <th style="min-width: 200px;">Doctor Info</th>
                                <th style="min-width: 180px;">Clinic</th>
                                <th style="min-width: 130px;">Field</th>
                                <th style="min-width: 220px;">What's Wrong ("Kya Galat Hai")</th>
                                <th style="min-width: 220px;">Requested ("Kya Hona Chahiye")</th>
                                <th style="min-width: 170px;">Reason</th>
                                <th style="min-width: 120px;" class="text-center">Status</th>
                                <th style="min-width: 200px;" class="text-center">Action / Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    {{-- 1. Date & Time --}}
                                    <td class="ps-4" style="min-width: 140px; white-space: nowrap;">
                                        <div class="fw-semibold text-dark">{{ $req->created_at->format('d M Y') }}</div>
                                        <small class="text-muted fs-11"><i class="bi bi-clock me-1"></i>{{ $req->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- 2. Doctor Info --}}
                                    <td style="min-width: 200px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="valex-avatar-mini">
                                                {{ strtoupper(substr($req->doctor->name ?? 'D', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">Dr. {{ $req->doctor->name ?? 'Doctor' }}</div>
                                                <small class="text-muted fs-11">
                                                    ID: <span class="fw-semibold text-primary">{{ $req->doctor->Doctor_Emp_id ?? 'N/A' }}</span> &bull; {{ $req->doctor->specialization ?? 'Doctor' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 3. Clinic --}}
                                    <td style="min-width: 180px;">
                                        <div class="fw-semibold text-dark">
                                            <i class="bi bi-hospital me-1 text-primary"></i> {{ $req->clinic->name ?? ($req->doctor->clinic_name ?? 'Clinic') }}
                                        </div>
                                        <small class="text-muted fs-11">{{ $req->clinic->clinic_id ?? '-' }}</small>
                                    </td>

                                    {{-- 4. Field Label --}}
                                    <td style="min-width: 130px;">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-12 fw-semibold">
                                            {{ $req->field_label }}
                                        </span>
                                    </td>

                                    {{-- 5. Current Value (Kya Galat Tha) --}}
                                    <td style="min-width: 220px;">
                                        <div class="val-diff-box current">
                                            <div class="fs-10 text-uppercase fw-bold opacity-75 mb-0.5">Current Recorded:</div>
                                            <span class="text-decoration-line-through">{{ $req->current_value ?: '(Not set)' }}</span>
                                        </div>
                                    </td>

                                    {{-- 6. Requested Value (Kya Hona Chahiye) --}}
                                    <td style="min-width: 220px;">
                                        <div class="val-diff-box requested">
                                            <div class="fs-10 text-uppercase fw-bold opacity-75 mb-0.5">Requested Value:</div>
                                            <span class="fw-bold">{{ $req->requested_value }}</span>
                                        </div>
                                    </td>

                                    {{-- 7. Reason --}}
                                    <td style="min-width: 170px; max-width: 220px;">
                                        <span class="text-secondary fs-12 text-break d-block" title="{{ $req->reason }}">
                                            {{ $req->reason ?: '-' }}
                                        </span>
                                    </td>

                                    {{-- 8. Status --}}
                                    <td style="min-width: 120px;" class="text-center">
                                        @if($req->status === 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-circle-fill"></i> Approved
                                            </span>
                                            @if($req->reviewed_at)
                                                <small class="d-block text-muted fs-10 mt-1">{{ $req->reviewed_at->format('d M, h:i A') }}</small>
                                            @endif
                                        @elseif($req->status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-x-circle-fill"></i> Rejected
                                            </span>
                                            @if($req->reviewed_at)
                                                <small class="d-block text-muted fs-10 mt-1">{{ $req->reviewed_at->format('d M, h:i A') }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-12 fw-bold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-hourglass-split"></i> Pending
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 9. Action / Review Details --}}
                                    <td class="text-center" style="min-width: 200px;">
                                        @if($req->status === 'pending')
                                            <div class="d-inline-flex align-items-center gap-1">
                                                {{-- Quick Approve Form --}}
                                                <form action="{{ route('superadmin.doctor_requests.approve', $req->id) }}" method="POST" onsubmit="return confirm('Super Admin: Are you sure you want to APPROVE this request? The doctor profile will be updated immediately.');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" title="Approve and apply changes">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                </form>

                                                {{-- Reject Modal Trigger --}}
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#adminRejectModal{{ $req->id }}" title="Reject Request">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </button>
                                            </div>

                                            <!-- Super Admin Reject Modal -->
                                            <div class="modal fade text-start" id="adminRejectModal{{ $req->id }}" tabindex="-1" aria-labelledby="adminRejectModalLabel{{ $req->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                        <form action="{{ route('superadmin.doctor_requests.reject', $req->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header bg-danger text-white py-3 px-4 border-0">
                                                                <h5 class="modal-title fw-bold text-white mb-0" id="adminRejectModalLabel{{ $req->id }}">
                                                                    <i class="bi bi-x-circle me-1"></i> Reject Request #{{ $req->id }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <p class="text-dark fs-13 mb-3">
                                                                    Rejecting request for <strong>Dr. {{ $req->doctor->name }}</strong> (Clinic: {{ $req->clinic->name ?? 'N/A' }}) on field <strong>{{ $req->field_label }}</strong>.
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="admin_notes{{ $req->id }}" class="form-label fw-semibold text-dark fs-13">Reason for Rejection <span class="text-danger">*</span></label>
                                                                    <textarea name="admin_notes" id="admin_notes{{ $req->id }}" rows="3" class="form-control" placeholder="Specify reason why Super Admin is rejecting this request..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
                                                                <button type="button" class="btn btn-light border px-3 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
                                                                    Confirm Rejection
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($req->status === 'approved')
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-12 fw-semibold">
                                                    <i class="bi bi-check2-circle me-1"></i>Approved by {{ $req->reviewed_by ?: ($req->clinic->name ?? 'Clinic') }}
                                                </span>
                                                @if($req->admin_notes && !str_starts_with($req->admin_notes, 'Approved by'))
                                                    <small class="text-muted fs-11 text-break">{{ $req->admin_notes }}</small>
                                                @endif
                                            </div>
                                        @elseif($req->status === 'rejected')
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-12 fw-semibold">
                                                    <i class="bi bi-x-circle me-1"></i>Rejected by {{ $req->reviewed_by ?: ($req->clinic->name ?? 'Clinic') }}
                                                </span>
                                                @if($req->admin_notes)
                                                    <small class="text-danger fs-11 text-break text-center" style="max-width: 200px;">
                                                        <strong>Note:</strong> {{ $req->admin_notes }}
                                                    </small>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-muted fs-12">
                                                <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill fs-12">
                                                    Processed
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-check fs-1 text-muted opacity-50 d-block mb-2"></i>
                                        No doctor correction requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
