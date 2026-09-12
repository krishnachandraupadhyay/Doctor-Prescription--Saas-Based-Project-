@extends("backend.include.layout")

@section('title', 'Pharma Company Master — Admin Console')

@section('content')
<style>
    .medicine-master-sidebar {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .sidebar-master-header {
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 16px 20px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .master-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        color: #475569;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .master-menu-item i {
        font-size: 16px;
        color: #0162e8;
        width: 22px;
        text-align: center;
        transition: transform 0.2s ease;
    }
    .master-menu-item:hover {
        background: #f8fafc;
        color: #0162e8;
        padding-left: 22px;
    }
    .master-menu-item:hover i {
        transform: scale(1.15);
    }
    .master-menu-item.active {
        background: #0162e8;
        color: #ffffff;
    }
    .master-menu-item.active i {
        color: #ffffff;
    }
</style>

<div class="page-content wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-buildings-fill me-2 text-primary"></i>Pharma Company Master
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Master</span> &bull; Manage pharmaceutical manufacturers &amp; medicine brands
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3.5 btn-sm fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#formModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Company
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Sidebar Navigation -->
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="medicine-master-sidebar">
                    <div class="sidebar-master-header">
                        <i class="bi bi-sliders text-primary fs-16"></i> Formulary Settings
                    </div>
                    <a href="{{ route('category') }}" class="master-menu-item">
                        <i class="bi bi-tags-fill"></i> Medicine Category
                    </a>
                    <a href="{{ route('dosage') }}" class="master-menu-item">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Medicine Dosage
                    </a>
                    <a href="{{ route('interval') }}" class="master-menu-item">
                        <i class="bi bi-clock-history"></i> Dose Interval
                    </a>
                    <a href="{{ route('duration') }}" class="master-menu-item">
                        <i class="bi bi-calendar-range-fill"></i> Dose Duration
                    </a>
                    <a href="{{ route('unit') }}" class="master-menu-item">
                        <i class="bi bi-rulers"></i> Measurement Unit
                    </a>
                    <a href="{{ route('company') }}" class="master-menu-item active border-bottom-0">
                        <i class="bi bi-buildings-fill"></i> Pharma Company
                    </a>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-xl-9 col-lg-8 col-md-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>Pharma Companies
                        </h5>

                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative" style="min-width: 240px;">
                                <input type="search" id="search" class="form-control form-control-sm pe-3 rounded-pill bg-light border" placeholder="Search company..." style="padding-left: 36px;">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-13"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="companyTable">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 70px;">#</th>
                                        <th class="py-3">Company Name</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($company as $key => $comp)
                                    <tr class="company-row" data-search="{{ strtolower($comp->company_name) }}">
                                        <td class="ps-4 fw-semibold text-muted fs-13">
                                            {{ method_exists($company, 'firstItem') ? ($company->firstItem() + $key) : $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13.5">{{ $comp->company_name }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs editBtn" data-id="{{ $comp->id }}" data-name="{{ $comp->company_name }}" data-bs-toggle="modal" data-bs-target="#addModal" title="Edit Company" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-pencil-fill fs-12"></i>
                                                </button>

                                                <form action="{{ route('deletecompany', $comp->id) }}" method="POST" class="deleteForm d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs deleteBtn" style="width: 32px; height: 32px;" title="Delete Company">
                                                        <i class="bi bi-trash-fill fs-12"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <i class="bi bi-buildings fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No pharma companies found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <p id="noResults" class="text-center text-muted py-4 mb-0" style="display:none;">
                                No matching companies found.
                            </p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($company, 'hasPages') && $company->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $company->firstItem() }}</strong> to <strong>{{ $company->lastItem() }}</strong> of <strong>{{ $company->total() }}</strong> companies
                        </div>
                        <div>
                            {{ $company->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= Add Company Modal ================= -->
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-plus-circle me-1.5"></i>Add Pharma Company
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('company.companyadd') }}" method="POST">
                    @csrf
                    <div id="companyContainer">
                        <div class="row mb-3 company-row">
                            <div class="col-10">
                                <label class="form-label fw-semibold text-dark fs-13 mb-1">
                                    Company Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="company_name[]" class="form-control rounded-3" placeholder="e.g., Sun Pharma, Cipla..." required>
                            </div>
                            <div class="col-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRow d-none" style="width:34px; height:34px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-3">
                        <button type="button" id="addRow" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> Add More Rows
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">
                            Save Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= Edit Company Modal ================= -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-pencil-square me-1.5"></i>Edit Pharma Company
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('company.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="company_id" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-13 mb-1">
                            Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="company_name" name="company_name" class="form-control rounded-3" placeholder="Enter company name..." required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('addRow').addEventListener('click', function () {
    let container = document.getElementById('companyContainer');
    let row = document.createElement('div');
    row.className = "row mb-3 company-row";
    row.innerHTML = `
        <div class="col-10">
            <input type="text" name="company_name[]" class="form-control rounded-3" placeholder="Enter Company Name..." required>
        </div>
        <div class="col-2 d-flex align-items-center">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRow" style="width:34px; height:34px;">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function(e){
    if(e.target.closest('.removeRow')){
        e.target.closest('.company-row').remove();
    }
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;
    document.getElementById('company_id').value = btn.getAttribute('data-id');
    document.getElementById('company_name').value = btn.getAttribute('data-name');
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const form = btn.closest('.deleteForm');
    Swal.fire({
        title: 'Are you sure?',
        text: "This company will be deleted permanently.",
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

function filterCompanies() {
    const query = document.getElementById('search').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#companyTable .company-row');
    let visibleCount = 0;
    rows.forEach(function (row) {
        const haystack = row.getAttribute('data-search') || '';
        const isMatch = haystack.includes(query);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCount++;
    });
    document.getElementById('noResults').style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
}

document.getElementById('search').addEventListener('input', filterCompanies);
</script>
@endsection