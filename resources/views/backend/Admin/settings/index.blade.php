@extends('backend.include.layout')

@section('title', 'System Settings - Admin Portal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="valex-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="valex-page-title mb-1 fw-bold text-dark">
                    System Settings &amp; Configuration
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Core System Engine</span> &bull; Manage branding, dashboard colors, timezones, and security policies
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fs-12 fw-semibold">
                    <i class="bi bi-gear-fill me-1"></i> Global Settings
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <strong class="fw-semibold">Please correct the following errors:</strong>
                </div>
                <ul class="mb-0 ps-4 fs-13">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $activeTab = request()->get('tab', 'general');
        @endphp

        <!-- Main Configuration Panel -->
        <div class="row g-4">
            <!-- Left Tab Navigation Pills -->
            <div class="col-xl-3 col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 mb-3">
                    <div class="card-header bg-transparent border-bottom py-3 px-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-12 fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Navigation</span>
                            <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-11">4 Sections</span>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="nav flex-column nav-pills modern-settings-nav gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            
                            <!-- TAB 1: GENERAL & IDENTITY -->
                            <button class="nav-link text-start d-flex align-items-center justify-content-between px-3 py-2.5 rounded-3 {{ $activeTab === 'general' ? 'active' : '' }}" 
                                    id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="{{ $activeTab === 'general' ? 'true' : 'false' }}">
                                <span class="nav-title">General &amp; Identity</span>
                                <i class="bi bi-chevron-right nav-arrow fs-12 ms-2 flex-shrink-0"></i>
                            </button>

                            <!-- TAB 2: THEME & APPEARANCE -->
                            <button class="nav-link text-start d-flex align-items-center justify-content-between px-3 py-2.5 rounded-3 {{ $activeTab === 'appearance' ? 'active' : '' }}" 
                                    id="v-pills-appearance-tab" data-bs-toggle="pill" data-bs-target="#v-pills-appearance" type="button" role="tab" aria-controls="v-pills-appearance" aria-selected="{{ $activeTab === 'appearance' ? 'true' : 'false' }}">
                                <span class="nav-title">Theme &amp; Colors</span>
                                <i class="bi bi-chevron-right nav-arrow fs-12 ms-2 flex-shrink-0"></i>
                            </button>

                            <!-- TAB 3: LOCALIZATION -->
                            <button class="nav-link text-start d-flex align-items-center justify-content-between px-3 py-2.5 rounded-3 {{ $activeTab === 'localization' ? 'active' : '' }}" 
                                    id="v-pills-localization-tab" data-bs-toggle="pill" data-bs-target="#v-pills-localization" type="button" role="tab" aria-controls="v-pills-localization" aria-selected="{{ $activeTab === 'localization' ? 'true' : 'false' }}">
                                <span class="nav-title">Localization</span>
                                <i class="bi bi-chevron-right nav-arrow fs-12 ms-2 flex-shrink-0"></i>
                            </button>

                            <!-- TAB 4: PASSWORD & SECURITY -->
                            <button class="nav-link text-start d-flex align-items-center justify-content-between px-3 py-2.5 rounded-3 {{ $activeTab === 'security' ? 'active' : '' }}" 
                                    id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}">
                                <span class="nav-title">Password &amp; Security</span>
                                <i class="bi bi-chevron-right nav-arrow fs-12 ms-2 flex-shrink-0"></i>
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Fast Info Helper Card -->
                <div class="card shadow-sm border-0 rounded-3 mt-3 bg-primary-subtle text-dark">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h6 class="fw-bold fs-13 mb-1 text-primary">Instant In-Memory Cache</h6>
                                <p class="mb-0 fs-12 text-muted">
                                    All settings are cached in-memory and update dynamically across the entire portal without needing server restarts.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Tab Content Forms -->
            <div class="col-xl-9 col-lg-8">
                <div class="tab-content" id="v-pills-tabContent">

                    <!-- ========================================== -->
                    <!-- TAB 1: GENERAL & IDENTITY -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-building text-primary me-2"></i> Brand Identity &amp; Information
                                </h5>
                                <p class="text-muted fs-12 mb-0 mt-1">Configure your system title, public branding, and support channels</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="group" value="general">

                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">System / Application Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-hospital"></i></span>
                                                <input type="text" name="system_name" class="form-control" value="{{ old('system_name', $settings['system_name'] ?? 'DocPortal') }}" required>
                                            </div>
                                            <small class="text-muted fs-11">Displayed in browser title bar, navbar, and email notices.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">System Tagline</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-chat-quote"></i></span>
                                                <input type="text" name="system_tagline" class="form-control" value="{{ old('system_tagline', $settings['system_tagline'] ?? '') }}" placeholder="e.g. Doctor Prescription & Clinic Suite">
                                            </div>
                                            <small class="text-muted fs-11">Brief description displayed under the logo or header.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Support Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings['support_email'] ?? '') }}" placeholder="support@clinicportal.com">
                                            </div>
                                            <small class="text-muted fs-11">Used for system notification inquiries and support requests.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Support Phone / Helpdesk</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $settings['support_phone'] ?? '') }}" placeholder="+91 98765 43210">
                                            </div>
                                            <small class="text-muted fs-11">Displayed on contact pages and doctor support modals.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Footer Copyright Notice</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-c-circle"></i></span>
                                                <input type="text" name="footer_text" class="form-control" value="{{ old('footer_text', $settings['footer_text'] ?? 'Doctor Prescription & Clinical Management Suite.') }}" placeholder="Doctor Prescription & Clinical Management Suite.">
                                            </div>
                                            <small class="text-muted fs-11">Appears at bottom left of dashboard footer.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Designed &amp; Developed By</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-code-slash"></i></span>
                                                <input type="text" name="developed_by" class="form-control" value="{{ old('developed_by', $settings['developed_by'] ?? 'Hospital & Clinical Administration.') }}" placeholder="e.g. Designed & Developed by YourCompany">
                                            </div>
                                            <small class="text-muted fs-11">Appears at bottom right of dashboard (replaces 'Hospital &amp; Clinical Administration.').</small>
                                        </div>
                                    </div>

                                    <hr class="my-4 text-muted opacity-25">

                                    <h6 class="fw-bold text-dark mb-3 fs-14 d-flex align-items-center">
                                        <i class="bi bi-images text-primary me-2"></i> Branding Logos &amp; Icons
                                    </h6>

                                    <div class="row g-4 mb-4">
                                        <!-- Logo Upload -->
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded-3 bg-light-subtle">
                                                <label class="form-label fw-semibold fs-13 text-dark d-block">System Logo</label>
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <div class="logo-preview-box border rounded p-2 bg-white d-flex align-items-center justify-content-center" style="width: 140px; height: 60px;">
                                                        @if(!empty($settings['system_logo']) && file_exists(public_path($settings['system_logo'])))
                                                            <img src="{{ asset($settings['system_logo']) }}" id="logo-preview-img" alt="Logo" style="max-height: 48px; max-width: 120px; object-fit: contain;">
                                                        @else
                                                            <div id="logo-preview-placeholder" class="text-primary fw-bold fs-14">
                                                                <i class="bi bi-hospital fs-4 me-1"></i> DocPortal
                                                            </div>
                                                            <img src="" id="logo-preview-img" alt="Logo" class="d-none" style="max-height: 48px; max-width: 120px; object-fit: contain;">
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="system_logo" id="system_logo_input" class="form-control form-control-sm" accept="image/*" onchange="previewImage(this, 'logo-preview-img', 'logo-preview-placeholder')">
                                                        <small class="text-muted fs-11 d-block mt-1">PNG, JPG, SVG or WEBP (Max 2MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Favicon Upload -->
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded-3 bg-light-subtle">
                                                <label class="form-label fw-semibold fs-13 text-dark d-block">System Favicon</label>
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <div class="logo-preview-box border rounded p-2 bg-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        @if(!empty($settings['system_favicon']) && file_exists(public_path($settings['system_favicon'])))
                                                            <img src="{{ asset($settings['system_favicon']) }}" id="favicon-preview-img" alt="Favicon" style="max-height: 36px; max-width: 36px; object-fit: contain;">
                                                        @else
                                                            <div id="favicon-preview-placeholder" class="text-muted">
                                                                <i class="bi bi-app fs-3"></i>
                                                            </div>
                                                            <img src="" id="favicon-preview-img" alt="Favicon" class="d-none" style="max-height: 36px; max-width: 36px; object-fit: contain;">
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="system_favicon" id="system_favicon_input" class="form-control form-control-sm" accept="image/*,.ico" onchange="previewImage(this, 'favicon-preview-img', 'favicon-preview-placeholder')">
                                                        <small class="text-muted fs-11 d-block mt-1">ICO, PNG (Max 1MB, 32x32 recommended)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-check2-circle me-1"></i> Save Identity Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 2: THEME & APPEARANCE -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade {{ $activeTab === 'appearance' ? 'show active' : '' }}" id="v-pills-appearance" role="tabpanel" aria-labelledby="v-pills-appearance-tab">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-palette-fill text-primary me-2"></i> Dashboard Theme &amp; Color Scheme
                                </h5>
                                <p class="text-muted fs-12 mb-0 mt-1">Customize primary accent palette, light/dark mode, and navigation appearance</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update') }}" method="POST" id="appearanceForm">
                                    @csrf
                                    <input type="hidden" name="group" value="appearance">

                                    <!-- Primary Theme Color Selection -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold fs-14 text-dark mb-2">Primary Accent Color</label>
                                        <p class="text-muted fs-12 mb-3">Select one of our healthcare curated palettes or choose a custom brand hex code.</p>

                                        <!-- Pre-set Palettes -->
                                        <div class="row g-3 mb-3">
                                            @foreach($presetThemes as $preset)
                                                <div class="col-xl-3 col-sm-6">
                                                    <div class="theme-preset-card border rounded-3 d-flex align-items-center cursor-pointer {{ strtolower($settings['theme_color'] ?? '#0162e8') === strtolower($preset['color']) ? 'active' : '' }}"
                                                         onclick="selectThemeColor('{{ $preset['color'] }}')">
                                                        <span class="color-circle shadow-sm" style="background-color: {{ $preset['color'] }};"></span>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-semibold fs-13 text-dark text-truncate">{{ $preset['name'] }}</div>
                                                            <div class="text-muted fs-11 font-monospace">{{ $preset['color'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Custom Color Picker & Hex Input -->
                                        <div class="p-3 border rounded-3 bg-light-subtle d-flex flex-wrap align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <label for="theme_color_picker" class="fw-semibold fs-13 text-dark mb-0">Custom Color:</label>
                                                <input type="color" id="theme_color_picker" class="form-control form-control-color border-0 p-0 rounded-circle cursor-pointer" style="width: 36px; height: 36px;" value="{{ $settings['theme_color'] ?? '#0162e8' }}" oninput="updateHexFromPicker(this.value)">
                                            </div>
                                            <div class="input-group" style="width: 180px;">
                                                <span class="input-group-text bg-white text-muted">HEX</span>
                                                <input type="text" name="theme_color" id="theme_color_hex" class="form-control text-uppercase" value="{{ $settings['theme_color'] ?? '#0162e8' }}" maxlength="7" oninput="updatePickerFromHex(this.value)" required>
                                            </div>
                                            <span class="badge px-3 py-2 text-white fw-medium rounded-pill shadow-sm" id="theme-live-sample" style="background-color: {{ $settings['theme_color'] ?? '#0162e8' }};">
                                                Live Sample Button
                                            </span>
                                        </div>
                                    </div>

                                    <hr class="my-4 text-muted opacity-25">

                                    <!-- Theme Mode: Light / Dark -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold fs-14 text-dark mb-2">Color Scheme Mode</label>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="theme-mode-card border rounded-3 p-3 d-flex align-items-center gap-3 w-100 cursor-pointer {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'active' : '' }}">
                                                    <input type="radio" name="theme_mode" value="light" class="form-check-input mt-0" {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'checked' : '' }}>
                                                    <div class="d-flex align-items-center justify-content-center bg-light rounded p-2 text-primary" style="width: 44px; height: 44px;">
                                                        <i class="bi bi-sun-fill fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-14">Light Mode</h6>
                                                        <small class="text-muted fs-12">Clean, bright healthcare UI (Recommended)</small>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="theme-mode-card border rounded-3 p-3 d-flex align-items-center gap-3 w-100 cursor-pointer {{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'active' : '' }}">
                                                    <input type="radio" name="theme_mode" value="dark" class="form-check-input mt-0" {{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'checked' : '' }}>
                                                    <div class="d-flex align-items-center justify-content-center bg-dark rounded p-2 text-warning" style="width: 44px; height: 44px;">
                                                        <i class="bi bi-moon-stars-fill fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-14">Dark Mode</h6>
                                                        <small class="text-muted fs-12">High-contrast sleek night vision scheme</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4 text-muted opacity-25">

                                    <!-- Sidebar & Topbar Style -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold fs-14 text-dark mb-2">Sidebar Style</label>
                                            <select name="sidebar_color" class="form-select">
                                                <option value="dark" {{ ($settings['sidebar_color'] ?? 'dark') === 'dark' ? 'selected' : '' }}>Dark Sidebar (Classic Navy / Valex Dark)</option>
                                                <option value="light" {{ ($settings['sidebar_color'] ?? 'dark') === 'light' ? 'selected' : '' }}>Light Sidebar (Pure White)</option>
                                                <option value="gradient" {{ ($settings['sidebar_color'] ?? 'dark') === 'gradient' ? 'selected' : '' }}>Gradient (Dynamic Primary Blend)</option>
                                            </select>
                                            <small class="text-muted fs-11">Sets the background styling of the left menu.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold fs-14 text-dark mb-2">Topbar / Header Style</label>
                                            <select name="topbar_color" class="form-select">
                                                <option value="light" {{ ($settings['topbar_color'] ?? 'light') === 'light' ? 'selected' : '' }}>Light Topbar (Clean White)</option>
                                                <option value="dark" {{ ($settings['topbar_color'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark Topbar (Contrast)</option>
                                            </select>
                                            <small class="text-muted fs-11">Controls the top navigation banner color.</small>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-check2-circle me-1"></i> Save Appearance Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 3: LOCALIZATION -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade {{ $activeTab === 'localization' ? 'show active' : '' }}" id="v-pills-localization" role="tabpanel" aria-labelledby="v-pills-localization-tab">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-globe-americas text-primary me-2"></i> Timezone &amp; Regional Localization
                                </h5>
                                <p class="text-muted fs-12 mb-0 mt-1">Configure default timezone, calendar formatting, and billing currencies</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="localization">

                                    <div class="row g-4 mb-4">
                                        <!-- Timezone -->
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold fs-13 text-dark">System Default Timezone <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-clock-history"></i></span>
                                                <select name="timezone" class="form-select select-timezone" required>
                                                    @foreach($timezones as $tzKey => $tzLabel)
                                                        <option value="{{ $tzKey }}" {{ ($settings['timezone'] ?? 'Asia/Kolkata') === $tzKey ? 'selected' : '' }}>
                                                            {{ $tzKey }} &mdash; {{ $tzLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <small class="text-muted fs-11">Current Server Time: <strong class="text-primary">{{ now()->format('d M Y, h:i A') }}</strong> (Timezone: {{ config('app.timezone') }})</small>
                                        </div>

                                        <!-- Date Format -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Default Date Format <span class="text-danger">*</span></label>
                                            <select name="date_format" class="form-select" required>
                                                @foreach($dateFormats as $dfKey => $dfLabel)
                                                    <option value="{{ $dfKey }}" {{ ($settings['date_format'] ?? 'd M Y') === $dfKey ? 'selected' : '' }}>
                                                        {{ $dfLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted fs-11">Applied across patient visits, prescription headers, and invoice tables.</small>
                                        </div>

                                        <!-- Currency Symbol & Code -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Billing Currency <span class="text-danger">*</span></label>
                                            <select name="currency_code" id="currency_code_select" class="form-select" onchange="updateCurrencySymbol(this)">
                                                @foreach($currencies as $code => $cData)
                                                    <option value="{{ $code }}" data-symbol="{{ $cData['symbol'] }}" {{ ($settings['currency_code'] ?? 'INR') === $code ? 'selected' : '' }}>
                                                        {{ $code }} &mdash; {{ $cData['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted fs-11">Used in SaaS plans, clinic invoices, and payment receipts.</small>
                                        </div>

                                        <!-- Currency Symbol (Manual Override) -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Currency Symbol <span class="text-danger">*</span></label>
                                            <input type="text" name="currency_symbol" id="currency_symbol_input" class="form-control" value="{{ $settings['currency_symbol'] ?? '₹' }}" required>
                                            <small class="text-muted fs-11">Symbol display character (e.g. ₹, $, €, £).</small>
                                        </div>

                                        <!-- Currency Position -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">Currency Position <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="currency_position" id="currPosBefore" value="before" {{ ($settings['currency_position'] ?? 'before') === 'before' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-medium fs-13" for="currPosBefore">
                                                        Prefix (e.g. ₹1,500)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="currency_position" id="currPosAfter" value="after" {{ ($settings['currency_position'] ?? 'before') === 'after' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-medium fs-13" for="currPosAfter">
                                                        Suffix (e.g. 1,500 ₹)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-check2-circle me-1"></i> Save Localization Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 4: PASSWORD LIMITS & SECURITY -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade {{ $activeTab === 'security' ? 'show active' : '' }}" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill text-primary me-2"></i> Password Limits &amp; Security Policy
                                </h5>
                                <p class="text-muted fs-12 mb-0 mt-1">Enforce password strength, session expiration timers, and lockout thresholds</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="security">

                                    <div class="row g-4 mb-4">
                                        <!-- Minimum Password Length -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">
                                                Minimum Password Length <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-123"></i></span>
                                                <input type="number" name="min_password_length" class="form-control" min="6" max="32" value="{{ old('min_password_length', $settings['min_password_length'] ?? '8') }}" required>
                                                <span class="input-group-text bg-light text-muted">characters</span>
                                            </div>
                                            <small class="text-muted fs-11">Recommended minimum length: 8 characters (ISO/IEC 27001 standard).</small>
                                        </div>

                                        <!-- Session Lifetime -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">
                                                Session Inactivity Timeout <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-hourglass-split"></i></span>
                                                <input type="number" name="session_lifetime" class="form-control" min="15" max="1440" value="{{ old('session_lifetime', $settings['session_lifetime'] ?? '120') }}" required>
                                                <span class="input-group-text bg-light text-muted">minutes</span>
                                            </div>
                                            <small class="text-muted fs-11">Auto logout user after period of inactivity (e.g. 120 mins).</small>
                                        </div>

                                        <!-- Max Failed Logins -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-13 text-dark">
                                                Max Failed Login Attempts Before Lockout <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-exclamation-octagon"></i></span>
                                                <input type="number" name="max_login_attempts" class="form-control" min="3" max="20" value="{{ old('max_login_attempts', $settings['max_login_attempts'] ?? '5') }}" required>
                                                <span class="input-group-text bg-light text-muted">attempts</span>
                                            </div>
                                            <small class="text-muted fs-11">Number of consecutive wrong password tries before temporary account lockout.</small>
                                        </div>
                                    </div>

                                    <hr class="my-4 text-muted opacity-25">

                                    <h6 class="fw-bold text-dark mb-3 fs-14 d-flex align-items-center">
                                        <i class="bi bi-check-all text-primary me-2"></i> Password Complexity Rules
                                    </h6>

                                    <div class="row g-3 mb-4">
                                        <!-- Uppercase Rule -->
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light-subtle d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="fw-semibold fs-13 text-dark">Uppercase Letter</div>
                                                    <small class="text-muted fs-11">Requires at least one (A-Z)</small>
                                                </div>
                                                <div class="form-check form-switch fs-5 mb-0">
                                                    <input class="form-check-input" type="checkbox" name="pwd_require_uppercase" value="1" {{ ($settings['pwd_require_uppercase'] ?? '1') == '1' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Numbers Rule -->
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light-subtle d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="fw-semibold fs-13 text-dark">Numbers / Digits</div>
                                                    <small class="text-muted fs-11">Requires at least one (0-9)</small>
                                                </div>
                                                <div class="form-check form-switch fs-5 mb-0">
                                                    <input class="form-check-input" type="checkbox" name="pwd_require_number" value="1" {{ ($settings['pwd_require_number'] ?? '1') == '1' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Special Characters Rule -->
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light-subtle d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="fw-semibold fs-13 text-dark">Special Characters</div>
                                                    <small class="text-muted fs-11">Requires (!@#$%^&amp;*)</small>
                                                </div>
                                                <div class="form-check form-switch fs-5 mb-0">
                                                    <input class="form-check-input" type="checkbox" name="pwd_require_special" value="1" {{ ($settings['pwd_require_special'] ?? '0') == '1' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-check2-circle me-1"></i> Save Security Policy
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Sleek Modern Settings Navigation (Stripe / Linear Style) */
.modern-settings-nav .nav-link {
    background: transparent;
    border: 1px solid transparent;
    padding: 12px 16px !important;
    border-radius: 8px;
    transition: all 0.18s ease;
    text-align: left;
}
.modern-settings-nav .nav-link:hover {
    background-color: #f8fafc;
    border-color: #e2e8f0;
}
.modern-settings-nav .nav-link:hover .nav-arrow {
    color: var(--valex-primary, #0162e8);
    transform: translateX(2px);
}

.modern-settings-nav .nav-link .nav-title {
    font-size: 13.5px;
    font-weight: 500;
    color: #334155;
    line-height: 1.4;
    transition: color 0.18s ease;
}
.modern-settings-nav .nav-link .nav-arrow {
    color: #94a3b8;
    transition: all 0.18s ease;
}

/* ACTIVE STATE - Clean Soft Healthcare Pill with Left Accent */
.modern-settings-nav .nav-link.active {
    background-color: #eff6ff !important;
    border: 1px solid #bfdbfe !important;
    border-left: 3.5px solid var(--valex-primary, #0162e8) !important;
    box-shadow: 0 1px 3px rgba(1, 98, 232, 0.08) !important;
}
.modern-settings-nav .nav-link.active .nav-title {
    color: var(--valex-primary, #0162e8) !important;
    font-weight: 700 !important;
}
.modern-settings-nav .nav-link.active .nav-arrow {
    color: var(--valex-primary, #0162e8) !important;
    transform: translateX(2px);
}

/* Theme Preset Card */
.theme-preset-card {
    padding: 12px 14px !important;
    gap: 12px !important;
    border-radius: 10px !important;
    border: 1.5px solid #e2e8f0 !important;
    background-color: #ffffff;
    transition: all 0.2s ease;
}
.theme-preset-card .color-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
}
.theme-preset-card:hover {
    border-color: #94a3b8 !important;
    background-color: #f8fafc;
    transform: translateY(-1px);
}
.theme-preset-card.active {
    border-color: var(--valex-primary, #0162e8) !important;
    background-color: rgba(1, 98, 232, 0.05);
    box-shadow: 0 0 0 2px rgba(1, 98, 232, 0.25) !important;
}

/* Theme Mode Card */
.theme-mode-card {
    transition: all 0.2s ease;
    border-color: #e2e8f0;
}
.theme-mode-card:hover {
    border-color: #94a3b8;
}
.theme-mode-card.active {
    border-color: var(--valex-primary, #0162e8) !important;
    background-color: rgba(1, 98, 232, 0.04);
}
</style>

<script>
function selectThemeColor(hex) {
    document.getElementById('theme_color_hex').value = hex.toUpperCase();
    document.getElementById('theme_color_picker').value = hex;
    document.getElementById('theme-live-sample').style.backgroundColor = hex;

    // update active state in cards
    document.querySelectorAll('.theme-preset-card').forEach(card => {
        card.classList.remove('active');
        if (card.textContent.toLowerCase().includes(hex.toLowerCase())) {
            card.classList.add('active');
        }
    });
}

function updateHexFromPicker(color) {
    document.getElementById('theme_color_hex').value = color.toUpperCase();
    document.getElementById('theme-live-sample').style.backgroundColor = color;
}

function updatePickerFromHex(hex) {
    if (/^#[0-9A-F]{6}$/i.test(hex)) {
        document.getElementById('theme_color_picker').value = hex;
        document.getElementById('theme-live-sample').style.backgroundColor = hex;
    }
}

function updateCurrencySymbol(select) {
    const symbol = select.options[select.selectedIndex].getAttribute('data-symbol');
    if (symbol) {
        document.getElementById('currency_symbol_input').value = symbol;
    }
}

function previewImage(input, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
