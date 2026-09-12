@extends("frontend.include.layout")
@section('title', 'Patient Advice & Suggestions - Doctor Portal')

@section('content')
<!---------------------------------------->
<style>
    /* ---------------- Card ---------------- */
    .card {
        height: auto;
        width: 100%;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.2rem 1.4rem;
    }

    .card-header {
        background: transparent;
        border: none;
        padding: 0 0 0.8rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .card-header h5 {
        font-weight: bold;
        font-size: 1.15rem;
        margin: 0;
        color: #1f2937;
    }

    .card-header .addsuggestion a {
        border: none;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        background-color: #4f46e5;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .3s;
    }

    .card-header .addsuggestion a:hover {
        background-color: #372fd3;
        color: #fff;
    }

    /* ---------------- Table ---------------- */
    .tabular-data {
        width: 100%;
    }

    .suggestion-table th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6b7280;
        border-bottom: 2px solid #f1f1f1 !important;
        background: #fff;
    }

    .suggestion-table td {
        vertical-align: middle;
    }

    .suggestion-table td.description-cell {
        max-width: 320px;
        white-space: normal;
        color: #4b5563;
        font-size: 0.92rem;
    }

    .action-icon {
        border: none;
        background: #f3f4f6;
        border-radius: 8px;
        padding: 6px 9px;
        font-size: 15px;
        color: #374151;
        transition: .2s;
        display: inline-flex;
        text-decoration: none;
    }

    .action-icon:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .action-icon.delete-icon:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    @media(max-width:650px) {
        .card-header {
            gap: 0.6rem;
        }
        .tabular-data {
            overflow-x: auto;
        }
        .suggestion-table td.description-cell {
            max-width: 200px;
        }
    }
</style>
<!---------------------------------------->
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="suggestion"><h5>Suggestions</h5></div>
                        <div class="addsuggestion">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#formModal">
                                <i class="bi bi-plus-lg"></i> Add Suggestion
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="tabular-data">
                        <table class="table table-hover suggestion-table">
                            <tr>
                                <th style="width:60px;">S.No.</th>
                                <th style="width:110px;">Code</th>
                                <th>Suggestion</th>
                                <th>Description</th>
                                <th class="text-end">Action</th>
                            </tr>
                            @forelse($suggest as $suggestion)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11 fw-bold font-monospace">
                                        {{ $suggestion->suggestion_code ?? ('SUG-' . str_pad($suggestion->id, 5, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </td>
                                <td>{{ $suggestion->suggestion_name }}</td>
                                <td class="description-cell">{{ $suggestion->description }}</td>
                                <td class="text-end">
                                    <a href="#"
                                       class="action-icon editBtn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editFormModal"
                                       data-id="{{ $suggestion->id }}"
                                       data-suggestion="{{ $suggestion->suggestion_name }}"
                                       data-description="{{ $suggestion->description }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('suggestion.delete', $suggestion->id) }}"
                                          method="POST" class="deleteForm d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-icon delete-icon deleteBtn border-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No suggestions found.</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

                <!------------Add Suggestion modal---------->
                <div class="modal fade" id="formModal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Add Suggestion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <form action="{{ route('suggestion.add') }}" method="POST">
                                    @csrf

                                    <div id="suggestionContainer">

                                        <div class="row mb-3 suggestion-row">

                                            <div class="col-12">
                                                <label class="form-label">
                                                    Suggestion
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text"
                                                       name="suggestion[]"
                                                       class="form-control"
                                                       placeholder="Enter suggestion">
                                            </div>

                                            <div class="col-12 mt-3">
                                                <label class="form-label">
                                                    Description
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <textarea class="form-control" name="description" rows="3"
                                                          placeholder="Enter description"></textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end mt-2">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm removeRow"
                                                        style="display:none;">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="addRow" class="btn btn-success">
                                        <i class="bi bi-plus-circle"></i> Add More
                                    </button>

                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

                <!------------Edit Suggestion modal---------->
                <div class="modal fade" id="editFormModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Suggestion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <form action="{{ url('suggestion/update') }}" method="POST" id="editSuggestionForm">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="id" id="edit_id">

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label">
                                                Suggestion
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                   name="suggestion_name"
                                                   id="edit_suggestion_name"
                                                   class="form-control"
                                                   placeholder="Enter suggestion">
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label">
                                                Description
                                                <span class="text-danger">*</span>
                                            </label>

                                            <textarea class="form-control" rows="3"
                                                      name="description"
                                                      id="edit_description"></textarea>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- container-fluid -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.getElementById('addRow').addEventListener('click', function () {

    let container = document.getElementById('suggestionContainer');

    let row = document.createElement('div');

    row.className = "row mb-3 suggestion-row";

    row.innerHTML = `
        <div class="col-12">
            <label class="form-label">Suggestion <span class="text-danger">*</span></label>
            <input type="text"
                   name="suggestion[]"
                   class="form-control"
                   placeholder="Enter suggestion">
        </div>

        <div class="col-12 mt-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control" name="description" rows="3" placeholder="Enter description"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-danger btn-sm removeRow">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
    `;

    container.appendChild(row);

});


document.addEventListener('click', function (e) {

    if (e.target.closest('.removeRow')) {
        e.target.closest('.suggestion-row').remove();
    }

});


/* -------- Edit modal: fill form fields from clicked row's data-* -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.editBtn');
    if (!btn) return;

    document.getElementById('edit_id').value = btn.getAttribute('data-id');
    document.getElementById('edit_suggestion_name').value = btn.getAttribute('data-suggestion');
    document.getElementById('edit_description').value = btn.getAttribute('data-description');

    document.getElementById('editSuggestionForm').action =
        "{{ url('suggestion/update') }}/" + btn.getAttribute('data-id');
});


/* -------- Delete: confirm before submitting -------- */
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.deleteBtn');
    if (!btn) return;

    e.preventDefault();
    e.stopPropagation();

    const form = btn.closest('.deleteForm');

    Swal.fire({
        title: 'Are you sure?',
        text: "This suggestion will be deleted permanently.",
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
