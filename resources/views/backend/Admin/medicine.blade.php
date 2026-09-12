@extends("backend.include.layout")

@section('title', 'Medicine Master Directory — Admin Console')

@section('content')
<style>
    .bg-purple-subtle {
        background-color: #f4f2ff !important;
    }
    .text-purple {
        color: #6c5ffc !important;
    }
    .border-purple-subtle {
        border-color: #d1cbfb !important;
    }
</style>

<div class="page-content wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-capsule-pill me-2 text-primary"></i>Medicine Master Directory
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Master</span> &bull; Manage registered medicines, brands, generics &amp; categories
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill shadow-xs">
                    <i class="bi bi-boxes me-1"></i> Total Medicines: {{ method_exists($medicine, 'total') ? $medicine->total() : $medicine->count() }}
                </span>
                <a href="{{ route('Addmedicine') }}" class="btn btn-primary rounded-pill px-3.5 btn-sm fw-semibold shadow-xs">
                    <i class="bi bi-plus-lg me-1"></i> Add New Medicine
                </a>
            </div>
        </div>

        <!-- Master Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>All Registered Medicines
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-1.5 fs-12 fw-bold rounded-pill">
                            {{ method_exists($medicine, 'total') ? $medicine->total() : $medicine->count() }} Records
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 60px;">#</th>
                                        <th class="py-3" style="width: 120px;">Code</th>
                                        <th class="py-3">Medicine Name</th>
                                        <th class="py-3">Generic Name</th>
                                        <th class="py-3">Category</th>
                                        <th class="py-3">Company</th>
                                        <th class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($medicine as $med)
                                    @php
                                        $catObj = $category->where('id', $med->category_id)->first();
                                        $compObj = $company->where('id', $med->company_id)->first();
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ method_exists($medicine, 'firstItem') ? ($medicine->firstItem() + $loop->index) : $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold font-monospace">
                                                {{ $med->medicine_code ?? ('MED-' . str_pad($med->id, 5, '0', STR_PAD_LEFT)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px; height:28px;">
                                                    <i class="bi bi-capsule fs-14"></i>
                                                </div>
                                                <span class="fw-bold text-dark fs-13">{{ $med->medicine_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-13">{{ $med->generic_name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($catObj)
                                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2.5 py-1 fs-12 fw-medium rounded-pill">
                                                    <i class="bi bi-tag-fill me-1"></i>{{ $catObj->category_name }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2.5 py-1 fs-12 fw-normal rounded">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($compObj)
                                                <span class="badge bg-light text-dark border font-monospace fs-12 px-2.5 py-1 rounded">
                                                    <i class="bi bi-building me-1 text-primary"></i>{{ $compObj->company_name }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2.5 py-1 fs-12 fw-normal rounded">N/A</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <a href="{{ route('editmedicine', $med->id) }}" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;" title="Edit Medicine">
                                                    <i class="bi bi-pencil-fill fs-12"></i>
                                                </a>
                                                <a href="{{ route('deletemedicine', $med->id) }}" data-href="{{ route('deletemedicine', $med->id) }}" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs deleteBtn" style="width: 32px; height: 32px;" title="Delete Medicine">
                                                    <i class="bi bi-trash-fill fs-12"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="bi bi-capsule fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No medicine records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($medicine, 'hasPages') && $medicine->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $medicine->firstItem() }}</strong> to <strong>{{ $medicine->lastItem() }}</strong> of <strong>{{ $medicine->total() }}</strong> medicines
                        </div>
                        <div>
                            {{ $medicine->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;
    e.preventDefault();
    const url = btn.getAttribute('data-href');
    Swal.fire({
        title: 'Are you sure?',
        text: "This medicine will be deleted permanently.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
</script>
@endsection
