@extends('backend.include.layout')

@section('title', 'Patient Registration - Receptionist Desk')

@section('content')
<style>
    .payment-category-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .valex-select-highlight:focus {
        border-color: #0162e8 !important;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.15) !important;
    }
    .doctor-context-pill {
        background: #f0f7ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Breadcrumb Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    New Patient Registration Desk
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Receptionist Portal</span> &bull; Enter patient demographic and choose payment category
                </p>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2 flex-grow-1">
                @if(isset($doctor) && $doctor)
                    <div class="doctor-context-pill fs-12 fw-semibold">
                        <i class="bi bi-hospital text-primary fs-14"></i>
                        <span>Doctor: <strong>Dr. {{ $doctor->name }}</strong> ({{ $doctor->specialization ?? 'Specialist' }})</span>
                    </div>
                @endif
                <a href="{{ route('receptionist.dashboard') }}" class="btn btn-valex-light btn-sm ms-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard Queue
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Registration Card -->
        <div class="row">
            <div class="col-12">

                <div class="valex-card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                    <div class="valex-card-header d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-vcard fs-5 text-primary"></i>
                            <h5 class="valex-card-title mb-0 fw-bold fs-15">Patient Information Form</h5>
                        </div>
                        <span class="badge bg-primary-transparent text-primary py-1 px-3 rounded-pill fs-12">
                            <i class="bi bi-asterisk text-danger me-1"></i> Required Fields
                        </span>
                    </div>

                    <div class="valex-card-body p-4">
                        <form method="POST" action="{{ route('receptionist.patients.store') }}" id="patientRegForm">
                            @csrf

                            {{-- Doctor Selection if available / multiple --}}
                            @if(isset($doctor) && $doctor)
                                <input type="hidden" name="doctor_id" id="doctor_id" value="{{ $doctor->id }}">
                            @elseif(isset($doctors) && count($doctors) > 0)
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6 col-12">
                                        <label for="doctor_select" class="form-label fw-semibold fs-13 text-dark">
                                            Select Consulting Doctor <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-person-badge text-primary"></i></span>
                                            <select class="form-select valex-select-highlight" id="doctor_select" name="doctor_id" required>
                                                <option value="" disabled selected>-- Choose Doctor --</option>
                                                @foreach($doctors as $doc)
                                                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                                        Dr. {{ $doc->name }} ({{ $doc->specialization ?? 'General' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row g-4">

                                {{-- Full Name --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="name" class="form-label fw-semibold fs-13 text-dark">
                                        Patient Full Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Ramesh Kumar" value="{{ old('name') }}" oninput="this.value = this.value.replace(/[0-9]/g, '')" required minlength="2" maxlength="100">
                                    </div>
                                </div>

                                {{-- Payment Category (Added by Onboarding Member for Doctor) --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="payment_category_id" class="form-label fw-semibold fs-13 text-dark">
                                        Payment Category <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-primary"><i class="bi bi-wallet2"></i></span>
                                        <select class="form-select valex-select-highlight" id="payment_category_id" name="payment_category_id" required>
                                            <option value="" disabled {{ old('payment_category_id') ? '' : 'selected' }}>-- Select Payment Category --</option>
                                            @if(isset($paymentCategories) && count($paymentCategories) > 0)
                                                @foreach($paymentCategories as $pCat)
                                                    <option value="{{ $pCat->id }}" {{ old('payment_category_id') == $pCat->id ? 'selected' : '' }}>
                                                        {{ $pCat->name }} {{ $pCat->price ? '— ₹' . number_format($pCat->price, 2) : '' }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No active categories configured for this doctor</option>
                                            @endif
                                        </select>
                                    </div>
                                    <small class="text-muted fs-11 mt-1 d-block">
                                        <i class="bi bi-info-circle me-1"></i> Configured by onboarding team for this doctor
                                    </small>
                                    @if(isset($clinic) && $clinic && $clinic->has_revisit_rule)
                                        <small class="text-success fw-semibold fs-11 mt-0.5 d-block">
                                            <i class="bi bi-gift-fill me-1"></i> Clinic Revisit Policy: Free follow-up within {{ $clinic->revisit_validity_days ?? 7 }} days.
                                        </small>
                                    @endif
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="gender" class="form-label fw-semibold fs-13 text-dark">
                                        Gender <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-gender-ambiguous text-muted"></i></span>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Guardian Type --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="Guardian" class="form-label fw-semibold fs-13 text-dark">
                                        Guardian Type
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-people text-muted"></i></span>
                                        <select class="form-select" id="Guardian" name="guardian_type">
                                            <option value="">Select Guardian (Optional)</option>
                                            <option value="father" {{ old('guardian_type') == 'father' ? 'selected' : '' }}>Father</option>
                                            <option value="husband" {{ old('guardian_type') == 'husband' ? 'selected' : '' }}>Husband</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Guardian Name --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="guardian_name" class="form-label fw-semibold fs-13 text-dark">
                                        Guardian Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person-heart text-muted"></i></span>
                                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Enter guardian name" value="{{ old('guardian_name') }}" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                                    </div>
                                </div>

                                {{-- Mobile Number --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="mobile" class="form-label fw-semibold fs-13 text-dark">
                                        Mobile / Phone Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-telephone text-muted"></i></span>
                                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="10 digit mobile" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" value="{{ old('mobile') }}">
                                    </div>
                                </div>

                                {{-- Age Year --}}
                                <div class="col-md-2 col-sm-3 col-6">
                                    <label for="age_year" class="form-label fw-semibold fs-13 text-dark">
                                        Age (Years)
                                    </label>
                                    <input type="number" class="form-control" id="age_year" name="age_year" placeholder="Years" min="0" max="120" value="{{ old('age_year') }}">
                                </div>

                                {{-- Age Month --}}
                                <div class="col-md-2 col-sm-3 col-6">
                                    <label for="age_month" class="form-label fw-semibold fs-13 text-dark">
                                        Age (Months)
                                    </label>
                                    <input type="number" class="form-control" id="age_month" name="age_month" placeholder="Months" min="0" max="11" value="{{ old('age_month') }}">
                                </div>

                                {{-- Aadhaar Number --}}
                                <div class="col-md-4 col-sm-6 col-12">
                                    <label for="aadhaar" class="form-label fw-semibold fs-13 text-dark">
                                        Aadhaar / ID Card Number <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                                        <input type="text" class="form-control" id="aadhaar" name="aadhaar" placeholder="12 digit Aadhaar number" maxlength="12" value="{{ old('aadhaar') }}" required>
                                    </div>
                                </div>

                                {{-- Full Address --}}
                                <div class="col-md-8 col-12">
                                    <label for="address" class="form-label fw-semibold fs-13 text-dark">
                                        Full Residential Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Street, City, Landmark, Pin code" required>{{ old('address') }}</textarea>
                                </div>

                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('receptionist.dashboard') }}" class="btn btn-light px-4">
                                    Cancel
                                </a>
                                <button type="reset" class="btn btn-light px-4">
                                    Reset Form
                                </button>
                                <button type="submit" class="btn btn-valex-primary px-4 fw-semibold shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Register Patient
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const regForm = document.getElementById('patientRegForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            let isValid = true;
            let errorMsgs = [];

            const nameInput = document.getElementById('name');
            if (nameInput) {
                const nameVal = nameInput.value.trim();
                if (!nameVal || /[0-9]/.test(nameVal)) {
                    isValid = false;
                    nameInput.classList.add('is-invalid');
                    errorMsgs.push('Patient full name must contain letters only (no numbers allowed).');
                } else {
                    nameInput.classList.remove('is-invalid');
                }
            }

            const mobileInput = document.getElementById('mobile');
            if (mobileInput && mobileInput.value.trim() !== '') {
                const mobileVal = mobileInput.value.trim();
                if (!/^[0-9]{10}$/.test(mobileVal)) {
                    isValid = false;
                    mobileInput.classList.add('is-invalid');
                    errorMsgs.push('Mobile number must be exactly 10 digits.');
                } else {
                    mobileInput.classList.remove('is-invalid');
                }
            }

            const aadhaarInput = document.getElementById('aadhaar');
            if (aadhaarInput) {
                const aadhaarVal = aadhaarInput.value.trim();
                if (!/^[0-9]{12}$/.test(aadhaarVal)) {
                    isValid = false;
                    aadhaarInput.classList.add('is-invalid');
                    errorMsgs.push('Aadhaar number must be exactly 12 digits.');
                } else {
                    aadhaarInput.classList.remove('is-invalid');
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMsgs.join('\n'));
            }
        });
    }

    const doctorSelect = document.getElementById('doctor_select');
    const categorySelect = document.getElementById('payment_category_id');

    if (doctorSelect && categorySelect) {
        doctorSelect.addEventListener('change', function () {
            const docId = this.value;
            if (!docId) return;

            categorySelect.disabled = true;
            categorySelect.innerHTML = '<option value="" disabled selected>Loading payment categories...</option>';

            fetch(`/receptionist/payment-categories/${docId}`)
                .then(res => res.json())
                .then(data => {
                    categorySelect.innerHTML = '<option value="" disabled selected>-- Select Payment Category --</option>';
                    if (data.success && data.categories.length > 0) {
                        data.categories.forEach(cat => {
                            const opt = document.createElement('option');
                            opt.value = cat.id;
                            const priceStr = cat.price ? ` — ₹${parseFloat(cat.price).toFixed(2)}` : '';
                            opt.textContent = `${cat.name}${priceStr}`;
                            categorySelect.appendChild(opt);
                        });
                    } else {
                        categorySelect.innerHTML = '<option value="" disabled selected>No active categories configured for this doctor</option>';
                    }
                })
                .catch(err => {
                    console.error('Error fetching payment categories:', err);
                    categorySelect.innerHTML = '<option value="" disabled selected>Error loading categories</option>';
                })
                .finally(() => {
                    categorySelect.disabled = false;
                });
        });
    }
});
</script>
@endsection
