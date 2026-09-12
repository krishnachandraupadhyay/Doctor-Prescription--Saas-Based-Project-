@extends('middleend.include.layout')
@section('title', 'Header & Footer Branding Assets - Onboarding Portal')

@section('content')
<style>
    :root {
        --brand-primary: #6366f1;
        --brand-primary-hover: #4f46e5;
        --brand-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
        --surface: #ffffff;
        --surface-alt: #f8fafc;
        --border-color: #e2e8f0;
        --border-dashed: #cbd5e1;
        --text-heading: #0f172a;
        --text-body: #334155;
        --text-muted: #64748b;
        --success: #10b981;
        --success-bg: #ecfdf5;
        --radius-lg: 16px;
        --radius-md: 12px;
        --radius-sm: 8px;
    }

    .brand-portal-wrap {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--text-body);
        padding-bottom: 2rem;
    }

    /* Page Header Hero Card */
    .hero-header-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hero-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .hero-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--brand-gradient);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 6px 16px -3px rgba(99, 102, 241, 0.3);
        flex-shrink: 0;
    }

    .hero-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 0 0 0.15rem 0;
    }

    .hero-header-subtitle {
        font-size: 0.825rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Main Container Card */
    .main-form-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        padding: 1.5rem 1.75rem;
    }

    /* Step Badge */
    .step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--brand-gradient);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.75rem;
        box-shadow: 0 3px 8px rgba(99, 102, 241, 0.25);
    }

    .section-heading-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 0;
    }

    /* Clinic Selector Box */
    .clinic-picker-panel {
        background: var(--surface-alt);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }

    .select-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
        display: block;
    }

    .custom-select-wrapper {
        position: relative;
    }

    .custom-select-wrapper i.leading-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--brand-primary);
        font-size: 1.15rem;
        pointer-events: none;
    }

    .clinic-dropdown {
        width: 100%;
        appearance: none;
        background-color: #ffffff;
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        padding: 0.65rem 2.5rem 0.65rem 2.75rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-heading);
        cursor: pointer;
        transition: all 0.2s ease;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236366f1' stroke-width='2.2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }

    .clinic-dropdown:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    /* Clinic Prescription Type Banner */
    .layout-banner {
        border-radius: var(--radius-md);
        padding: 0.85rem 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.25s ease-in-out;
    }

    .layout-banner.fixed-type {
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
    }

    .layout-banner.customize-type {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .layout-banner .banner-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .layout-banner.fixed-type .banner-icon {
        background: #6366f1;
        color: #ffffff;
    }

    .layout-banner.customize-type .banner-icon {
        background: #10b981;
        color: #ffffff;
    }

    .banner-text-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 0 0 0.1rem 0;
    }

    .banner-text-desc {
        font-size: 0.775rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Compact Asset Upload Cards */
    .asset-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease;
    }

    .asset-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
    }

    .asset-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.65rem;
    }

    .asset-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin: 0;
    }

    .asset-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.15rem 0.55rem;
        border-radius: 6px;
        background: #f1f5f9;
        color: var(--brand-primary);
        border: 1px solid #e2e8f0;
    }

    /* Centered Compact Dropzone */
    .dropzone-container {
        border: 1.5px dashed var(--border-dashed);
        border-radius: 10px;
        background: #fafbfc;
        padding: 1.1rem 0.75rem;
        text-align: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        flex-grow: 1;
        min-height: 135px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .dropzone-container:hover {
        border-color: var(--brand-primary);
        background: #faf5ff;
    }

    .dropzone-container.has-preview {
        border-style: solid;
        border-color: #e2e8f0;
        background: #ffffff;
        padding: 0.75rem;
    }

    .dropzone-container input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .upload-idle {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
    }

    .upload-icon-bubble {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ede9fe;
        color: var(--brand-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto 0.45rem auto;
        transition: transform 0.2s ease;
    }

    .dropzone-container:hover .upload-icon-bubble {
        background: var(--brand-primary);
        color: #ffffff;
        transform: scale(1.06);
    }

    .upload-main-text {
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 0.15rem;
    }

    .upload-main-text span {
        color: var(--brand-primary);
        text-decoration: underline;
    }

    .upload-sub-text {
        font-size: 0.725rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Live Preview Box */
    .preview-box {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        z-index: 5;
    }

    .preview-img-frame {
        width: 100%;
        max-height: 100px;
        border-radius: 6px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        margin-bottom: 0.45rem;
        padding: 4px;
    }

    .preview-img-frame img {
        max-height: 90px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 4px;
    }

    .preview-status-pill {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.15rem 0.55rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .preview-status-pill.saved {
        background: var(--success-bg);
        color: var(--success);
        border: 1px solid #a7f3d0;
    }

    .preview-status-pill.new-file {
        background: #ede9fe;
        color: var(--brand-primary);
        border: 1px solid #c7d2fe;
    }

    .change-prompt {
        margin-top: 0.35rem;
        font-size: 0.725rem;
        font-weight: 600;
        color: var(--brand-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    /* Action Bar */
    .form-actions-bar {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .btn-save-assets {
        background: var(--brand-gradient);
        color: #ffffff;
        border: none;
        padding: 0.65rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save-assets:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
        color: #ffffff;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid brand-portal-wrap px-4 py-3">

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center gap-2 p-3" role="alert" style="background: #ecfdf5; border-left: 4px solid #10b981 !important;">
            <i class="ri-checkbox-circle-fill text-success fs-5"></i>
            <div class="text-dark font-weight-500 fs-7">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center gap-2 p-3" role="alert" style="background: #fef2f2; border-left: 4px solid #ef4444 !important;">
            <i class="ri-error-warning-fill text-danger fs-5"></i>
            <div class="text-dark font-weight-500 fs-7">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 p-3" role="alert" style="background: #fef2f2; border-left: 4px solid #ef4444 !important;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="ri-error-warning-fill text-danger fs-6"></i>
                <strong class="text-danger fs-7">Please check errors:</strong>
            </div>
            <ul class="mb-0 ps-4 text-dark font-weight-500 fs-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Header Hero Card -->
    <div class="hero-header-card">
        <div class="hero-header-left">
            <div class="hero-icon-box">
                <i class="ri-palette-line"></i>
            </div>
            <div>
                <h1 class="hero-header-title">Prescription Branding Assets</h1>
                <p class="hero-header-subtitle">Configure clinic stamp and header/footer design assets.</p>
            </div>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill font-weight-600" style="background: #ede9fe; color: var(--brand-primary); font-size: 0.775rem; border: 1px solid #c7d2fe;">
                <i class="ri-shield-check-line me-1"></i> Onboarding Portal
            </span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="main-form-card">
        <form action="{{ route('prescriptionstore') }}" method="POST" enctype="multipart/form-data" id="prescriptionDocForm">
            @csrf

            <!-- STEP 1: Select Clinic -->
            <div class="clinic-picker-panel">
                <div class="section-heading-row">
                    <span class="step-badge">1</span>
                    <h2 class="section-title">Select Clinic</h2>
                </div>
                
                <label for="clinic_id" class="select-label">Choose Clinic to manage prescription branding:</label>
                <div class="custom-select-wrapper">
                    <i class="ri-hospital-line leading-icon"></i>
                    <select name="clinic_id" id="clinic_id" class="clinic-dropdown" required onchange="handleClinicChange(this.value)">
                        <option value="" disabled selected>-- Click to Choose a Clinic --</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" 
                                    data-type="{{ strtolower($clinic->prescription_type ?? 'fixed') }}"
                                    data-name="{{ $clinic->name }}"
                                    {{ old('clinic_id', request('clinic_id')) == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }} ({{ ucfirst($clinic->prescription_type ?? 'Fixed') }} Layout)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Dynamic Layout Information Banner -->
            <div id="layoutTypeBanner" class="layout-banner fixed-type" style="display: none;">
                <div class="banner-icon">
                    <i id="layoutTypeIcon" class="ri-layout-top-line"></i>
                </div>
                <div>
                    <h5 id="layoutTypeTitle" class="banner-text-title">Fixed Prescription Layout</h5>
                    <p id="layoutTypeDesc" class="banner-text-desc">
                        This clinic uses high-resolution graphical Header & Footer branding along with the Clinic Stamp.
                    </p>
                </div>
            </div>

            <!-- STEP 2: Upload Assets -->
            <div id="assetsSection" style="display: none;">
                <div class="section-heading-row">
                    <span class="step-badge">2</span>
                    <h2 class="section-title">Upload Clinic Branding Assets</h2>
                </div>

                <div class="row g-3">
                    <!-- 1. Clinic Logo (Available for both Customize and Fixed) -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="asset-card">
                            <div class="asset-card-header">
                                <h3 class="asset-title">
                                    <i class="ri-image-line text-primary"></i> Clinic Logo
                                </h3>
                                <span class="asset-badge">Square / Round</span>
                            </div>
                            <div class="dropzone-container" id="dropzone-photo">
                                <input type="file" name="photo" id="photo_input" accept="image/png, image/jpeg, image/jpg" onchange="handleFileSelected(this, 'photo')">
                                
                                <div class="upload-idle" id="idle-photo">
                                    <div class="upload-icon-bubble">
                                        <i class="ri-upload-cloud-2-line"></i>
                                    </div>
                                    <div class="upload-main-text">Drop Logo here or <span>Browse</span></div>
                                    <p class="upload-sub-text">PNG or JPG (Max 2MB)</p>
                                </div>

                                <div class="preview-box" id="preview-box-photo" style="display: none;">
                                    <div class="preview-img-frame">
                                        <img id="preview-img-photo" src="" alt="Clinic Logo Preview">
                                    </div>
                                    <span class="preview-status-pill saved" id="pill-photo">
                                        <i class="ri-checkbox-circle-fill"></i> Uploaded
                                    </span>
                                    <div class="change-prompt">
                                        <i class="ri-refresh-line"></i> Click to change
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Clinic Stamp (Always Required) -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="asset-card">
                            <div class="asset-card-header">
                                <h3 class="asset-title">
                                    <i class="ri-award-line text-primary"></i> Clinic Stamp
                                </h3>
                                <span class="asset-badge">Round / Oval Stamp</span>
                            </div>
                            <div class="dropzone-container" id="dropzone-clinic_stamp">
                                <input type="file" name="clinic_stamp" id="clinic_stamp_input" accept="image/png, image/jpeg, image/jpg" onchange="handleFileSelected(this, 'clinic_stamp')">
                                
                                <div class="upload-idle" id="idle-clinic_stamp">
                                    <div class="upload-icon-bubble">
                                        <i class="ri-upload-cloud-2-line"></i>
                                    </div>
                                    <div class="upload-main-text">Drop Stamp here or <span>Browse</span></div>
                                    <p class="upload-sub-text">PNG or JPG (Max 2MB)</p>
                                </div>

                                <div class="preview-box" id="preview-box-clinic_stamp" style="display: none;">
                                    <div class="preview-img-frame">
                                        <img id="preview-img-clinic_stamp" src="" alt="Clinic Stamp Preview">
                                    </div>
                                    <span class="preview-status-pill saved" id="pill-clinic_stamp">
                                        <i class="ri-checkbox-circle-fill"></i> Uploaded
                                    </span>
                                    <div class="change-prompt">
                                        <i class="ri-refresh-line"></i> Click to change
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Header Asset (Fixed Only) -->
                    <div class="col-12 col-md-6 col-lg-3 fixed-only-asset">
                        <div class="asset-card">
                            <div class="asset-card-header">
                                <h3 class="asset-title">
                                    <i class="ri-layout-top-line text-primary"></i> Header Banner
                                </h3>
                                <span class="asset-badge">Top Banner</span>
                            </div>
                            <div class="dropzone-container" id="dropzone-header">
                                <input type="file" name="header" id="header_input" accept="image/png, image/jpeg, image/jpg" onchange="handleFileSelected(this, 'header')">
                                
                                <div class="upload-idle" id="idle-header">
                                    <div class="upload-icon-bubble">
                                        <i class="ri-image-add-line"></i>
                                    </div>
                                    <div class="upload-main-text">Drop Header here or <span>Browse</span></div>
                                    <p class="upload-sub-text">PNG or JPG (Max 4MB)</p>
                                </div>

                                <div class="preview-box" id="preview-box-header" style="display: none;">
                                    <div class="preview-img-frame">
                                        <img id="preview-img-header" src="" alt="Header Preview">
                                    </div>
                                    <span class="preview-status-pill saved" id="pill-header">
                                        <i class="ri-checkbox-circle-fill"></i> Uploaded
                                    </span>
                                    <div class="change-prompt">
                                        <i class="ri-refresh-line"></i> Click to change
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Footer Asset (Fixed Only) -->
                    <div class="col-12 col-md-6 col-lg-3 fixed-only-asset">
                        <div class="asset-card">
                            <div class="asset-card-header">
                                <h3 class="asset-title">
                                    <i class="ri-layout-bottom-line text-primary"></i> Footer Banner
                                </h3>
                                <span class="asset-badge">Bottom Banner</span>
                            </div>
                            <div class="dropzone-container" id="dropzone-footer">
                                <input type="file" name="footer" id="footer_input" accept="image/png, image/jpeg, image/jpg" onchange="handleFileSelected(this, 'footer')">
                                
                                <div class="upload-idle" id="idle-footer">
                                    <div class="upload-icon-bubble">
                                        <i class="ri-image-add-line"></i>
                                    </div>
                                    <div class="upload-main-text">Drop Footer here or <span>Browse</span></div>
                                    <p class="upload-sub-text">PNG or JPG (Max 4MB)</p>
                                </div>

                                <div class="preview-box" id="preview-box-footer" style="display: none;">
                                    <div class="preview-img-frame">
                                        <img id="preview-img-footer" src="" alt="Footer Preview">
                                    </div>
                                    <span class="preview-status-pill saved" id="pill-footer">
                                        <i class="ri-checkbox-circle-fill"></i> Uploaded
                                    </span>
                                    <div class="change-prompt">
                                        <i class="ri-refresh-line"></i> Click to change
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="form-actions-bar">
                    <button type="submit" class="btn-save-assets" id="saveAssetsBtn">
                        <i class="ri-save-3-line"></i> Save Branding Assets
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Server existing documents data mapped by clinic_id
    const existingDocuments = @json($documents ?? []);

    function handleClinicChange(clinicId) {
        if (!clinicId) return;

        const selectElem = document.getElementById('clinic_id');
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const prescrType = selectedOption.getAttribute('data-type') || 'fixed';
        const clinicName = selectedOption.getAttribute('data-name') || 'Clinic';

        // Display Layout Banner
        const layoutBanner = document.getElementById('layoutTypeBanner');
        const layoutTitle = document.getElementById('layoutTypeTitle');
        const layoutDesc = document.getElementById('layoutTypeDesc');
        const layoutIcon = document.getElementById('layoutTypeIcon');
        const assetsSection = document.getElementById('assetsSection');

        layoutBanner.style.display = 'flex';
        assetsSection.style.display = 'block';

        const fixedElements = document.querySelectorAll('.fixed-only-asset');
        const headerInput = document.getElementById('header_input');
        const footerInput = document.getElementById('footer_input');

        if (prescrType === 'customize') {
            layoutBanner.className = 'layout-banner customize-type';
            layoutIcon.className = 'ri-text-spacing';
            layoutTitle.textContent = clinicName + ' - Customize Layout';
            layoutDesc.textContent = 'This clinic dynamically generates prescription headers & footers from text details. You can upload the Clinic Logo and Official Clinic Stamp.';
            
            fixedElements.forEach(el => el.style.display = 'none');
            if (headerInput) headerInput.removeAttribute('required');
            if (footerInput) footerInput.removeAttribute('required');
        } else {
            layoutBanner.className = 'layout-banner fixed-type';
            layoutIcon.className = 'ri-layout-top-line';
            layoutTitle.textContent = clinicName + ' - Fixed Layout';
            layoutDesc.textContent = 'This clinic requires high-resolution Header, Footer, and Clinic Stamp graphical assets for printing prescriptions.';
            
            fixedElements.forEach(el => el.style.display = 'block');
        }

        // Preload Existing Documents if available
        const doc = existingDocuments[clinicId];
        renderSavedAsset('photo', doc ? doc.photo : null);
        renderSavedAsset('clinic_stamp', doc ? doc.clinic_stamp : null);
        renderSavedAsset('header', doc ? doc.header : null);
        renderSavedAsset('footer', doc ? doc.footer : null);
    }

    function getAssetUrl(path) {
        if (!path) return '';
        if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:')) {
            return path;
        }
        const cleanPath = path.replace(/^\/+/, '');
        const origin = window.location.origin;
        const pathName = window.location.pathname;
        const baseMatch = pathName.match(/^(.*?\/public)/);
        if (baseMatch) {
            return origin + baseMatch[1] + '/' + cleanPath;
        }
        return origin + '/' + cleanPath;
    }

    function renderSavedAsset(field, imagePath) {
        const previewBox = document.getElementById(`preview-box-${field}`);
        const idleBox = document.getElementById(`idle-${field}`);
        const img = document.getElementById(`preview-img-${field}`);
        const pill = document.getElementById(`pill-${field}`);
        const dropzone = document.getElementById(`dropzone-${field}`);

        if (!previewBox || !idleBox || !img || !pill || !dropzone) return;

        if (imagePath) {
            const url = getAssetUrl(imagePath);

            img.onerror = function() {
                this.onerror = null;
                const cleanPath = imagePath.replace(/^\/+/, '');
                this.src = '/' + cleanPath;
            };

            img.src = url;
            previewBox.style.display = 'flex';
            idleBox.style.display = 'none';
            dropzone.classList.add('has-preview');
            pill.className = 'preview-status-pill saved';
            pill.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Current Active Asset';
        } else {
            img.onerror = null;
            img.src = '';
            previewBox.style.display = 'none';
            idleBox.style.display = 'block';
            dropzone.classList.remove('has-preview');
        }
    }

    function handleFileSelected(input, field) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewBox = document.getElementById(`preview-box-${field}`);
                const idleBox = document.getElementById(`idle-${field}`);
                const img = document.getElementById(`preview-img-${field}`);
                const pill = document.getElementById(`pill-${field}`);
                const dropzone = document.getElementById(`dropzone-${field}`);

                img.onerror = null;
                img.src = e.target.result;
                previewBox.style.display = 'flex';
                idleBox.style.display = 'none';
                dropzone.classList.add('has-preview');

                pill.className = 'preview-status-pill new-file';
                pill.innerHTML = `<i class="ri-file-upload-line"></i> ${file.name.substring(0, 20)} (${(file.size/1024).toFixed(1)} KB)`;
            };

            reader.readAsDataURL(file);
        }
    }

    // Auto-trigger if clinic was pre-selected or after validation error redirect
    document.addEventListener('DOMContentLoaded', function() {
        const selectElem = document.getElementById('clinic_id');
        if (selectElem && selectElem.value) {
            handleClinicChange(selectElem.value);
        }
    });
</script>
@endsection
