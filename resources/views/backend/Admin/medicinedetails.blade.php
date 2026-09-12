@extends('backend.include.layout')

@section('title', 'Medicine Inventory Directory — Admin Console')

@section('content')
<style>
    .bg-purple-subtle {
        background-color: #f4f2ff !important;
    }
    .text-purple {
        color: #6c5ffc !important;
    }
    .border-purple-subtle {
        border-color: #d1cbfb !important;
    }
</style>

<div class="page-content wrapper py-4">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-capsule me-2 text-primary"></i>Medicine Details Directory
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Super Admin Console</span> &bull; All registered medicines, generics, brands &amp; categories
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 fw-bold rounded-pill">
                    <i class="bi bi-boxes me-1"></i> Total Records: {{ $medicines->total() }}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Card Header with Search Bar -->
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark fs-15">
                            <i class="bi bi-table me-2 text-primary"></i>Medicines List
                        </h5>

                        <form action="{{ route('/medicinedetails') }}" method="GET" class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm style-search-group" style="min-width: 280px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search medicine, generic, company..." value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('/medicinedetails') }}" class="btn btn-outline-secondary btn-sm" title="Clear Search">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Card Body with Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="medicineTable">
                                <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 60px;">#</th>
                                        <th class="py-3">Medicine Name</th>
                                        <th class="py-3">Generic Name</th>
                                        <th class="py-3">Company Name</th>
                                        <th class="pe-4 py-3">Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($medicines as $key => $item)
                                    @php
                                        $catName = !empty(trim($item->category->category_name ?? '')) 
                                            ? trim($item->category->category_name) 
                                            : (\App\Models\medicine_categorie::where('id', $item->category_id)->value('category_name') ?? null);
                                        $compName = !empty(trim($item->company->company_name ?? '')) 
                                            ? trim($item->company->company_name) 
                                            : (\App\Models\company_name::where('id', $item->company_id)->value('company_name') ?? null);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ $medicines->firstItem() + $key }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-capsule text-primary fs-14"></i>
                                                <span class="fw-bold text-dark fs-13">{{ $item->medicine_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-13">
                                                {{ $item->generic_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!empty($compName))
                                                <span class="badge bg-light text-dark border font-monospace fs-12 px-2.5 py-1 rounded">
                                                    <i class="bi bi-building me-1 text-primary"></i>{{ $compName }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2.5 py-1 fs-12 fw-normal rounded">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            @if(!empty($catName))
                                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-3 py-1 fs-12 fw-semibold rounded-pill">
                                                    <i class="bi bi-tag-fill me-1"></i>{{ $catName }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2.5 py-1 fs-12 fw-normal rounded">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-capsule fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            No medicine records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card Footer with Pagination Links -->
                    @if($medicines->hasPages() || $medicines->total() > 0)
                    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="text-muted fs-12">
                            Showing <strong>{{ $medicines->firstItem() ?? 0 }}</strong> to <strong>{{ $medicines->lastItem() ?? 0 }}</strong> of <strong>{{ $medicines->total() }}</strong> medicines
                        </div>
                        <div>
                            {{ $medicines->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
