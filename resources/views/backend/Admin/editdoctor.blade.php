@extends('backend.include.layout')
@section('title', 'Edit Doctor - Admin Console')

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
               <form method="POST" action="{{ route('doctor.update', $doctor->id) }}">
                    @csrf
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Doctor Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $doctor->name) }}"
                                       placeholder="Enter doctor name"
                                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Doctor Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $doctor->email) }}"
                                       placeholder="Enter doctor email"
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
                                <span class="input-group-text bg-light"><i class="bi bi-hospital"></i></span>
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
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <input type="text"
                                       class="form-control @error('clinic_name') is-invalid @enderror"
                                       id="clinic_name"
                                       name="clinic_name"
                                       value="{{ old('clinic_name', $doctor->clinic_name) }}"
                                       placeholder="Enter clinic name"
                                       required>
                                @error('clinic_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 4. Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold fs-13">Contact / Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
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
                            <label for="specialization" class="form-label fw-semibold fs-13">Specialization</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-award"></i></span>
                                <input type="text"
                                       class="form-control @error('specialization') is-invalid @enderror"
                                       id="specialization"
                                       name="specialization"
                                       value="{{ old('specialization', $doctor->specialization) }}"
                                       placeholder="e.g. Cardiologist / General Physician">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <a href="{{ url('/managedoctor') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Update Doctor
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>

<script>
    function autoFillEditClinicName(select) {
        const selected = select.options[select.selectedIndex];
        const name = selected.getAttribute('data-name');
        if (name) {
            document.getElementById('clinic_name').value = name;
        }
    }
</script>
@endsection

