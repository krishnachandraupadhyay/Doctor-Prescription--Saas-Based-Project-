@extends("backend.include.layout")

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

    .category-badge {
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .company-badge {
        background: #f3f4f6;
        color: #374151;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
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

    .action-icon.edit-icon:hover {
        background: #e5e7eb;
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
</style>
<!---------------------------------------->
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">

                <div class="medicine-list-card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-capsule"></i>
                            Medicine
                            <span class="medicine-count-badge">
                                {{ method_exists($medicine, 'total') ? $medicine->total() : $medicine->count() }} total
                            </span>
                        </h5>
                        <div class="addmedicine">
                            <a href="{{ route('Addmedicine') }}">
                                <i class="bi bi-plus-lg"></i> Add Medicine
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="tabular-data table-responsive">
                        <table class="table table-hover medicine-table">
                            <tr>
                                <th style="width:60px;">S.No</th>
                                <th style="width:110px;">Code</th>
                                <th>Medicine Name</th>
                                <th>Medicine Generic Name</th>
                                <th>Medicine Category Name</th>
                                <th>Medicine Company</th>
                                <th class="text-end">Action</th>
                            </tr>
                            @forelse($medicine as $med)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold font-monospace">
                                        {{ $med->medicine_code ?? ('MED-' . str_pad($med->id, 5, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </td>
                                <td class="medicine-name-cell">{{ $med->medicine_name }}</td>
                                <td>{{ $med->generic_name }}</td>
                                <td>
                                    <span class="category-badge">
                                        {{ $category->where('id',$med->category_id)->first()->category_name ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="company-badge">
                                        {{ $company->where('id',$med->company_id)->first()->company_name ?? '' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a class="action-icon edit-icon" href="{{ route('editmedicine', $med->id) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="action-icon delete-icon deleteBtn"
                                       href="{{ route('deletemedicine', $med->id) }}"
                                       data-href="{{ route('deletemedicine', $med->id) }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No medicines found.</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>

                    <!-- ================= Pagination ================= -->
                    @if(method_exists($medicine, 'hasPages') && $medicine->hasPages())
                    <div class="pagination-footer">
                        <div class="pagination-info">
                            Showing {{ $medicine->firstItem() }}–{{ $medicine->lastItem() }} of {{ $medicine->total() }} medicines
                        </div>
                        <nav>
                            {{ $medicine->links() }}
                        </nav>
                    </div>
                    @endif
                    <!-- ================= /Pagination ================= -->

                </div>

            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

/* -------- Delete: confirm before navigating (works with existing GET link route) -------- */
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
