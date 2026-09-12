@extends('middleend.include.layout')
@section('title', 'Edit Doctor Details - Onboarding Portal')

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Edit Doctor Details
                    </h4>
                    <p class="text-muted small mb-0">Fill or update the clinical details for <strong>Dr. {{ $doctor->name }}</strong> (ID: {{ $doctor->Doctor_Emp_id ?? 'DOC-'.$doctor->id }})</p>
                </div>
                <a href="{{ route('/manage.doctor') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Manage Doctors
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
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-light py-3 px-4 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark">Doctor Profile & Clinical Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('onboarding.doctor.update', $doctor->id) }}">
                            @csrf

                            <div class="row g-4">

                                <!-- 1. Doctor Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold fs-13">Doctor Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $doctor->name) }}"
                                               placeholder="e.g. Dr. John Doe"
                                               oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 2. Doctor Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold fs-13">Doctor Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $doctor->email) }}"
                                               placeholder="e.g. doctor@clinic.com"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 3. Select Clinic -->
                                <div class="col-md-6">
                                    <label for="clinic_id" class="form-label fw-semibold fs-13">Select Clinic</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-building text-muted"></i></span>
                                        <select class="form-select @error('clinic_id') is-invalid @enderror" id="clinic_id" name="clinic_id" onchange="autoFillEditClinicName(this)">
                                            <option value="">-- Select Registered Clinic --</option>
                                            @if(isset($clinics) && count($clinics) > 0)
                                                @foreach($clinics as $clinic)
                                                    <option value="{{ $clinic->id }}" data-name="{{ $clinic->name }}" {{ old('clinic_id', $doctor->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                                        {{ $clinic->name }} ({{ $clinic->clinic_id }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('clinic_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 3b. Clinic Name -->
                                <div class="col-md-6">
                                    <label for="clinic_name" class="form-label fw-semibold fs-13">Clinic / Hospital Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-hospital text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('clinic_name') is-invalid @enderror"
                                               id="clinic_name"
                                               name="clinic_name"
                                               value="{{ old('clinic_name', $doctor->clinic_name) }}"
                                               placeholder="e.g. Apex Health Clinic"
                                               required>
                                        @error('clinic_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 4. Phone Number -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold fs-13">Contact / Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-telephone text-muted"></i></span>
                                        <input type="tel"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone', $doctor->phone) }}"
                                               placeholder="e.g. 9876543210"
                                               maxlength="10"
                                               minlength="10"
                                               pattern="[0-9]{10}"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 5. Specialization -->
                                <div class="col-md-6">
                                    <label for="specialisation" class="form-label fw-semibold fs-13">Specialization</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-award text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('specialisation') is-invalid @enderror"
                                               id="specialisation"
                                               name="specialisation"
                                               value="{{ old('specialisation', $doctor->specialization) }}"
                                               placeholder="e.g. Cardiologist, Dermatologist, General Physician">
                                        @error('specialisation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 6. Experience -->
                                <div class="col-md-6">
                                    <label for="experience" class="form-label fw-semibold fs-13">Experience</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-briefcase text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('experience') is-invalid @enderror"
                                               id="experience"
                                               name="experience"
                                               value="{{ old('experience', $doctor->Experience) }}"
                                               placeholder="e.g. 5 Years / 10+ Years">
                                        @error('experience')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 7. Qualification -->
                                <div class="col-md-6">
                                    <label for="qualification" class="form-label fw-semibold fs-13">Doctor Qualification</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-mortarboard text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('qualification') is-invalid @enderror"
                                               id="qualification"
                                               name="qualification"
                                               value="{{ old('qualification', $doctor->qualification) }}"
                                               placeholder="e.g. MBBS, MD, MS, DNB">
                                        @error('qualification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 8. Clinic Address -->
                                <div class="col-md-6">
                                    <label for="clinic_address" class="form-label fw-semibold fs-13">Clinic Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                                        <input type="text"
                                               class="form-control @error('clinic_address') is-invalid @enderror"
                                               id="clinic_address"
                                               name="clinic_address"
                                               value="{{ old('clinic_address', $doctor->clinic_address) }}"
                                               placeholder="e.g. 123 Health Ave, Suite 400, New Delhi">
                                        @error('clinic_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <a href="{{ route('/manage.doctor') }}" class="btn btn-light px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                        <i class="bi bi-check2-circle me-1"></i> Update
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<script>
    function autoFillEditClinicName(select) {
        const selectedOpt = select.options[select.selectedIndex];
        const clinicName = selectedOpt.getAttribute('data-name');
        if (clinicName) {
            document.getElementById('clinic_name').value = clinicName;
        }
    }
</script>
@endsection
