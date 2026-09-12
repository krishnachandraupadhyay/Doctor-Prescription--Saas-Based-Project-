@extends('backend.include.layout')

@section('title', 'Manage Practice Subscribers - Admin Portal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Subscribers Directory & Active Subscriptions
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Active Tenants</span> &bull; Manage subscriptions, extend validity, and allocate plans to Clinics & Hospitals
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-layers me-1"></i> Subscription Plans
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignSubscriptionModal">
                    <i class="bi bi-plus-circle-fill me-1"></i> Assign New Subscription
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

        <!-- KPI Statistic Row (1 line: 4 per row on desktop) -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Clinics</span>
                            <h3 class="mb-0 fw-bold mt-1 text-primary">{{ $totalClinics }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-hospital fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Active Subscribed Clinics</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $activeClinicsCount }}</h3>
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
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Total Enrolled Doctors</span>
                            <h3 class="mb-0 fw-bold mt-1 text-info">{{ $totalDoctors }}</h3>
                        </div>
                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Expiring in 7 Days</span>
                            <h3 class="mb-0 fw-bold mt-1 {{ $expiringSoon > 0 ? 'text-danger' : 'text-muted' }}">{{ $expiringSoon }}</h3>
                        </div>
                        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Form -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body py-3 px-4">
                <form method="GET" action="{{ route('admin.subscriptions.subscribers.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <select name="plan_id" class="form-select">
                            <option value="">All Subscription Plans</option>
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->formattedPrice() }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial Period</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                        @if(request()->anyFilled(['status', 'plan_id']))
                            <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Subscribers Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 valex-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 fs-12 fw-semibold text-muted text-uppercase">Clinic/Hospital</th>
                                <th class="fs-12 fw-semibold text-muted text-uppercase">Current Plan</th>
                                <th class="fs-12 fw-semibold text-muted text-uppercase">Validity Period</th>
                                <th class="fs-12 fw-semibold text-muted text-center text-uppercase">Days Left</th>
                                <th class="fs-12 fw-semibold text-muted text-center text-uppercase">Status</th>
                                <th class="pe-3 fs-12 fw-semibold text-muted text-end text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                                @php
                                    $model = $sub->subscriber_model;
                                    $name = $model ? $model->name : 'Clinic Not Found';
                                    $subId = $model ? ($model->clinic_id ?? 'CLN-' . $model->id) : '#' . $sub->subscriber_id;
                                    $daysRemaining = $sub->daysRemaining();
                                @endphp
                                <tr>
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold fs-12 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i class="bi bi-hospital fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fs-13 fw-bold text-dark">{{ $name }}</h6>
                                                <small class="text-muted font-monospace fs-11">ID: {{ $subId }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-13 text-dark">{{ $sub->plan->name ?? 'Plan Removed' }}</div>
                                        <small class="text-muted fs-11">{{ $sub->plan ? $sub->plan->formattedPrice() . ' / ' . $sub->plan->billing_cycle->label() : '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fs-12 text-dark fw-medium">
                                            <i class="bi bi-calendar-event me-1 text-muted"></i>{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                                        </div>
                                        @if($sub->payment_reference)
                                            <small class="text-muted fs-10 font-monospace">Ref: {{ $sub->payment_reference }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($sub->isExpired())
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 fs-11 fw-bold">
                                                Expired
                                            </span>
                                        @elseif($daysRemaining <= 7)
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1 fs-11 fw-bold">
                                                {{ $daysRemaining }} Days Left
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 fs-11 fw-bold">
                                                {{ $daysRemaining }} Days Left
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $sub->effectiveBadgeClasses() }} rounded-pill px-2.5 py-1 fs-11">
                                            {{ $sub->effectiveStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Extend Dropdown -->
                                            <button type="button" class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Extend Subscription">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-12">
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.extend', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="days" value="30">
                                                        <button type="submit" class="dropdown-item py-1.5"><i class="bi bi-plus-circle me-1 text-primary"></i> +30 Days (1 Month)</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.extend', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="days" value="90">
                                                        <button type="submit" class="dropdown-item py-1.5"><i class="bi bi-plus-circle me-1 text-primary"></i> +90 Days (Quarter)</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.extend', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="days" value="365">
                                                        <button type="submit" class="dropdown-item py-1.5"><i class="bi bi-plus-circle me-1 text-primary"></i> +365 Days (1 Year)</button>
                                                    </form>
                                                </li>
                                            </ul>

                                            <!-- Status Change Dropdown -->
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Change Status">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-12">
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.status', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit" class="dropdown-item text-success py-1.5"><i class="bi bi-check-circle me-1"></i> Set Active</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.status', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="suspended">
                                                        <button type="submit" class="dropdown-item text-warning py-1.5"><i class="bi bi-pause-circle me-1"></i> Suspend Access</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.subscriptions.subscribers.status', $sub) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item text-danger py-1.5"><i class="bi bi-x-circle me-1"></i> Cancel Plan</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-hospital fs-1 d-block mb-2 text-muted opacity-50"></i>
                                        No clinic subscriptions found. Click "Assign New Subscription" above to enroll a Clinic.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($subscriptions->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Modal: Assign Subscription -->
<div class="modal fade" id="assignSubscriptionModal" tabindex="-1" aria-labelledby="assignSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="assignSubscriptionModalLabel">
                    <i class="bi bi-credit-card-2-front-fill me-2"></i> Assign Clinic Subscription
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.subscriptions.subscribers.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13">Select Clinic <span class="text-danger">*</span></label>
                            <select name="subscriber_id" id="clinic_select" class="form-select" required>
                                <option value="">-- Choose Clinic --</option>
                                @foreach($clinics as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->name }} (Reg: {{ $cl->clinic_id ?? 'CLN-' . $cl->id }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13">Subscription Plan <span class="text-danger">*</span></label>
                            <select name="subscription_plan_id" id="plan_select" class="form-select" required>
                                <option value="">-- Select Subscription Tier --</option>
                                @foreach($plans as $pl)
                                    <option value="{{ $pl->id }}" data-cycle="{{ $pl->billing_cycle->value }}" data-price="{{ $pl->price }}">
                                        {{ $pl->name }} &bull; {{ $pl->formattedPrice() }} / {{ $pl->billing_cycle->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date_input" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date_input" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active (Paid)</option>
                                <option value="trial">Trial Period</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Payment Reference / Transaction ID</label>
                            <input type="text" name="payment_reference" class="form-control" placeholder="e.g. TXN-998242 or CASH">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Internal Admin Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="e.g. Granted by Super Admin on clinic onboard">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Assign Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_select');
    const startDateInput = document.getElementById('start_date_input');
    const endDateInput = document.getElementById('end_date_input');

    if (planSelect && startDateInput && endDateInput) {
        planSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const cycle = selected.getAttribute('data-cycle');
            const start = new Date(startDateInput.value || new Date());

            let days = 30;
            if (cycle === 'quarterly') days = 90;
            if (cycle === 'yearly') days = 365;
            if (cycle === 'lifetime') days = 3650;

            start.setDate(start.getDate() + days);
            endDateInput.value = start.toISOString().split('T')[0];
        });
    }
});
</script>
@endsection
