@extends('clinic.include.layout')
@section('title', 'Patient Revisit & Follow-up Fee Rule - Clinic Portal')

@section('content')
<style>
    .valex-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #EEF3F1;
        box-shadow: 0 4px 20px -4px rgba(15, 59, 56, 0.08);
        overflow: hidden;
    }
    .revisit-pill-btn {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .revisit-pill-btn:hover, .revisit-pill-btn.active {
        background: #0162e8;
        border-color: #0162e8;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(1, 98, 232, 0.3);
    }
    .fee-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fafafa;
    }
    .fee-option-card:hover {
        border-color: #93c5fd;
        background: #f0f7ff;
    }
    .fee-option-card.selected {
        border-color: #0162e8;
        background: #f0f7ff;
        box-shadow: 0 4px 14px rgba(1, 98, 232, 0.12);
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
                        <div class="rounded-4 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; font-size: 26px;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0 fs-18">Patient Revisit &amp; Follow-up Fee Rule</h4>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill fs-11 fw-semibold">
                                    {{ $clinic->name }}
                                </span>
                            </div>
                            <p class="text-muted fs-13 mb-0">
                                Set clinic policy for returning patients: choose whether follow-up visits within a certain number of days are free or charged.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Current Status Badge --}}
                    <div class="d-flex align-items-center gap-2">
                        @if($clinic->has_revisit_rule)
                            <div class="px-3 py-2 bg-success-subtle border border-success-subtle rounded-3 text-success fs-13 fw-semibold d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Policy: <strong>Free within {{ $clinic->revisit_validity_days ?? 7 }} Days</strong></span>
                            </div>
                        @else
                            <div class="px-3 py-2 bg-light border rounded-3 text-muted fs-13 fw-semibold d-flex align-items-center gap-2">
                                <i class="bi bi-shield-x"></i>
                                <span>Policy: <strong>Standard Fees (No Special Rule)</strong></span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Configuration Card --}}
        <div class="valex-card mb-4">
            <div class="p-3 p-md-4 border-bottom bg-light bg-opacity-50">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary fs-18"></i>
                        <h5 class="fw-bold text-dark mb-0 fs-16">
                            Configure Revisit Rule
                        </h5>
                    </div>
                    <div>
                        @if($clinic->has_revisit_rule)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Policy Active (Free Revisit within {{ $clinic->revisit_validity_days ?? 7 }} Days)
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                                <i class="bi bi-x-circle-fill me-1"></i> Policy Disabled (Always Charge Standard Fees)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('clinic.revisit_rule.update') }}" method="POST" id="revisitRuleForm">
                    @csrf

                    <div class="row g-4">
                        {{-- 1. Enable / Disable Toggle --}}
                        <div class="col-lg-5 col-12">
                            <label class="form-label fw-bold text-dark fs-13 mb-2">
                                Revisit / Follow-up Policy Status <span class="text-danger">*</span>
                            </label>

                            <div class="d-flex flex-column gap-3">
                                {{-- Option 1: Enable Revisit Rule --}}
                                <div class="fee-option-card {{ $clinic->has_revisit_rule ? 'selected' : '' }}" onclick="selectRevisitOption('1')">
                                    <div class="d-flex align-items-start gap-3">
                                        <input class="form-check-input mt-1 flex-shrink-0" type="radio" name="has_revisit_rule" id="rule_enabled" value="1" {{ $clinic->has_revisit_rule ? 'checked' : '' }}>
                                        <div>
                                            <label class="fw-bold text-dark fs-14 mb-0 d-block cursor-pointer" for="rule_enabled">
                                                ✅ Enable Revisit Rule
                                            </label>
                                            <p class="text-muted fs-12 mb-0 mt-1">
                                                Patients returning within validity days will receive follow-up benefits (e.g. Free consultation / ₹0 fee).
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Option 2: Disable Revisit Rule --}}
                                <div class="fee-option-card {{ !$clinic->has_revisit_rule ? 'selected' : '' }}" onclick="selectRevisitOption('0')">
                                    <div class="d-flex align-items-start gap-3">
                                        <input class="form-check-input mt-1 flex-shrink-0" type="radio" name="has_revisit_rule" id="rule_disabled" value="0" {{ !$clinic->has_revisit_rule ? 'checked' : '' }}>
                                        <div>
                                            <label class="fw-bold text-dark fs-14 mb-0 d-block cursor-pointer" for="rule_disabled">
                                                ❌ Disable Revisit Rule
                                            </label>
                                            <p class="text-muted fs-12 mb-0 mt-1">
                                                Standard consultation fees will always apply to every visit, regardless of previous visit dates.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Rule Configuration Details --}}
                        <div class="col-lg-7 col-12 border-start-lg ps-lg-4" id="revisitDetailsContainer">
                            <div class="p-4 bg-light rounded-4 border mb-4">
                                <h6 class="fw-bold text-dark fs-14 mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-range text-primary"></i>
                                    <span>Validity Window &amp; Fee Policy</span>
                                </h6>

                                {{-- Validity Days Input --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark fs-13 mb-1">
                                        Revisit Validity Window (Days) <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 3 ? 'active' : '' }}" onclick="setDays(3)">3 Days</button>
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 5 ? 'active' : '' }}" onclick="setDays(5)">5 Days</button>
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 7 ? 'active' : '' }}" onclick="setDays(7)">7 Days (Standard)</button>
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 10 ? 'active' : '' }}" onclick="setDays(10)">10 Days</button>
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 15 ? 'active' : '' }}" onclick="setDays(15)">15 Days</button>
                                        <button type="button" class="revisit-pill-btn {{ ($clinic->revisit_validity_days ?? 7) == 30 ? 'active' : '' }}" onclick="setDays(30)">30 Days</button>
                                    </div>
                                    <div class="input-group" style="max-width: 280px;">
                                        <input type="number" name="revisit_validity_days" id="revisit_validity_days" class="form-control fw-bold fs-14" min="1" max="365" value="{{ old('revisit_validity_days', $clinic->revisit_validity_days ?? 7) }}" placeholder="e.g. 7" oninput="updatePreview()">
                                        <span class="input-group-text bg-white text-muted fw-semibold">Days</span>
                                    </div>
                                    <small class="text-muted fs-11 mt-1 d-block">
                                        If a patient returns within these many days from their previous visit date, the revisit rule triggers.
                                    </small>
                                </div>

                                {{-- Fee Mode --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark fs-13 mb-1">
                                        Fee on Revisit / Follow-up <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-sm-6 col-12">
                                            <div class="form-check p-3 bg-white border rounded-3 d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 mt-0" type="radio" name="revisit_fee_type" id="fee_free" value="free" {{ ($clinic->revisit_fee_type ?? 'free') === 'free' ? 'checked' : '' }} onchange="updatePreview()">
                                                <label class="form-check-label fw-semibold text-dark fs-13 cursor-pointer mb-0" for="fee_free">
                                                    <span class="badge bg-success-subtle text-success me-1">₹0 Fee</span> Free Revisit (Fee Waived)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="form-check p-3 bg-white border rounded-3 d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 mt-0" type="radio" name="revisit_fee_type" id="fee_paid" value="paid" {{ ($clinic->revisit_fee_type ?? 'free') === 'paid' ? 'checked' : '' }} onchange="updatePreview()">
                                                <label class="form-check-label fw-semibold text-dark fs-13 cursor-pointer mb-0" for="fee_paid">
                                                    <span class="badge bg-primary-subtle text-primary me-1">Standard</span> Charge Normal Fee
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Live Rule Summary --}}
                                <div class="p-3 bg-white border border-primary-subtle rounded-3" id="liveRuleSummary">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-info-circle-fill text-primary fs-5 mt-0.5"></i>
                                        <div class="fs-12 text-dark">
                                            <strong class="text-primary d-block mb-0.5">Rule Summary Preview:</strong>
                                            <span id="previewText">
                                                When a patient returns within <strong>7 days</strong>, their consultation will be <strong>Free (₹0 fee)</strong>. If they visit after 7 days, standard consultation fee will be charged.
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> Save Revisit Policy
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function selectRevisitOption(val) {
    if (val === '1') {
        document.getElementById('rule_enabled').checked = true;
    } else {
        document.getElementById('rule_disabled').checked = true;
    }
    document.querySelectorAll('.fee-option-card').forEach(card => card.classList.remove('selected'));
    if (val === '1') {
        document.querySelector('input#rule_enabled').closest('.fee-option-card').classList.add('selected');
        document.getElementById('revisitDetailsContainer').style.opacity = '1';
        document.getElementById('revisitDetailsContainer').style.pointerEvents = 'auto';
    } else {
        document.querySelector('input#rule_disabled').closest('.fee-option-card').classList.add('selected');
        document.getElementById('revisitDetailsContainer').style.opacity = '0.55';
        document.getElementById('revisitDetailsContainer').style.pointerEvents = 'none';
    }
    updatePreview();
}

