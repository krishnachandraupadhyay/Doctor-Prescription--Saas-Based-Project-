@extends("frontend.include.layout")
@section('title', 'Add New Patient - Doctor Portal')

@section('content')
<style>
    .valex-patient-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9edf4;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .valex-patient-header {
        padding: 20px 26px;
        border-bottom: 1px solid #f1f4f9;
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .valex-patient-body {
        padding: 28px;
    }

    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 18px;
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
        color: #475569;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .input-group-text-valex {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 14px;
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
    }

    .input-valex {
        height: 44px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .input-valex:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12);
        outline: none;
    }

    .textarea-valex {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #1e293b;
        padding: 12px 14px;
        transition: all 0.2s ease;
        resize: vertical;
        min-height: 80px;
    }

    .textarea-valex:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 3px rgba(1, 98, 232, 0.12);
        outline: none;
    }

    .btn-valex-primary {
        background: #0162e8 !important;
        border: 1px solid #0162e8 !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        box-shadow: 0 4px 12px rgba(1, 98, 232, 0.25) !important;
        text-decoration: none !important;
    }

    .btn-valex-primary:hover {
        background: #0150bf !important;
        border-color: #0150bf !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(1, 98, 232, 0.35) !important;
    }

    .btn-valex-light {
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 18px !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
    }

    .btn-valex-light:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
    }
</style>

