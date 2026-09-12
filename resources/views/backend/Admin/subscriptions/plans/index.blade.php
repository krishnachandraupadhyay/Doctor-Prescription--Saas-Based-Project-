@extends('backend.include.layout')

@section('title', 'Subscription Plans - Admin Portal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Subscription Plans Management
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">SaaS Pricing & Tiers</span> &bull; Configure plans, feature access, and practice quotas
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people-fill me-1"></i> View Subscribers
                </a>
                <a href="{{ route('admin.subscriptions.features.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list-check me-1"></i> Feature Catalog
                </a>
                <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> Create New Plan
                </a>
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

        <!-- KPI Statistic Row (1 line: 4 per row on desktop) -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Plans</span>
                            <h3 class="mb-0 fw-bold mt-1 text-dark">{{ $totalPlans }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-layers-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Active Plans</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $activePlans }}</h3>
                        </div>
                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-check2-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Subscribed Clinics</span>
                            <h3 class="mb-0 fw-bold mt-1 text-info">{{ $totalSubscribers }}</h3>
                        </div>
                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-hospital fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Pricing Type</span>
                            <h5 class="mb-0 fw-bold mt-1 text-dark">SaaS Tiered</h5>
                        </div>
                        <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-currency-rupee fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body py-3 px-4">
                <form method="GET" action="{{ route('admin.subscriptions.plans.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search plan name, description..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="cycle" class="form-select">
                            <option value="">All Billing Cycles</option>
                            <option value="monthly" {{ request('cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ request('cycle') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ request('cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="lifetime" {{ request('cycle') === 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100" title="Filter"><i class="bi bi-filter"></i></button>
                        @if(request()->anyFilled(['search', 'status', 'cycle']))
                            <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-light" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Plan Pricing Cards Grid (2 per row) -->
        <div class="row g-4 mb-4">
            @forelse($plans as $plan)
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="card h-100 shadow-sm border-0 rounded-4 position-relative overflow-hidden {{ $plan->is_popular ? 'border border-2 border-primary' : '' }}">
                        @if($plan->is_popular)
                            <div class="position-absolute top-0 end-0 bg-primary text-white fs-11 fw-bold px-3 py-1 rounded-bottom-start">
                                POPULAR
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 fs-17">{{ $plan->name }}</h5>
                                    <small class="text-muted fs-11 font-monospace">slug: {{ $plan->slug }}</small>
                                </div>
                                <span class="badge {{ $plan->status->badgeClasses() }} rounded-pill px-3 py-1 fs-12">
                                    {{ $plan->status->label() }}
                                </span>
                            </div>

                            <p class="text-muted fs-13 mb-3">
                                {{ $plan->description ?? 'No description provided.' }}
                            </p>

                            <div class="d-flex align-items-baseline mb-3 p-2.5 bg-light rounded-3">
                                <span class="fs-1 fw-black text-dark">{{ $plan->formattedPrice() }}</span>
                                <span class="text-muted fs-13 ms-1.5 fw-medium">/ {{ $plan->billing_cycle->label() }}</span>
                                @if($plan->trial_days > 0)
                                    <span class="badge bg-info-subtle text-info ms-auto rounded-pill px-2.5 py-1 fs-11">
                                        {{ $plan->trial_days }} Days Free Trial
                                    </span>
                                @endif
                            </div>

                            <!-- Quota Limits Section -->
                            <div class="pt-2 mb-3">
                                <div class="fs-11 fw-bold text-uppercase text-muted mb-2 tracking-wide">
                                    <i class="bi bi-speedometer2 text-warning me-1"></i> Quota Limits
                                </div>
                                <div class="table-responsive rounded-2 border border-light">
                                    <table class="table table-sm table-hover align-middle mb-0 fs-12">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" style="width: 45px;" class="ps-3 py-1.5 fw-semibold text-muted">#</th>
                                                <th scope="col" class="py-1.5 fw-semibold text-muted">Quota Metric</th>
                                                <th scope="col" style="width: 90px;" class="pe-3 py-1.5 text-end fw-semibold text-muted">Limit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-3 py-1.5 text-muted fw-semibold">1</td>
                                                <td class="py-1.5 text-dark fw-medium">
                                                    <i class="bi bi-people me-1.5 text-primary"></i> Patients / Month
                                                </td>
                                                <td class="pe-3 py-1.5 text-end fw-bold text-dark">
                                                    {{ $plan->displayLimit('max_patients_per_month') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3 py-1.5 text-muted fw-semibold">2</td>
                                                <td class="py-1.5 text-dark fw-medium">
                                                    <i class="bi bi-file-earmark-medical me-1.5 text-primary"></i> Prescriptions (Rx) / Month
                                                </td>
                                                <td class="pe-3 py-1.5 text-end fw-bold text-dark">
                                                    {{ $plan->displayLimit('max_prescriptions_per_month') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3 py-1.5 text-muted fw-semibold">3</td>
                                                <td class="py-1.5 text-dark fw-medium">
                                                    <i class="bi bi-person-badge me-1.5 text-primary"></i> Staff Accounts
                                                </td>
                                                <td class="pe-3 py-1.5 text-end fw-bold text-dark">
                                                    {{ $plan->displayLimit('max_staff') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3 py-1.5 text-muted fw-semibold">4</td>
                                                <td class="py-1.5 text-dark fw-medium">
                                                    <i class="bi bi-hospital me-1.5 text-primary"></i> Doctors Cap
                                                </td>
                                                <td class="pe-3 py-1.5 text-end fw-bold text-dark">
                                                    {{ $plan->displayLimit('max_doctors') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Included Features Section (Below Quota Limits) -->
                            <div class="mb-3 flex-grow-1">
                                <div class="fs-11 fw-bold text-uppercase text-muted mb-2 tracking-wide">
                                    <i class="bi bi-shield-check text-success me-1"></i> Included Features
                                </div>
                                @php
                                    $featureList = $plan->features ?? [];
                                @endphp
                                <div class="table-responsive rounded-2 border border-light">
                                    <table class="table table-sm table-hover align-middle mb-0 fs-12">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" style="width: 45px;" class="ps-3 py-1.5 fw-semibold text-muted">#</th>
                                                <th scope="col" class="py-1.5 fw-semibold text-muted">Feature Name</th>
                                                <th scope="col" style="width: 60px;" class="pe-3 py-1.5 text-end fw-semibold text-muted">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($featureList as $code)
                                                <tr>
                                                    <td class="ps-3 py-1.5 text-muted fw-semibold">{{ $loop->iteration }}</td>
                                                    <td class="py-1.5 text-dark fw-medium">{{ str_replace('_', ' ', ucfirst($code)) }}</td>
                                                    <td class="pe-3 py-1.5 text-end">
                                                        <i class="bi bi-check-circle-fill text-success fs-13" title="Included"></i>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-2 text-muted fst-italic">No features enabled</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto gap-2">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fs-12 fw-semibold">
                                    <i class="bi bi-hospital me-1"></i> {{ $plan->subscriptions_count }} Subscribed Clinics
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <form method="POST" action="{{ route('admin.subscriptions.plans.toggle-status', $plan) }}" class="m-0">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm {{ $plan->isActive() ? 'btn-outline-success' : 'btn-outline-secondary' }} rounded-2 px-2.5 py-1 d-inline-flex align-items-center gap-2 plan-toggle-btn" 
                                                title="Click to {{ $plan->isActive() ? 'Deactivate' : 'Activate' }} Plan">
                                            <i class="bi {{ $plan->isActive() ? 'bi-toggle-on fs-6' : 'bi-toggle-off fs-6' }} me-1"></i>
                                            <span class="fs-12 fw-medium">{{ $plan->isActive() ? 'Active' : 'Inactive' }}</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}" 
                                       class="btn btn-sm btn-primary rounded-2 px-3 py-1 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" 
                                       title="Edit Plan">
                                        <i class="bi bi-pencil-square me-1.5"></i>
                                        <span>Edit Plan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm border-0 text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-box2 fs-1 d-block mb-2 text-muted opacity-50"></i>
                            <h5 class="fw-bold text-dark">No Subscription Plans Found</h5>
                            <p class="fs-13 mb-3">Get started by creating your first subscription tier.</p>
                            <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Create Plan
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

<style>
    .plan-toggle-btn {
        transition: all 0.2s ease-in-out;
    }
    .plan-toggle-btn i {
        color: inherit !important;
        transition: color 0.15s ease-in-out;
    }
    .plan-toggle-btn:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        color: #fff !important;
    }
    .plan-toggle-btn:hover i {
        color: #fff !important;
    }
    .btn-outline-success.plan-toggle-btn:hover {
        background-color: #198754 !important;
        border-color: #198754 !important;
    }
    .btn-outline-secondary.plan-toggle-btn:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
</style>
@endsection
