@extends('middleend.include.layout')
@section('title', 'Payment Categories Master - Onboarding Portal')
@section('content')
<style>
    .card {
        padding:0.2rem;
    }
    .card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card .card-header .addbutton{
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        padding: 0.4rem 0.9rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .card .card-header .addbutton a,
    .card .card-header .addbutton {
        color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
    }

    /* Modal styling */
    #paymentCategoryModal .modal-header {
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        color: #fff;
    }
    #paymentCategoryModal .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    .category-row {
        display: flex;
        gap: 0.6rem;
        align-items: center;
        margin-bottom: 0.65rem;
    }
    .category-row .category-name-input,
    .category-row input.form-control {
        flex: 2;
        min-width: 180px;
    }
    .category-row .clinic-select {
        flex: 1.1;
        min-width: 160px;
        max-width: 230px;
    }
    .category-row .status-select {
        width: 110px;
        max-width: 110px;
        flex-shrink: 0;
    }
    .category-row .remove-row {
        background: #fee2e2;
        color: #dc3545;
        border: 1px solid #fecaca;
        border-radius: 0.25rem;
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .category-row .remove-row:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    @media (max-width: 576px) {
        .category-row {
            flex-wrap: wrap;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #e2e8f0;
        }
        .category-row .category-name-input,
        .category-row input.form-control {
            width: 100%;
            flex: 1 1 100%;
        }
        .category-row .clinic-select {
            flex: 1;
            max-width: 100%;
        }
    }
    #addMoreRowBtn {
        background: #f1f1f1;
        border: 1px dashed #7b2ff7;
        color: #7b2ff7;
        border-radius: 0.25rem;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    #addMoreRowBtn:hover {
        background: #7b2ff7;
        color: #fff;
    }
    .badge-active {
        background: #d1f7e0;
        color: #0f9d58;
        padding: 0.25rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.78rem;
    }
    .badge-inactive {
        background: #fde2e2;
        color: #dc3545;
        padding: 0.25rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.78rem;
    }
    .status-toggle {
        display: inline-block;
        user-select: none;
    }
    .status-toggle:hover {
        opacity: 0.75;
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Payment Method</h5>
                            <div class="addbutton">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#paymentCategoryModal">
                                    <i class="bi bi-plus"></i> Payment Category
                                </a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive mt-3">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Clinic</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Updated By</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paymentCategories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->clinic->name ?? ($category->doctor->name ?? '-') }}</td>
                                            <td>
                                                <span class="status-toggle {{ $category->status == 1 ? 'badge-active' : 'badge-inactive' }}"
                                                      data-id="{{ $category->id }}"
                                                      data-status="{{ $category->status }}"
                                                      title="Click to toggle status">
                                                    {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $category->created_by ?? '-' }}</td>
                                            <td>{{ $category->updated_by ?? '-' }}</td>
                                            <td>{{ $category->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Abhi tak koi payment category add nahi hui hai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>

<!-- Payment Category Modal -->
<div class="modal fade" id="paymentCategoryModal" tabindex="-1" aria-labelledby="paymentCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="paymentCategoryForm" action="{{ route('payment-category.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentCategoryModalLabel">Add Payment Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="d-none d-sm-flex gap-2 mb-2 px-1 text-muted fs-13 fw-semibold">
                        <div style="flex: 2; min-width: 180px;">Category Name <span class="text-danger">*</span></div>
                        <div style="flex: 1.1; min-width: 160px; max-width: 230px;">Clinic <span class="text-danger">*</span></div>
                        <div style="width: 110px;">Status</div>
                        <div style="width: 36px; text-align: center;"></div>
                    </div>

                    <div id="categoryRowsWrapper">
                        <div class="category-row">
                            <input type="text" name="category_name[]" class="form-control category-name-input" placeholder="Category name (e.g. Consultation Fee)" required>
                            <select name="clinic_id[]" class="form-select clinic-select" required>
                                <option value="" selected disabled>Select Clinic</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">{{ $clinic->name }} ({{ $clinic->clinic_id ?? 'CLN-'.$clinic->id }})</option>
                                @endforeach
                            </select>
                            <select name="category_status[]" class="form-select status-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <button type="button" class="remove-row" title="Remove"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    <button type="button" id="addMoreRowBtn" class="mt-2">
                        <i class="bi bi-plus-circle"></i> Add More
                    </button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #7b2ff7, #f107a3); color:#fff;">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('categoryRowsWrapper');
    const addBtn = document.getElementById('addMoreRowBtn');

    // clinics list controller se JSON me aa rahi hai, taaki naye rows me bhi dropdown ban sake
    const clinicsList = @json($clinics->map(function ($c) { return ['id' => $c->id, 'name' => $c->name . ' (' . ($c->clinic_id ?? ('CLN-' . $c->id)) . ')']; }));

    function clinicOptionsHtml() {
        let html = `<option value="" selected disabled>Select Clinic</option>`;
        clinicsList.forEach(function (cln) {
            html += `<option value="${cln.id}">${cln.name}</option>`;
        });
        return html;
    }

    // template for a fresh row
    function rowTemplate() {
        const div = document.createElement('div');
        div.className = 'category-row';
        div.innerHTML = `
            <input type="text" name="category_name[]" class="form-control category-name-input" placeholder="Category name (e.g. Consultation Fee)" required>
            <select name="clinic_id[]" class="form-select clinic-select" required>
                ${clinicOptionsHtml()}
            </select>
            <select name="category_status[]" class="form-select status-select">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
            <button type="button" class="remove-row" title="Remove"><i class="bi bi-trash"></i></button>
        `;
        return div;
    }

    addBtn.addEventListener('click', function () {
        wrapper.appendChild(rowTemplate());
    });

    // remove row (event delegation, so dynamically added rows bhi kaam karein)
    wrapper.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row');
        if (!btn) return;
        const rows = wrapper.querySelectorAll('.category-row');
        if (rows.length > 1) {
            btn.closest('.category-row').remove();
        } else {
            // last row ko clear kar do, remove mat karo
            btn.closest('.category-row').querySelector('input').value = '';
        }
    });

    // reset form + rows jab modal band ho
    const modalEl = document.getElementById('paymentCategoryModal');
    modalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('paymentCategoryForm').reset();
        const rows = wrapper.querySelectorAll('.category-row');
        rows.forEach((row, index) => {
            if (index > 0) row.remove();
        });
    });

    // ---------------------------------------------------
    // STATUS TOGGLE (Active / Inactive) - click to flip
    // ---------------------------------------------------
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('CSRF meta tag not found. Add <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout head.');
    }

    // event delegation on document, works even if rows are re-rendered later
    document.addEventListener('click', function (e) {
        const badgeEl = e.target.closest('.status-toggle');
        if (!badgeEl) return;

        const id = badgeEl.getAttribute('data-id');
        if (!id) return;

        // prevent double clicks while request is in flight
        if (badgeEl.dataset.loading === 'true') return;
        badgeEl.dataset.loading = 'true';
        const originalText = badgeEl.textContent;
        badgeEl.style.opacity = '0.5';

        fetch(`/payment-category/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : ''
            }
        })
        .then(async (response) => {
            if (!response.ok) {
                const text = await response.text();
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.status == 1) {
                    badgeEl.textContent = 'Active';
                    badgeEl.classList.remove('badge-inactive');
                    badgeEl.classList.add('badge-active');
                    badgeEl.setAttribute('data-status', 1);
                } else {
                    badgeEl.textContent = 'Inactive';
                    badgeEl.classList.remove('badge-active');
                    badgeEl.classList.add('badge-inactive');
                    badgeEl.setAttribute('data-status', 0);
                }
            } else {
                badgeEl.textContent = originalText;
                alert('Status update failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Toggle status error:', error);
            badgeEl.textContent = originalText;
            alert('Status update failed. Please try again.');
        })
        .finally(() => {
            badgeEl.style.opacity = '1';
            badgeEl.dataset.loading = 'false';
        });
    });
});
</script>
@endsection