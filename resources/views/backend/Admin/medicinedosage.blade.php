@extends("backend.include.layout")

@section('title', 'Medicine Dosage Master — Admin Console')

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
                    <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Medicine Dosage Master
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Master</span> &bull; Manage prescribed dosages per category &amp; measurement units
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3.5 btn-sm fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#formModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Dosage
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
                    <a href="{{ route('dosage') }}" class="master-menu-item active">
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
                    <a href="{{ route('company') }}" class="master-menu-item border-bottom-0">
                        <i class="bi bi-buildings-fill"></i> Pharma Company
                    </a>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-xl-9 col-lg-8 col-md-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>Medicine Dosage List
                        </h5>

                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative" style="min-width: 240px;">
                                <input type="search" id="search" class="form-control form-control-sm pe-3 rounded-pill bg-light border" placeholder="Search dosage..." style="padding-left: 36px;">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-13"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="dosageTable">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 70px;">#</th>
                                        <th class="py-3">Category</th>
                                        <th class="py-3">Dosage Name</th>
                                        <th class="py-3">Unit</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dos as $key => $dosage)
                                    @php
                                        $catName = $categ->where('id', $dosage->category_id)->first()->category_name ?? 'Standard';
                                        $unitName = $uni->where('id', $dosage->unit_id)->first()->unit_name ?? '-';
                                        $searchBlob = strtolower($catName . ' ' . $dosage->dosage_name . ' ' . $unitName);
                                    @endphp
                                    <tr class="dosage-row" data-search="{{ $searchBlob }}">
                                        <td class="ps-4 fw-semibold text-muted fs-13">
                                            {{ method_exists($dos, 'firstItem') ? ($dos->firstItem() + $key) : $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13.5">{{ $catName }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13.5">{{ $dosage->dosage_name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-13">{{ $unitName }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs editBtn" data-id="{{ $dosage->id }}" data-category="{{ $dosage->category_id }}" data-dosage="{{ $dosage->dosage_name }}" data-unit="{{ $dosage->unit_id }}" data-bs-toggle="modal" data-bs-target="#addModal" title="Edit Dosage" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-pencil-fill fs-12"></i>
                                                </button>

                                                <form action="{{ route('deletedosage', $dosage->id) }}" method="POST" class="deleteForm d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs deleteBtn" style="width: 32px; height: 32px;" title="Delete Dosage">
                                                        <i class="bi bi-trash-fill fs-12"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-grid fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No medicine dosage records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <p id="noResults" class="text-center text-muted py-4 mb-0" style="display:none;">
                                No matching dosages found.
                            </p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($dos, 'hasPages') && $dos->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $dos->firstItem() }}</strong> to <strong>{{ $dos->lastItem() }}</strong> of <strong>{{ $dos->total() }}</strong> dosages
                        </div>
                        <div>
                            {{ $dos->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= Add Dosage Modal ================= -->
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-plus-circle me-1.5"></i>Add Medicine Dosage
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dosage.add') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select rounded-3" required>
                                <option value="">Select Category</option>
                                @foreach($categ as $cat)
                                <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Unit <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select rounded-3" required>
                                <option value="">Select Unit</option>
                                @foreach($uni as $unit)
                                <option value="{{ $unit->id}}">{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Dosage Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" name="dosage" placeholder="e.g., 500, 1-0-1, 1 Tab..." required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">Save Dosage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= Edit Dosage Modal ================= -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-pencil-square me-1.5"></i>Edit Medicine Dosage
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dosage.add') }}" method="POST">
                    @csrf
                    <input type="hidden" id="dosage_id" name="id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Category <span class="text-danger">*</span></label>
                            <select name="category" id="dosage_category" class="form-select rounded-3" required>
                                <option value="">Select Category</option>
                                @foreach($categ as $cat)
                                <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Unit <span class="text-danger">*</span></label>
                            <select name="unit" id="dosage_unit" class="form-select rounded-3" required>
                                <option value="">Select Unit</option>
                                @foreach($uni as $unit)
                                <option value="{{ $unit->id}}">{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark fs-13 mb-1">Dosage Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="dosage_name" name="dosage" placeholder="Enter dosage name..." required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">Update Dosage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;
    document.getElementById('dosage_id').value = btn.getAttribute('data-id');
    document.getElementById('dosage_category').value = btn.getAttribute('data-category');
    document.getElementById('dosage_unit').value = btn.getAttribute('data-unit');
    document.getElementById('dosage_name').value = btn.getAttribute('data-dosage');
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const form = btn.closest('.deleteForm');
    Swal.fire({
        title: 'Are you sure?',
        text: "This dosage will be deleted permanently.",
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

function filterDosages() {
    const query = document.getElementById('search').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#dosageTable .dosage-row');
    let visibleCount = 0;
    rows.forEach(function (row) {
        const haystack = row.getAttribute('data-search') || '';
        const isMatch = haystack.includes(query);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCount++;
    });
    document.getElementById('noResults').style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
}

document.getElementById('search').addEventListener('input', filterDosages);
</script>
@endsection