function setDays(days) {
    document.getElementById('revisit_validity_days').value = days;
    document.querySelectorAll('.revisit-pill-btn').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.startsWith(days + ' '));
    });
    updatePreview();
}

function updatePreview() {
    const isEnabled = document.getElementById('rule_enabled').checked;
    const days = document.getElementById('revisit_validity_days').value || 7;
    const isFree = document.getElementById('fee_free').checked;
    const previewEl = document.getElementById('previewText');

    if (!isEnabled) {
        previewEl.innerHTML = '<span class="text-danger fw-bold">Revisit Rule Disabled:</span> Patients will always be charged standard consultation fees on every visit.';
        return;
    }

    if (isFree) {
        previewEl.innerHTML = `If an existing patient returns within <strong>${days} days</strong> of their previous visit, their consultation will be marked as <strong class="text-success">FREE (₹0.00 fee waived)</strong> at registration and doctor desk. If they visit after <strong>${days} days</strong>, standard fees will apply.`;
    } else {
        previewEl.innerHTML = `If an existing patient returns within <strong>${days} days</strong>, standard / configured fees will apply as per clinic policy.`;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const isEnabled = document.getElementById('rule_enabled').checked;
    selectRevisitOption(isEnabled ? '1' : '0');
    updatePreview();
});
</script>
@endsection
