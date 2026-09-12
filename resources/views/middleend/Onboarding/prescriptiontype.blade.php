@extends('middleend.include.layout')
@section('title', 'Prescription Layout Settings - Onboarding Portal')
@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">

        <!-- Page Title Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark mb-0">Prescription Type</h4>
                <p class="text-muted small mb-0">Choose a prescription layout and assign it to a doctor.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fa fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('doctor.prescriptionType.update') }}" method="POST">
                            @csrf

                            {{-- Image selection --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary mb-3">Select layout</label>
                                <p class="text-muted small mb-3">Click to select. Double-click an image to view it full screen.</p>
                                <div class="row g-3">

                                    <div class="col-md-4 col-sm-6">
                                        <div class="image-select-card position-relative" data-value="Fixed">
                                            <span class="check-badge">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <img src="{{ asset('prescription_type/layout1.jpg') }}" alt="Fixed" class="img-fluid rounded-2 preview-img">
                                            <div class="text-center fw-semibold mt-2">Fixed</div>
                                            <div class="text-center text-muted small">Photo, sign, stamp, header and footer</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6">
                                        <div class="image-select-card position-relative" data-value="Customize">
                                            <span class="check-badge">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <img src="{{ asset('prescription_type/layout2.jpg') }}" alt="Customize" class="img-fluid rounded-2 preview-img">
                                            <div class="text-center fw-semibold mt-2">Customize</div>
                                            <div class="text-center text-muted small">Photo, sign and stamp only</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end image selection -->

                            <hr class="my-4">

                             <div class="row g-3">
                                {{-- Clinic select dropdown --}}
                                <div class="col-md-6 col-sm-12">
                                    <label for="clinic_id" class="form-label fw-semibold text-secondary">Select Clinic</label>
                                    <select class="form-select" id="clinic_id" name="clinic_id" required onchange="fillExisting()">
                                        <option value="" disabled {{ !isset($selectedClinicId) ? 'selected' : '' }}>-- Choose Clinic --</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" 
                                                    data-prescription-type="{{ $clinic->prescription_type }}"
                                                    {{ (isset($selectedClinicId) && $selectedClinicId == $clinic->id) ? 'selected' : '' }}>
                                                {{ $clinic->name }} ({{ $clinic->clinic_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Prescription type select --}}
                                <div class="col-md-6 col-sm-12">
                                    <label for="prescription_type" class="form-label fw-semibold text-secondary">Prescription type</label>
                                    <select class="form-select" id="prescription_type" name="prescription_type" required>
                                        <option value="" disabled selected>Select prescription type</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="customize">Customize</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                    <i class="fa fa-save me-1"></i> Update
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Fullscreen image preview overlay --}}
<div id="image-preview-overlay">
    <span id="image-preview-close">&times;</span>
    <img id="image-preview-img" src="" alt="Preview">
</div>

<style>
.image-select-card {
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 12px;
    background: #fff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
}
.image-select-card:hover {
    border-color: #b3c1f5;
    transform: translateY(-2px);
}
.image-select-card.selected {
    border-color: #405189;
    box-shadow: 0 0 0 3px rgba(64, 81, 137, 0.12);
}
.image-select-card img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
}
.image-select-card .check-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #405189;
    color: #fff;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 2;
}
.image-select-card.selected .check-badge {
    display: flex;
}

/* Fullscreen preview */
#image-preview-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
#image-preview-overlay.active {
    display: flex;
}
#image-preview-img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 8px;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
}
#image-preview-close {
    position: absolute;
    top: 24px;
    right: 32px;
    color: #fff;
    font-size: 36px;
    line-height: 1;
    cursor: pointer;
    font-weight: 300;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.image-select-card');
    const select = document.getElementById('prescription_type');
    const overlay = document.getElementById('image-preview-overlay');
    const overlayImg = document.getElementById('image-preview-img');
    const overlayClose = document.getElementById('image-preview-close');

    function highlightCard(value) {
        cards.forEach(function (c) {
            c.classList.toggle('selected', c.getAttribute('data-value') === value);
        });
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            const value = card.getAttribute('data-value');
            select.value = value;
            highlightCard(value);
        });
    });

    highlightCard(select.value);
    fillExisting();

    // Double-click par image fullscreen preview khole
    document.querySelectorAll('.preview-img').forEach(function (img) {
        img.addEventListener('dblclick', function (e) {
            e.stopPropagation();
            overlayImg.src = img.src;
            overlay.classList.add('active');
        });
    });

    function closePreview() {
        overlay.classList.remove('active');
        overlayImg.src = '';
    }

    overlayClose.addEventListener('click', closePreview);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closePreview();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePreview();
    });
});

// Clinic select karte hi uska pehle se saved prescription_type dikha do
function fillExisting() {
    const clinicSelect = document.getElementById('clinic_id');
    const prescriptionSelect = document.getElementById('prescription_type');
    if (!clinicSelect || !clinicSelect.options[clinicSelect.selectedIndex]) return;
    const selectedOption = clinicSelect.options[clinicSelect.selectedIndex];
    const existingType = (selectedOption.getAttribute('data-prescription-type') || '').trim();

    if (existingType) {
        prescriptionSelect.value = existingType.toLowerCase();
        document.querySelectorAll('.image-select-card').forEach(function (c) {
            c.classList.toggle('selected', c.getAttribute('data-value').toLowerCase() === existingType.toLowerCase());
        });
    }
}
</script>
@endsection