@extends("frontend.include.layout")

@section('title', 'Edit Patient Details - Doctor Portal')

@section('content')
<style>
    .valex-edit-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .valex-edit-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f4f9;
        background: #fbfcfe;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .valex-edit-body {
        padding: 24px;
    }
    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-label-valex {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748b;
        margin-bottom: 6px;
    }
    .input-valex {
        height: 42px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .input-valex:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12);
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    Edit Patient Information
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Patient Management</span> &bull; Update personal demographics, guardian, contact &amp; address details
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('symptoms') }}" class="btn btn-valex-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Queue
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Edit Form Card -->
        <div class="valex-edit-card shadow-sm">
            <div class="valex-edit-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-transparent text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Edit Patient Details</h5>
                        <span class="text-muted fs-12">Patient Registration: <strong class="text-primary font-monospace">{{ $patient->registration ?? $patient->patient_id }}</strong></span>
                    </div>
                </div>
                <div>
                    <span class="badge bg-light text-muted border px-3 py-1.5 rounded-pill fs-12">
                        <i class="bi bi-hash"></i> Database ID: #{{ $patient->id }}
                    </span>
                </div>
            </div>

            <div class="valex-edit-body">
                <form action="{{ route('patient.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- 1. PERSONAL INFORMATION -->
                    <div class="form-section-title">
                        <i class="bi bi-person-vcard text-primary"></i> 1. Personal &amp; Contact Details
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label class="form-label-valex">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="full_name" class="form-control input-valex" value="{{ old('full_name', $patient->patient_name) }}" placeholder="e.g. Ramesh Kumar" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
                            </div>
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-md-6">
                            <label class="form-label-valex">Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone-fill"></i></span>
                                <input type="tel" name="mobile" class="form-control input-valex" value="{{ old('mobile', $patient->mobile) }}" placeholder="10-digit mobile number" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-4">
                            <label class="form-label-valex">Gender <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="gender" class="form-select input-valex" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ strtolower(old('gender', $patient->gender)) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ strtolower(old('gender', $patient->gender)) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ strtolower(old('gender', $patient->gender)) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        {{-- Payment Category --}}
                        <div class="col-md-4">
                            <label class="form-label-valex">Payment Category</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><i class="bi bi-wallet2"></i></span>
                                <select name="payment_category_id" class="form-select input-valex">
                                    <option value="">-- Select Category --</option>
                                    @if(isset($paymentCategories))
                                        @foreach($paymentCategories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('payment_category_id', $patient->payment_category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} {{ $cat->price ? '(₹' . number_format($cat->price, 2) . ')' : '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-md-4">
                            <label class="form-label-valex">Date of Birth <span class="text-muted fw-normal fs-11">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="dob" class="form-control input-valex" value="{{ old('dob', $patient->dob) }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        {{-- Aadhaar Number --}}
                        <div class="col-md-4">
                            <label class="form-label-valex">Aadhaar Number <span class="text-muted fw-normal fs-11">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-card-text"></i></span>
                                <input type="text" name="aadhar" class="form-control input-valex" value="{{ old('aadhar', $patient->aaddhar_num) }}" placeholder="12-digit Aadhaar" maxlength="12">
                            </div>
                        </div>
                    </div>

                    <!-- 2. GUARDIAN & AGE DETAILS -->
                    <div class="form-section-title">
                        <i class="bi bi-people text-info"></i> 2. Guardian &amp; Age Details
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Guardian Type --}}
                        <div class="col-md-4">
                            <label class="form-label-valex">Guardian Relation</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person-badge"></i></span>
                                <select name="guardian_type" class="form-select input-valex">
                                    <option value="father" {{ strtolower(old('guardian_type', $patient->guardian_type)) == 'father' ? 'selected' : '' }}>Father</option>
                                    <option value="husband" {{ strtolower(old('guardian_type', $patient->guardian_type)) == 'husband' ? 'selected' : '' }}>Husband</option>
                                    <option value="mother" {{ strtolower(old('guardian_type', $patient->guardian_type)) == 'mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="guardian" {{ strtolower(old('guardian_type', $patient->guardian_type)) == 'guardian' ? 'selected' : '' }}>Other Guardian</option>
                                </select>
                            </div>
                        </div>

                        {{-- Guardian Name --}}
                        <div class="col-md-8">
                            <label class="form-label-valex">Guardian Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person-heart"></i></span>
                                <input type="text" name="guardian_name" class="form-control input-valex" value="{{ old('guardian_name', $patient->husband_father_name) }}" placeholder="Enter Guardian's Full Name" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                            </div>
                        </div>

                        {{-- Age (Years) --}}
                        <div class="col-md-6">
                            <label class="form-label-valex">Age (Years) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-hourglass-split"></i></span>
                                <input type="number" name="age_year" class="form-control input-valex" value="{{ old('age_year', $patient->age_year) }}" min="0" max="130" placeholder="e.g. 28" required>
                                <span class="input-group-text bg-light text-muted fs-12">Yrs</span>
                            </div>
                        </div>

                        {{-- Age (Months) --}}
                        <div class="col-md-6">
                            <label class="form-label-valex">Age (Months)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-hourglass-top"></i></span>
                                <input type="number" name="age_month" class="form-control input-valex" value="{{ old('age_month', $patient->age_month) }}" min="0" max="11" placeholder="e.g. 4">
                                <span class="input-group-text bg-light text-muted fs-12">Mos</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. RESIDENTIAL ADDRESS -->
                    <div class="form-section-title">
                        <i class="bi bi-geo-alt text-success"></i> 3. Residential Address
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label-valex">Complete Address <span class="text-muted fw-normal fs-11">(Optional)</span></label>
                            <textarea name="address" rows="3" class="form-control" style="border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13.5px;" placeholder="Enter complete residential address, city, state, pincode...">{{ old('address', $patient->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                        <a href="{{ route('symptoms') }}" class="btn btn-light px-4">
                            <i class="bi bi-arrow-left me-1"></i> Cancel &amp; Back
                        </a>
                        <button type="submit" class="btn btn-valex-primary px-4 py-2 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Update Patient Information
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action^="{{ url('/patient.update') }}"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;
            let errorMsgs = [];

            const nameInput = form.querySelector('input[name="full_name"]');
            if (nameInput) {
                const nameVal = nameInput.value.trim();
                if (!nameVal || /[0-9]/.test(nameVal)) {
                    isValid = false;
                    nameInput.classList.add('is-invalid');
                    errorMsgs.push('Full name must contain letters only (no numbers allowed).');
                } else {
                    nameInput.classList.remove('is-invalid');
                }
            }

            const mobileInput = form.querySelector('input[name="mobile"]');
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

            if (!isValid) {
                e.preventDefault();
                alert(errorMsgs.join('\n'));
            }
        });
    }
});
</script>
@endsection