<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>New Patient Registration
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">OPD Intake</span> &bull; Enter patient personal demographics, guardian information &amp; contact details
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('symptoms') }}" class="btn-valex-light">
                    <i class="bi bi-arrow-left"></i> Back to Patient Queue
                </a>
            </div>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-18"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                    <i class="bi bi-exclamation-triangle-fill fs-18"></i> Please correct the following errors:
                </div>
                <ul class="mb-0 ps-4 fs-13">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="valex-patient-card">
            
            {{-- Form Header --}}
            <div class="valex-patient-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-size: 20px;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark fs-16">Patient Intake &amp; Demographics Form</h5>
                        <span class="text-muted fs-12">All fields marked with <span class="text-danger fw-bold">*</span> are mandatory for OPD registration</span>
                    </div>
                </div>
                <div>
                    <span class="badge bg-primary-transparent text-primary px-3 py-1.5 rounded-pill fs-12 fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i> Today: {{ date('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="valex-patient-body">
                <form action="{{ route('Addpatient.store') }}" method="POST">
                    @csrf

                    <!-- ================= SECTION 1: PERSONAL DETAILS ================= -->
                    <div class="form-section-title">
                        <i class="bi bi-person-vcard text-primary"></i> 1. Personal &amp; Contact Details
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Full Name --}}
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Full Name <span class="text-danger">*</span></span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-person-fill text-primary"></i></span>
                                <input type="text" name="full_name" class="form-control input-valex" value="{{ old('full_name') }}" placeholder="Enter patient's full name (e.g. Rahul Sharma)" oninput="this.value = this.value.replace(/[0-9]/g, '')" required autofocus>
                            </div>
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-lg-6 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Mobile Number</span>
                                <small class="text-muted text-lowercase fw-normal fs-11">10 digits</small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-telephone-fill text-info"></i></span>
                                <input type="tel" name="mobile" class="form-control input-valex" value="{{ old('mobile') }}" placeholder="e.g. 9876543210" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Gender <span class="text-danger">*</span></span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-gender-ambiguous text-danger"></i></span>
                                <select name="gender" class="form-select input-valex" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male (पुरुष)</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female (महिला)</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other (अन्य)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Payment Category --}}
                        <div class="col-lg-4 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Payment / Billing Category</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-wallet2 text-success"></i></span>
                                <select name="payment_category_id" class="form-select input-valex">
                                    <option value="">-- General / Direct OPD --</option>
                                    @if(isset($paymentCategories))
                                        @foreach($paymentCategories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('payment_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} {{ $cat->price ? '(₹' . number_format($cat->price, 2) . ')' : '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Aadhaar Number --}}
                        <div class="col-lg-4 col-md-12 col-12">
                            <label class="form-label-valex">
                                <span>Aadhaar Card Number <span class="text-danger">*</span></span>
                                <small class="text-muted text-lowercase fw-normal fs-11">12 digits</small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-card-text text-purple" style="color: #7928ca;"></i></span>
                                <input type="text" name="aadhar" class="form-control input-valex" value="{{ old('aadhar') }}" placeholder="12-digit Aadhaar (e.g. 987654321032)" maxlength="12" required>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 2: GUARDIAN & AGE DETAILS ================= -->
                    <div class="form-section-title">
                        <i class="bi bi-people text-info"></i> 2. Guardian &amp; Age Details
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Guardian Type --}}
                        <div class="col-lg-3 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Guardian Relation</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-person-badge text-muted"></i></span>
                                <select name="guardian_type" class="form-select input-valex">
                                    <option value="father" {{ old('guardian_type', 'father') == 'father' ? 'selected' : '' }}>Father (पिता)</option>
                                    <option value="husband" {{ old('guardian_type') == 'husband' ? 'selected' : '' }}>Husband (पति)</option>
                                    <option value="mother" {{ old('guardian_type') == 'mother' ? 'selected' : '' }}>Mother (माता)</option>
                                    <option value="guardian" {{ old('guardian_type') == 'guardian' ? 'selected' : '' }}>Other Guardian (संरक्षक)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Guardian Name --}}
                        <div class="col-lg-5 col-md-6 col-12">
                            <label class="form-label-valex">
                                <span>Guardian Name</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-person-heart text-danger"></i></span>
                                <input type="text" name="guardian_name" class="form-control input-valex" value="{{ old('guardian_name') }}" placeholder="Enter guardian's full name" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                            </div>
                        </div>

                        {{-- Age (Years) --}}
                        <div class="col-lg-2 col-md-6 col-6">
                            <label class="form-label-valex">
                                <span>Age (Years)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-hourglass-split text-warning"></i></span>
                                <input type="number" name="age_year" class="form-control input-valex" value="{{ old('age_year') }}" min="0" max="120" placeholder="e.g. 32">
                                <span class="input-group-text bg-light text-muted fs-12 fw-semibold">Yrs</span>
                            </div>
                        </div>

                        {{-- Age (Months) --}}
                        <div class="col-lg-2 col-md-6 col-6">
                            <label class="form-label-valex">
                                <span>Age (Months)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex"><i class="bi bi-hourglass-top text-secondary"></i></span>
                                <input type="number" name="age_month" class="form-control input-valex" value="{{ old('age_month') }}" min="0" max="11" placeholder="e.g. 6">
                                <span class="input-group-text bg-light text-muted fs-12 fw-semibold">Mos</span>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 3: RESIDENTIAL ADDRESS ================= -->
                    <div class="form-section-title">
                        <i class="bi bi-geo-alt text-danger"></i> 3. Residential Address
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label-valex">
                                <span>Full Address &amp; Locality</span>
                                <small class="text-muted text-lowercase fw-normal fs-11">Village / City / Street / Landmark</small>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-valex align-items-start pt-2"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                <textarea name="address" rows="2" class="form-control textarea-valex" placeholder="Enter patient's residence address, village/town, district...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ================= FORM ACTIONS ================= -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top mt-4">
                        <button type="reset" class="btn btn-valex-light">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Form
                        </button>

                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('symptoms') }}" class="btn btn-valex-light">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-valex-primary px-4 py-2">
                                <i class="bi bi-check2-circle me-1.5 fs-15"></i> Save &amp; Register Patient
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('Addpatient.store') }}"]');
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

            const aadharInput = form.querySelector('input[name="aadhar"]');
            if (aadharInput) {
                const aadharVal = aadharInput.value.trim();
                if (!/^[0-9]{12}$/.test(aadharVal)) {
                    isValid = false;
                    aadharInput.classList.add('is-invalid');
                    errorMsgs.push('Aadhaar number must be exactly 12 digits.');
                } else {
                    aadharInput.classList.remove('is-invalid');
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