@extends("backend.include.layout")

@section('title', 'Symptoms Master Directory — Admin Console')

@section('content')
<div class="page-content wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-activity me-2 text-primary"></i>Symptoms Master Directory
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Master</span> &bull; Manage patient chief complaints, clinical symptoms &amp; vitals terms
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill shadow-xs">
                    <i class="bi bi-list-check me-1"></i> Total Symptoms: {{ method_exists($symptoms, 'total') ? $symptoms->total() : $symptoms->count() }}
                </span>
                <button type="button" class="btn btn-primary rounded-pill px-3.5 btn-sm fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#formModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Symptoms
                </button>
            </div>
        </div>

        <!-- Master Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>All Recorded Symptoms
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-1.5 fs-12 fw-bold rounded-pill">
                            {{ method_exists($symptoms, 'total') ? $symptoms->total() : $symptoms->count() }} Records
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 70px;">#</th>
                                        <th class="py-3" style="width: 120px;">Code</th>
                                        <th class="py-3">Symptom / Complaint</th>
                                        <th class="py-3">Created By</th>
                                        <th class="py-3">Updated By</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($symptoms as $sym)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted fs-13">
                                            {{ method_exists($symptoms, 'firstItem') ? ($symptoms->firstItem() + $loop->index) : $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold font-monospace">
                                                {{ $sym->symptom_code ?? ('SYM-' . str_pad($sym->id, 5, '0', STR_PAD_LEFT)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-activity text-primary fs-14"></i>
                                                <span class="fw-semibold text-dark fs-13.5">{{ $sym->symptom_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fs-13">
                                                <i class="bi bi-person me-1 text-muted"></i>{{ $sym->created_by ?? 'Admin' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-13">{{ $sym->updated_by ?? '-' }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs editBtn" data-id="{{ $sym->id }}" data-name="{{ $sym->symptom_name }}" data-bs-toggle="modal" data-bs-target="#editModal" title="Edit Symptom" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-pencil-fill fs-12"></i>
                                                </button>

                                                <form action="{{ route('deletesymptom', $sym->id) }}" method="POST" class="deleteForm d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs deleteBtn" style="width: 32px; height: 32px;" title="Delete Symptom">
                                                        <i class="bi bi-trash-fill fs-12"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-clipboard-x fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No symptom records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($symptoms, 'hasPages') && $symptoms->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $symptoms->firstItem() }}</strong> to <strong>{{ $symptoms->lastItem() }}</strong> of <strong>{{ $symptoms->total() }}</strong> symptoms
                        </div>
                        <div>
                            {{ $symptoms->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= Add Symptoms Modal ================= -->
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-plus-circle me-1.5"></i>Add New Symptoms
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('symptoms.add') }}" method="POST">
                    @csrf
                    <div id="symptomContainer">
                        <div class="row mb-3 symptom-row">
                            <div class="col-10">
                                <label class="form-label fw-semibold text-dark fs-13 mb-1">
                                    Symptom Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="symptom_name[]" class="form-control rounded-3" placeholder="e.g., High Fever, Dry Cough..." required>
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
                            Save Symptoms
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= Edit Symptom Modal ================= -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-pencil-square me-1.5"></i>Edit Symptom Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('symptoms.masterupdate') }}" method="POST">
                    @csrf
                    <input type="hidden" id="symptom_id" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-13 mb-1">
                            Symptom Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="symptom_name" name="symptom_name" class="form-control rounded-3" placeholder="Enter symptom name..." required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">Update Symptom</button>
                    </div>
                </form>
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
    e.stopPropagation();
    const form = btn.closest('.deleteForm');
    Swal.fire({
        title: 'Are you sure?',
        text: "This symptom will be deleted permanently.",
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

document.getElementById('addRow').addEventListener('click', function () {
    let container = document.getElementById('symptomContainer');
    let row = document.createElement('div');
    row.className = "row mb-3 symptom-row";
    row.innerHTML = `
        <div class="col-10">
            <input type="text" name="symptom_name[]" class="form-control rounded-3" placeholder="Enter symptom name..." required>
        </div>
        <div class="col-2 d-flex align-items-center">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle removeRow" style="width:34px; height:34px;">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.removeRow')) {
        e.target.closest('.symptom-row').remove();
    }
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;
    document.getElementById('symptom_id').value = btn.getAttribute('data-id');
    document.getElementById('symptom_name').value = btn.getAttribute('data-name');
});
</script>
@endsection
