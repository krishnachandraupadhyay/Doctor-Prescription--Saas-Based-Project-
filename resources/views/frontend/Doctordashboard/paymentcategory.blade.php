@extends("frontend.include.layout")
@section('title', 'Payment Categories & Consultation Fees - Doctor Portal')

@section('content')
<style>
    .table-card{
        background:#fff;
        border-radius:14px;
        border:1px solid #EEF3F1;
        box-shadow:0 6px 18px -8px rgba(15,59,56,0.12);
        overflow:hidden;
    }
    .table-card .card-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#FAFCFB;
        border-bottom:1px solid #EEF3F1;
        padding:16px 20px;
        font-weight:700;
        color:#0F3B38;
        font-size:14.5px;
    }
    .table-card table th{
        font-size:11.5px;
        text-transform:uppercase;
        letter-spacing:0.04em;
        color:#9FB5B1;
        font-weight:700;
        border-bottom:1px solid #EEF3F1;
    }
    .table-card table td{
        font-size:14px;
        color:#0E2624;
        vertical-align:middle;
    }
    .price-badge{
        background:#EAF5F2;
        color:#175C55;
        padding:4px 12px;
        border-radius:20px;
        font-size:12.5px;
        font-weight:600;
    }
    .price-none{
        color:#C4D2CF;
        font-style:italic;
        font-size:13px;
    }
    .add-price-btn{
        width:34px;
        height:34px;
        border-radius:8px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:linear-gradient(135deg, #7b2ff7, #f107a3);
        color:#fff;
        border:none;
    }
    .add-price-btn:hover{
        opacity:0.85;
        color:#fff;
    }
    #paymentPriceModal .modal-header{
        background:linear-gradient(135deg, #7b2ff7, #f107a3);
        color:#fff;
    }
    #paymentPriceModal .modal-header .btn-close{
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-3">
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-credit-card-2-front text-primary me-2"></i> Payment Categories &amp; Consultation Fees
                    </h5>
                    <p class="text-muted fs-13 mb-0">Payment categories are configured by Clinic &amp; Onboarding Administration. You can set the consultation fee/price for each category below.</p>
                </div>

                <div class="table-card">
                    <div class="card-header">
                        <span>Assigned Payment Categories</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Category Name</th>
                                    <th>Price</th>
                                    <th>Created By</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentCategories as $category)
                                    <tr>
                                        <td class="ps-4">{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->price)
                                                <span class="price-badge">₹{{ number_format($category->price, 2) }}</span>
                                            @else
                                                <span class="price-none">Not set</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_by }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="add-price-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#paymentPriceModal"
                                                    data-category-id="{{ $category->id }}"
                                                    data-category-name="{{ $category->name }}"
                                                    data-category-price="{{ $category->price ?? '' }}"
                                                    title="Add / Edit Price">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Abhi tak koi payment category assign nahi hui hai.
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
    <!-- container-fluid -->
</div>

<!-- Add / Edit Price Modal -->
<div class="modal fade" id="paymentPriceModal" tabindex="-1" aria-labelledby="paymentPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="paymentPriceForm" action="{{ route('doctor.payment-category.update-price') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" id="modal_category_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentPriceModalLabel">Set Category Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Category</label>
                        <input type="text" id="modal_category_name" class="form-control" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="modal_price" class="form-label fw-semibold text-secondary">Price (₹)</label>
                        <input type="number" name="price" id="modal_price" class="form-control" min="0" step="0.01" placeholder="Enter price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #7b2ff7, #f107a3); color:#fff;">
                        Save Price
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

    // reset form when modal closes
    modalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('paymentPriceForm').reset();
    });
});
</script>
@endsection
