@extends('clinic.include.layout')
@section('title', 'Deleted Staff Members - Clinic Portal')

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
        background: #fdf2f2;
        border-bottom: 1px solid #fee2e2;
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
        background-color: #fee2e2;
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
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

        {{-- Page Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    {{-- Left: Deleted Staff Info --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-danger-subtle text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                            <i class="bi bi-person-x-fill"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Deleted Staff Archive</h4>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->name }}
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                Staff in this list cannot access the portal. You can restore them back to active duty anytime.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Back Button --}}
                    <div>
                        @if(!empty($clinic->has_member))
                            <a href="{{ route('clinic.staff') }}" class="btn btn-primary btn-sm px-3.5 py-2 rounded-3 fw-semibold shadow-sm d-flex align-items-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Back to Clinic Staff
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Deleted Staff Table Card --}}
        <div class="valex-card mb-4">
            <div class="valex-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-trash3 text-danger fs-16"></i>
                    <h5 class="fw-bold text-danger mb-0 fs-15">Deleted Staff Members</h5>
                </div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fs-12 fw-semibold">
                    Total Deleted: {{ count($deletedMembers) }}
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
                            <th>Deleted On</th>
                            <th class="text-center" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedMembers as $member)
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
                                    <span class="text-muted fs-12">{{ \Carbon\Carbon::parse($member->updated_at ?? $member->created_at)->format('d M Y, h:i A') }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('clinic.restore_staff', $member->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Restore {{ addslashes($member->name) }} back to active clinic staff?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1 fs-12 fw-semibold shadow-xs">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-check-circle text-success" style="font-size: 42px; opacity: 0.5;"></i>
                                        <h6 class="fw-bold text-secondary mt-3">No Deleted Staff Records</h6>
                                        <p class="text-muted fs-13 mb-0">All clinic staff and receptionists are currently active.</p>
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
@endsection
