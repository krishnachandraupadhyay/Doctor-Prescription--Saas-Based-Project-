@extends('backend.include.layout')

@section('title', 'Deleted Onboarding Members')

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-trash3-fill text-danger me-2"></i> Deleted Onboarding Members</h4>
                <p class="text-muted fs-13 mb-0">Soft-deleted onboarding managers. They cannot log in to the portal unless restored.</p>
            </div>
            <a href="{{ url('/onboarding') }}" class="btn btn-primary rounded-pill px-3.5 py-1.5 fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Onboarding Managers
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 fw-bold text-dark">Deleted Onboarding Members List</h5>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                {{ method_exists($users, 'total') ? $users->total() : $users->count() }} Deleted
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Member ID</th>
                                        <th class="py-3">Name</th>
                                        <th class="py-3">Email Address</th>
                                        <th class="py-3">Phone</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 fs-13">
                                    @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                {{ $user->member_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark fs-13">{{ $user->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark"><i class="bi bi-envelope me-1 text-muted"></i> {{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $user->phone ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                <i class="bi bi-x-circle-fill me-1"></i> Deleted
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('onboarding.restore', $user->id) }}" 
                                               class="btn btn-sm btn-success rounded-pill px-3 py-1 fs-12 fw-semibold"
                                               title="Restore onboarding member to active list">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-trash3 fs-2 d-block mb-2 text-muted opacity-50"></i>
                                            No deleted onboarding members found. All members are active.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                    <div class="card-footer bg-light p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fs-12">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} members</span>
                            <div>{{ $users->links() }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
@endsection
