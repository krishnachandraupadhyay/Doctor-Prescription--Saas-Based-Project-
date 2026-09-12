@extends("frontend.include.layout")
@section('title', 'Medicine Formulary List - Doctor Portal')

@section('content')
<!---------------------------------------->
<style>
    /* ---------------- Filter card ---------------- */
    .filter-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.2rem;
    }

    .filter-card .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        color: #6b7280;
        margin-bottom: 0.35rem;
    }

    .filter-card .form-select {
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        transition: .3s;
    }

    .filter-card .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 8px rgba(79, 70, 229, .2);
        outline: none;
    }

    .filter-btn {
        border: none;
        border-radius: 8px;
        background-color: #4f46e5;
        color: #fff;
        font-weight: 500;
        font-size: 0.85rem;
        padding: 0.45rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .3s;
    }

    .filter-btn:hover {
        background-color: #372fd3;
        color: #fff;
    }

    .reset-btn {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: #f3f4f6;
        color: #374151;
        font-size: 0.85rem;
        padding: 0.45rem 0.7rem;
        display: inline-flex;
        align-items: center;
        transition: .2s;
    }

    .reset-btn:hover {
        background-color: #e5e7eb;
        color: #374151;
    }

    /* ---------------- Medicine card ---------------- */
    .medicine-list-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.2rem 1.4rem;
    }

    .medicine-list-card .card_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .medicine-list-card .card_header h5 {
        font-weight: bold;
        font-size: 1.15rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .medicine-list-card .card_header h5 i {
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

    .description-cell {
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .action-icon {
        border: none;
        background: #fef2f2;
        border-radius: 8px;
        padding: 6px 9px;
        font-size: 15px;
        color: #dc3545;
        transition: .2s;
        display: inline-flex;
        cursor: pointer;
    }

    .action-icon:hover {
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

    @media(max-width:768px) {
        .filter-card .row > div {
            margin-bottom: 0.6rem;
        }
    }

    @media(max-width:600px) {
        .medicine-list-card .card_header {
            gap: 0.6rem;
        }
        .description-cell {
            max-width: 140px;
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

                <!-- ================= Filter Form ================= -->
                <div class="filter-card">
                    <form action="{{ route('listofmedicine') }}" method="GET">
                        <div class="row g-2 align-items-end">

                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Medicine Name</label>
                                <select name="medicine_name" class="form-select form-select-sm">
                                    <option value="">All Medicine Name</option>
                                    @foreach($medicineNames as $name)
                                        <option value="{{ $name }}" @selected(request('medicine_name') == $name)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Generic Name</label>
                                <select name="generic_name" class="form-select form-select-sm">
                                    <option value="">All Generic Name</option>
                                    @foreach($genericNames as $name)
                                        <option value="{{ $name }}" @selected(request('generic_name') == $name)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-2">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select form-select-sm">
                                    <option value="">All Category</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-2">
                                <label class="form-label">Company</label>
                                <select name="company_id" class="form-select form-select-sm">
                                    <option value="">All Company</option>
                                    @foreach($company as $comp)
                                        <option value="{{ $comp->id }}" @selected(request('company_id') == $comp->id)>
                                            {{ $comp->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="filter-btn" title="Search">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('listofmedicine') }}" class="reset-btn" title="Reset">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- ================= /Filter Form ================= -->

                <div class="medicine-list-card">
                    <div class="card_header">
                        <h5>
                            <i class="bi bi-capsule"></i>
                            Medicine List
                            <span class="medicine-count-badge">{{ $medicine->total() }} total</span>
                        </h5>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-hover medicine-table">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Medicine Name</th>
                                <th>Generic Name</th>
                                <th>Category</th>
                                <th>Company Name</th>
                                <th>Description</th>
                            </tr>
                            @forelse($medicine as $med)
                            <tr>
                                <td>{{ $loop->iteration + ($medicine->currentPage() - 1) * $medicine->perPage() }}</td>
                                <td class="medicine-name-cell">{{ $med->medicine_name }}</td>
                                <td>{{ $med->generic_name }}</td>
                                <td>
                                    <span class="category-badge">
                                        {{ $category->where('id',$med->category_id)->value('category_name') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="company-badge">
                                        {{ $company->where('id',$med->company_id)->value('company_name') }}
                                    </span>
                                </td>
                                <td class="description-cell" title="{{ $med->description }}">
                                    {{ $med->description }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No matching medicines found.</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>

                    <!-- ================= Pagination ================= -->
                    @if($medicine->hasPages())
                    <div class="pagination-footer">
                        <div class="pagination-info">
                            Showing {{ $medicine->firstItem() }}–{{ $medicine->lastItem() }} of {{ $medicine->total() }} medicines
                        </div>
                        <nav>
                            {{ $medicine->appends(request()->query())->links() }}
                        </nav>
                    </div>
                    @endif
                    <!-- ================= /Pagination ================= -->

                </div>

            </div>
        </div>
    </div>
</div>
<!-- container-fluid -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;
    e.preventDefault();
    const form = btn.closest('.deleteForm');
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
            form.submit();
        }
    });
});
</script>
@endsection
