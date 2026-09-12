@extends("backend.include.layout")

@section('title', 'Diagnosis Test Master — Admin Console')

@section('content')
<div class="page-content wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-heart-pulse-fill me-2 text-primary"></i>Diagnosis Test Master Directory
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Clinical Master</span> &bull; Manage laboratory tests, radiology &amp; diagnostic investigation types
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill shadow-xs">
                    <i class="bi bi-eyedropper me-1"></i> Total Tests: {{ method_exists($tests, 'total') ? $tests->total() : $tests->count() }}
                </span>
                <button type="button" class="btn btn-primary rounded-pill px-3.5 btn-sm fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#formModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Diagnosis Test
                </button>
            </div>
        </div>

        <!-- Master Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>All Diagnostic Tests
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-1.5 fs-12 fw-bold rounded-pill">
                            {{ method_exists($tests, 'total') ? $tests->total() : $tests->count() }} Records
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 70px;">#</th>
                                        <th class="py-3">Test Name</th>
                                        <th class="py-3">Description</th>
                                        <th class="py-3">Created By</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tests as $test)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted fs-13">
                                            {{ method_exists($tests, 'firstItem') ? ($tests->firstItem() + $loop->index) : $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-check2-square text-success fs-14"></i>
                                                <span class="fw-semibold text-dark fs-13.5">{{ $test->test_name }}</span>
                                            </div>
                                        </td>
                                        <td style="max-width: 320px;">
                                            <span class="text-muted fs-13 text-truncate d-inline-block style-max-300" title="{{ $test->description }}">
                                                {{ $test->description ?? 'No description provided' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fs-13">
                                                <i class="bi bi-person me-1 text-muted"></i>{{ $test->created_by ?? 'Admin' }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs editBtn" data-bs-toggle="modal" data-bs-target="#editFormModal" data-id="{{ $test->id }}" data-name="{{ $test->test_name }}" data-description="{{ $test->description }}" title="Edit Test" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-pencil-fill fs-12"></i>
                                                </button>

                                                <form action="{{ route('deletediagnosistest', $test->id) }}" method="POST" class="deleteForm d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs deleteBtn" style="width: 32px; height: 32px;" title="Delete Test">
                                                        <i class="bi bi-trash-fill fs-12"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-folder-x fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No diagnostic test records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($tests, 'hasPages') && $tests->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $tests->firstItem() }}</strong> to <strong>{{ $tests->lastItem() }}</strong> of <strong>{{ $tests->total() }}</strong> tests
                        </div>
                        <div>
                            {{ $tests->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= Add Diagnosis Test Modal ================= -->
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-plus-circle me-1.5"></i>Add Diagnosis Test
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('diagnosistest.add') }}" method="POST">
                    @csrf
                    <div id="testContainer">
                        <div class="row mb-3 test-row">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark fs-13 mb-1">
                                    Test Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="test_name[]" class="form-control rounded-3" placeholder="e.g., Complete Blood Count (CBC)..." required>
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label fw-semibold text-dark fs-13 mb-1">Description</label>
                                <textarea class="form-control rounded-3" name="description" rows="2" placeholder="Optional notes or instructions..."></textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 removeRow d-none">
                                    <i class="bi bi-trash me-1"></i> Remove Row
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-3">
                        <button type="button" id="addRow" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> Add More Tests
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">
                            Save Diagnosis Test
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= Edit Diagnosis Test Modal ================= -->
<div class="modal fade" id="editFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold fs-15 text-white">
                    <i class="bi bi-pencil-square me-1.5"></i>Edit Diagnosis Test
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" method="POST" id="editTestForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-13 mb-1">
                            Test Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="test_name" id="edit_test_name" class="form-control rounded-3" placeholder="Enter test name..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-13 mb-1">Description</label>
                        <textarea class="form-control rounded-3" rows="3" name="description" id="edit_description" placeholder="Enter description..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">Update Test</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('addRow').addEventListener('click', function () {
    let container = document.getElementById('testContainer');
    let row = document.createElement('div');
    row.className = "row mb-3 test-row border-top pt-3";
    row.innerHTML = `
        <div class="col-12">
            <label class="form-label fw-semibold text-dark fs-13 mb-1">Test Name <span class="text-danger">*</span></label>
            <input type="text" name="test_name[]" class="form-control rounded-3" placeholder="Enter test name..." required>
        </div>
        <div class="col-12 mt-2">
            <label class="form-label fw-semibold text-dark fs-13 mb-1">Description</label>
            <textarea class="form-control rounded-3" name="description" rows="2" placeholder="Enter description..."></textarea>
        </div>
        <div class="col-12 d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 removeRow">
                <i class="bi bi-trash me-1"></i> Remove Row
            </button>
        </div>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.removeRow')) {
        e.target.closest('.test-row').remove();
    }
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;
    document.getElementById('edit_test_name').value = btn.getAttribute('data-name');
    document.getElementById('edit_description').value = btn.getAttribute('data-description');
    document.getElementById('editTestForm').action = "{{ url('diagnosistest.update') }}/" + btn.getAttribute('data-id');
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const form = btn.closest('.deleteForm');
    Swal.fire({
        title: 'Are you sure?',
        text: "This diagnosis test will be deleted permanently.",
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
