@extends('frontend.include.layout')
@section('title', 'Deleted Staff Members - Doctor Portal')
@section('content')
<style>
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(26, 35, 126, 0.06);
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #fff;
    }

    .card .card-header {
        background-color: #fdf2f2;
        padding: 16px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        border: none;
        border-bottom: 1px solid #fee2e2;
    }

    .card .card-header .cardhead {
        font-weight: 700;
        color: #991b1b;
        font-size: 16px;
        letter-spacing: 0.2px;
        display: flex;
        align-items: center;
        gap: 8px;
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
        background-color: #fee2e2;
        color: #dc2626;
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
        color: #dc2626;
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 600;
    }

    .empty-state {
        padding: 50px 22px;
        text-align: center;
        color: #9aa0ab;
        font-size: 14px;
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <!-- Page Top Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-person-x-fill text-danger me-2"></i> Deleted Staff Members
                </h4>
                <p class="text-muted fs-13 mb-0">Staff and receptionists in this list cannot log in. You can restore them back to Active Clinic Staff at any time.</p>
            </div>
            <a href="{{ route('addmember') }}" class="btn btn-primary rounded-pill px-3.5 py-1.5 fs-13 fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1.5"></i> Back to Clinic Staff
            </a>
        </div>

        <div class="row">
            <div class="col-xxl-12">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="cardhead">
                            <i class="bi bi-trash3"></i> Deleted Staff List
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                            Total Deleted: {{ isset($deletedMembers) ? count($deletedMembers) : 0 }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        @if(isset($deletedMembers) && count($deletedMembers) > 0)
                            <div class="table-responsive">
                                <table class="member-table align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th>Member ID</th>
                                            <th>Member Name</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Deleted On</th>
                                            <th class="text-end pe-4" style="width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($deletedMembers as $index => $member)
                                            <tr>
                                                <td class="text-muted fw-semibold">{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="member-id-badge">{{ $member->member_id }}</span>
                                                </td>
                                                <td>
                                                    <div class="member-info">
                                                        <div class="member-avatar">
                                                            {{ strtoupper(substr($member->name ?? 'M', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <span class="member-name">{{ $member->name }}</span>
                                                            <span class="member-email">{{ $member->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(strtolower($member->role ?? '') === 'receptionist')
                                                        <span class="badge" style="background:#EDE9FE; color:#6D28D9; border:1px solid #DDD6FE; border-radius:12px; padding:4px 10px; font-size:11px;">
                                                            <i class="bi bi-person-badge me-1"></i> Receptionist
                                                        </span>
                                                    @else
                                                        <span class="badge" style="background:#E0F2FE; color:#0369A1; border:1px solid #BAE6FD; border-radius:12px; padding:4px 10px; font-size:11px;">
                                                            <i class="bi bi-person-workspace me-1"></i> Staff
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Deleted
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fs-13">
                                                        {{ \Carbon\Carbon::parse($member->updated_at ?? $member->created_at)->format('d M Y, h:i A') }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <form action="{{ route('doctor.restore.member', $member->id) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to restore {{ addslashes($member->name) }} back to active clinic staff?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1 fs-12 fw-semibold shadow-xs">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-check2-circle text-success" style="font-size: 38px; display:block; margin-bottom:10px;"></i>
                                <h6 class="fw-bold text-dark mb-1">No Deleted Staff Members</h6>
                                <p class="text-muted fs-13 mb-0">All clinic staff and receptionists are currently active.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
