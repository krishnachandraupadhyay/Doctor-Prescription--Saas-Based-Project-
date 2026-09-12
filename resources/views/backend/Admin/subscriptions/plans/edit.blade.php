@extends('backend.include.layout')

@section('title', 'Edit Subscription Plan - Admin Portal')

@section('content')
<style>
    .feature-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .feature-item-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .feature-item-card:has(.feature-checkbox:checked) {
        background: #f0f7ff;
        border-color: #93c5fd;
    }
    .feature-item-card .feature-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        cursor: pointer;
        margin: 2px 0 0 0;
        flex-shrink: 0;
    }
    .feature-item-card .feature-checkbox:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }
</style>
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Edit Plan: {{ $plan->name }}
                </h4>
                <p class="text-muted fs-13 mb-0">
                    Update pricing, active quotas, and feature allocations for this tier
                </p>
            </div>
            <div>
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Plans
                </a>
            </div>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please correct the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.subscriptions.plans.update', $plan) }}">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left Column: Plan Details & Limits -->
                <div class="col-xl-7">
                    <!-- Basic Information Card -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i> Plan Details & Pricing
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-semibold fs-13">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $plan->name) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold fs-13">Price (₹ INR) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">₹</span>
                                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $plan->price) }}" required>
                                    </div>
                                    <small class="text-muted fs-11">Enter 0 for free/trial plans.</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-13">Billing Cycle <span class="text-danger">*</span></label>
                                    <select name="billing_cycle" class="form-select @error('billing_cycle') is-invalid @enderror" required>
                                        @php $currentCycle = old('billing_cycle', $plan->billing_cycle->value ?? 'monthly'); @endphp
                                        <option value="monthly" {{ $currentCycle === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ $currentCycle === 'quarterly' ? 'selected' : '' }}>Quarterly (3 Mo)</option>
                                        <option value="yearly" {{ $currentCycle === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="lifetime" {{ $currentCycle === 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-13">Trial Days <span class="text-danger">*</span></label>
                                    <input type="number" name="trial_days" class="form-control @error('trial_days') is-invalid @enderror" value="{{ old('trial_days', $plan->trial_days) }}" min="0" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-13">Status <span class="text-danger">*</span></label>
                                    @php $currentStatus = old('status', $plan->status->value ?? 'active'); @endphp
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-semibold fs-13">Short Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description', $plan->description) }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-13">Display Sorting</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $plan->sort_order) }}" min="0">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }}>
                                        <label class="form-check-label fs-12 fw-medium" for="is_popular">
                                            Highlight as "Popular"
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quota Limits Card -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                                <i class="bi bi-speedometer2 text-warning me-2"></i> Resource & Quota Limits
                            </h5>
                            <span class="badge bg-light text-muted border">Tip: Set -1 for Unlimited</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-13">Max Patients per Month</label>
                                    <input type="number" name="limits[max_patients_per_month]" class="form-control" value="{{ old('limits.max_patients_per_month', $plan->getLimit('max_patients_per_month', -1)) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-13">Max Prescriptions per Month</label>
                                    <input type="number" name="limits[max_prescriptions_per_month]" class="form-control" value="{{ old('limits.max_prescriptions_per_month', $plan->getLimit('max_prescriptions_per_month', -1)) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-13">Max Staff / Receptionists</label>
                                    <input type="number" name="limits[max_staff]" class="form-control" value="{{ old('limits.max_staff', $plan->getLimit('max_staff', 3)) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-13">Max Doctors Cap</label>
                                    <input type="number" name="limits[max_doctors]" class="form-control" value="{{ old('limits.max_doctors', $plan->getLimit('max_doctors', 1)) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Features Selection Checklist -->
                <div class="col-xl-5">
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                                <i class="bi bi-shield-check text-success me-2"></i> Enabled Features
                            </h5>
                            <div>
                                <button type="button" class="btn btn-link btn-sm text-primary p-0 text-decoration-none fw-semibold" id="selectAllFeatures">Select All</button>
                                <span class="text-muted mx-1">|</span>
                                <button type="button" class="btn btn-link btn-sm text-muted p-0 text-decoration-none" id="deselectAllFeatures">Clear</button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $planFeatures = old('features', $plan->features ?? []);
                            @endphp
                            @foreach($features as $category => $catFeatures)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-1.5 mb-2.5">
                                        <h6 class="fs-12 fw-bold text-uppercase text-primary mb-0">
                                            <i class="bi bi-tag-fill me-1 text-primary opacity-75"></i> {{ $category }} Features
                                        </h6>
                                        <span class="badge bg-light text-muted border fs-10 px-2 py-0.5 rounded-pill">{{ count($catFeatures) }} Available</span>
                                    </div>
                                    <div>
                                        @foreach($catFeatures as $feat)
                                            <label class="feature-item-card" for="feat_{{ $feat->code }}">
                                                <input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="{{ $feat->code }}" id="feat_{{ $feat->code }}"
                                                    {{ is_array($planFeatures) && in_array($feat->code, $planFeatures) ? 'checked' : '' }}>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold fs-13 text-dark mb-0.5">{{ $feat->name }}</div>
                                                    <div class="text-muted fs-12 lh-sm">{{ $feat->description }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-3 p-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this plan?')) document.getElementById('delete-plan-form').submit();">
                                <i class="bi bi-trash me-1"></i> Delete Plan
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i> Update Plan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="delete-plan-form" method="POST" action="{{ route('admin.subscriptions.plans.destroy', $plan) }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllFeatures');
    const deselectAllBtn = document.getElementById('deselectAllFeatures');
    const checkboxes = document.querySelectorAll('.feature-checkbox');

    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = true);
    });

    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
    });
});
</script>
@endsection
