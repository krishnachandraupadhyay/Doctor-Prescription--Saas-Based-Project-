@extends('backend.include.layout')
@section('title', 'Payment Categories Directory - Admin Console')
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
    .badge-active{
        background:#d1f7e0;
        color:#0f9d58;
        padding:0.25rem 0.6rem;
        border-radius:1rem;
        font-size:0.78rem;
        font-weight:600;
    }
    .badge-inactive{
        background:#fde2e2;
        color:#dc3545;
        padding:0.25rem 0.6rem;
        border-radius:1rem;
        font-size:0.78rem;
        font-weight:600;
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
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">

                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-0">Payment Categories</h4>
                    <p class="text-muted small">Doctor ke naam, category aur price ke saath saari list.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-card">
                    <div class="card-header">All Payment Categories</div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Clinic / Doctor</th>
                                    <th>Category Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentCategories as $category)
                                    <tr>
                                        <td class="ps-4">{{ $loop->iteration }}</td>
                                        <td>{{ optional($category->clinic)->name ?? (optional($category->doctor)->name ?? 'Unassigned') }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->price)
                                                <span class="price-badge">₹{{ number_format($category->price, 2) }}</span>
                                            @else
                                                <span class="price-none">Not set</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->status == 1)
                                                <span class="badge-active">Active</span>
                                            @else
                                                <span class="badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_at?->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Abhi tak koi payment category nahi mili.
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
@endsection