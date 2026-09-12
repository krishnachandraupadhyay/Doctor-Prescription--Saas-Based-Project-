@extends('backend.include.layout')

@section('title', 'Feature Catalog - Admin Portal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    System Feature Catalog
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">SaaS Feature Gates</span> &bull; Define modules and privileges available for subscription plan allocation
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-layers me-1"></i> Subscription Plans
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                    <i class="bi bi-plus-circle-fill me-1"></i> Add New Feature
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- KPI Statistic Row -->
        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Catalog Features</span>
                            <h3 class="mb-0 fw-bold mt-1 text-primary">{{ $totalFeatures }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-list-stars fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Active Features</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $activeFeatures }}</h3>
                        </div>
                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-check2-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Categories</span>
                            <h3 class="mb-0 fw-bold mt-1 text-info">{{ $features->count() }}</h3>
                        </div>
                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-tags-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Table by Category -->
        @foreach($features as $category => $catFeatures)
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                        <i class="bi bi-folder2-open text-primary me-2"></i> {{ $category }} Features
                    </h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 fs-11">
                        {{ $catFeatures->count() }} Feature{{ $catFeatures->count() > 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 valex-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 fs-12 fw-semibold text-muted">FEATURE NAME</th>
                                    <th class="fs-12 fw-semibold text-muted">CODE / GATE KEY</th>
                                    <th class="fs-12 fw-semibold text-muted">DESCRIPTION</th>
                                    <th class="fs-12 fw-semibold text-muted text-center">SORT</th>
                                    <th class="fs-12 fw-semibold text-muted text-center">STATUS</th>
                                    <th class="pe-3 fs-12 fw-semibold text-muted text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($catFeatures as $feat)
                                    <tr>
                                        <td class="ps-3 py-2.5">
                                            <div class="fw-bold text-dark fs-13">{{ $feat->name }}</div>
                                        </td>
                                        <td>
                                            <code class="text-primary bg-light px-2 py-1 rounded fs-12 font-monospace">{{ $feat->code }}</code>
                                        </td>
                                        <td class="text-muted fs-12" style="max-width: 320px;">
                                            {{ $feat->description ?? 'No description.' }}
                                        </td>
                                        <td class="text-center text-muted fs-12 font-monospace">
                                            {{ $feat->sort_order }}
                                        </td>
                                        <td class="text-center">
                                            @if($feat->is_active)
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 fs-11">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 fs-11">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editFeatureModal_{{ $feat->id }}" title="Edit Feature">
                                                    <i class="bi bi-pencil-square text-primary"></i>
                                                </button>
                                                <form method="POST" action="{{ route('admin.subscriptions.features.toggle-status', $feat) }}" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light border" title="Toggle Status">
                                                        <i class="bi {{ $feat->is_active ? 'bi-toggle-on text-success fs-6' : 'bi-toggle-off text-muted fs-6' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal: Edit Feature -->
                                    <div class="modal fade" id="editFeatureModal_{{ $feat->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold fs-15 text-dark">
                                                        Edit Feature: {{ $feat->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.subscriptions.features.update', $feat) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold fs-13">Feature Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $feat->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold fs-13">Category <span class="text-danger">*</span></label>
                                                            <input type="text" name="category" class="form-control" value="{{ $feat->category }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold fs-13">Description</label>
                                                            <textarea name="description" class="form-control" rows="2">{{ $feat->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold fs-13">Display Order</label>
                                                            <input type="number" name="sort_order" class="form-control" value="{{ $feat->sort_order }}" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>

<!-- Modal: Add Feature -->
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-labelledby="addFeatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addFeatureModalLabel">
                    <i class="bi bi-plus-circle-fill me-2"></i> Add New Feature to Catalog
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.subscriptions.features.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Feature Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. WhatsApp Prescription Share" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Feature Key Code (slug)</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. whatsapp_share (auto-generated if empty)">
                        <small class="text-muted fs-11">Unique identifier used for permission checks in code.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Category Grouping <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="Clinical">Clinical</option>
                            <option value="Staff & Security">Staff & Security</option>
                            <option value="Billing">Billing</option>
                            <option value="Branding">Branding</option>
                            <option value="Storage">Storage</option>
                            <option value="Analytics">Analytics</option>
                            <option value="Communication">Communication</option>
                            <option value="General">General</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Describe what this feature enables..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
