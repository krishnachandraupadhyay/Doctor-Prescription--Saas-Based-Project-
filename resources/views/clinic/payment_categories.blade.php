@extends('clinic.include.layout')
@section('title', 'Payment Categories & Pricing - Clinic Portal')

@section('content')
<style>
    .valex-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #EEF3F1;
        box-shadow: 0 4px 16px -4px rgba(15, 59, 56, 0.08);
        overflow: hidden;
    }
    .valex-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FAFCFB;
        border-bottom: 1px solid #EEF3F1;
        padding: 16px 20px;
    }
    .valex-table th {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #718096;
        font-weight: 700;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .valex-table td {
        font-size: 13.5px;
        color: #1a202c;
        vertical-align: middle;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .price-badge {
        background: #EAF5F2;
        color: #175C55;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .price-none {
        color: #94a3b8;
        font-style: italic;
        font-size: 13px;
    }
    .btn-edit-price {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        color: #fff;
        border: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(123, 47, 247, 0.25);
    }
    .btn-edit-price:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(123, 47, 247, 0.35);
        color: #fff;
    }
    .badge-status-active {
        background: #d1fae5;
        color: #065f46;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .badge-status-inactive {
        background: #fee2e2;
        color: #991b1b;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
    #paymentPriceModal .modal-header {
        background: linear-gradient(135deg, #7b2ff7, #f107a3);
        color: #fff;
    }
    #paymentPriceModal .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Page Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    {{-- Left: Title & Info --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 24px;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Payment Categories &amp; Pricing</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->name }}
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                Manage consultation charges and service fee structures configured for this clinic.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Quick Stats --}}
                    <div class="d-flex align-items-center gap-2">
                        <div class="px-3 py-2 bg-light border rounded-3 text-dark fs-13 fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-layers-fill text-primary"></i>
                            <span>Total Categories: <strong class="text-primary">{{ $paymentCategories->count() }}</strong></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Payment Categories Table Card --}}
        <div class="valex-card mb-4">
            <div class="valex-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-list-check text-primary fs-16"></i>
                    <h5 class="fw-bold text-dark mb-0 fs-15">Configured Payment Categories</h5>
                </div>
                <small class="text-muted">Prices apply across clinic billing &amp; patient registration</small>
            </div>

            <div class="table-responsive">
                <table class="table valex-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">#</th>
                            <th>Category Name</th>
                            <th>Current Price</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Last Updated</th>
                            <th class="text-center" style="width: 100px;">Set Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentCategories as $category)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-dark fs-14">{{ $category->name }}</div>
                                    <small class="text-muted">ID: #{{ $category->id }}</small>
                                </td>
                                <td>
                                    @if(!is_null($category->price) && $category->price !== '')
                                        <span class="price-badge">
                                            <i class="bi bi-currency-rupee"></i>{{ number_format($category->price, 2) }}
                                        </span>
                                    @else
                                        <span class="price-none">Not configured</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->status == 1)
                                        <span class="badge-status-active"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                                    @else
                                        <span class="badge-status-inactive"><i class="bi bi-x-circle-fill me-1"></i>Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-secondary fs-13">{{ $category->created_by ?? 'System' }}</span>
                                </td>
                                <td>
                                    <span class="text-secondary fs-13">{{ $category->updated_by ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted fs-12">{{ $category->updated_at ? $category->updated_at->format('d M Y, h:i A') : '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn-edit-price"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentPriceModal"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}"
                                            data-category-price="{{ $category->price ?? '' }}"
                                            title="Set or Edit Price">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-cash-stack text-muted" style="font-size: 42px; opacity: 0.4;"></i>
                                        <h6 class="fw-bold text-secondary mt-3">No Payment Categories Configured</h6>
                                        <p class="text-muted fs-13 mb-0">
                                            Payment categories have not been assigned to your clinic yet. Please contact the onboarding administrator.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>

<!-- Add / Edit Price Modal -->
<div class="modal fade" id="paymentPriceModal" tabindex="-1" aria-labelledby="paymentPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form id="paymentPriceForm" action="{{ route('clinic.payment_categories.update_price') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" id="modal_category_id">
                
                <div class="modal-header py-3">
                    <h5 class="modal-title fs-16 fw-bold" id="paymentPriceModalLabel">
                        <i class="bi bi-currency-rupee me-1"></i> Update Category Fee
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary fs-13">Category Name</label>
                        <input type="text" id="modal_category_name" class="form-control bg-light" disabled style="font-weight: 600;">
                    </div>
                    <div class="mb-2">
                        <label for="modal_price" class="form-label fw-semibold text-secondary fs-13">Consultation / Service Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">₹</span>
                            <input type="number" name="price" id="modal_price" class="form-control" min="0" step="0.01" placeholder="e.g. 500.00" required>
                        </div>
                        <small class="text-muted fs-11 mt-1 d-block">Enter the standard consultation or service fee for this category.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm px-3 text-white fw-semibold rounded-2" style="background: linear-gradient(135deg, #7b2ff7, #f107a3);">
                        <i class="bi bi-check-lg me-1"></i> Save Price
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('paymentPriceModal');

    modalEl.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;

        const categoryId    = btn.getAttribute('data-category-id');
        const categoryName  = btn.getAttribute('data-category-name');
        const categoryPrice = btn.getAttribute('data-category-price');

        document.getElementById('modal_category_id').value = categoryId;
        document.getElementById('modal_category_name').value = categoryName;
        document.getElementById('modal_price').value = categoryPrice || '';
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('paymentPriceForm').reset();
    });
});
</script>
@endsection
