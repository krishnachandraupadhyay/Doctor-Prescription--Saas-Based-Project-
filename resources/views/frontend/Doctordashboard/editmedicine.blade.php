@extends("frontend.include.layout")
@section('title', 'Edit Medicine - Doctor Portal')

@section('content')
<style>
    :root {
        --med-primary: #0284c7;
        --med-primary-dark: #0369a1;
        --med-primary-light: #e0f2fe;
        --med-primary-ring: rgba(2, 132, 199, 0.18);
        --med-accent: #0f766e;
        --med-bg-subtle: #f8fafc;
        --med-card-bg: #ffffff;
        --med-text-main: #0f172a;
        --med-text-muted: #64748b;
        --med-border: #e2e8f0;
        --med-border-hover: #cbd5e1;
        --med-radius: 16px;
        --med-radius-sm: 10px;
    }

    .page-content {
        padding-top: calc(70px + 0.5rem) !important;
    }

    .edit-medicine-wrapper {
        margin: 0 auto 1.5rem auto;
        padding: 0;
        width: 100%;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .edit-medicine-card {
        background: var(--med-card-bg);
        border-radius: var(--med-radius);
        border: 1px solid var(--med-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    /* Header Styling */
    .edit-medicine-header {
        padding: 1.25rem 1.75rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid var(--med-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .edit-medicine-header .header-title-group {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .edit-medicine-header .header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--med-primary) 0%, var(--med-accent) 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        flex-shrink: 0;
    }

    .edit-medicine-header h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--med-text-main);
        margin: 0 0 0.15rem 0;
        letter-spacing: -0.01em;
    }

    .edit-medicine-header p {
        margin: 0;
        color: var(--med-text-muted);
        font-size: 0.825rem;
    }

    .medicine-id-badge {
        background: var(--med-primary-light);
        color: var(--med-primary-dark);
        font-weight: 600;
        font-size: 0.775rem;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        border: 1px solid rgba(2, 132, 199, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Form Layout */
    .edit-medicine-body {
        padding: 1.5rem 1.75rem;
    }

    .form-group-custom {
        margin-bottom: 1.25rem;
    }

    .form-label-custom {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--med-text-main);
        margin-bottom: 0.4rem;
    }

    .form-label-custom .text-danger {
        color: #ef4444 !important;
        font-weight: bold;
    }

    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 1rem;
        color: var(--med-text-muted);
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .form-control-custom,
    .form-select-custom,
    .form-textarea-custom {
        width: 100%;
        background-color: #ffffff;
        border: 1.5px solid var(--med-border);
        border-radius: var(--med-radius-sm);
        padding: 0.6rem 1rem 0.6rem 2.75rem;
        font-size: 0.9rem;
        color: var(--med-text-main);
        transition: all 0.2s ease-in-out;
        box-sizing: border-box;
    }

    .form-control-custom:hover,
    .form-select-custom:hover,
    .form-textarea-custom:hover {
        border-color: var(--med-border-hover);
    }

    .form-control-custom:focus,
    .form-select-custom:focus,
    .form-textarea-custom:focus {
        outline: none;
        border-color: var(--med-primary);
        box-shadow: 0 0 0 4px var(--med-primary-ring);
        background-color: #ffffff;
    }

    .input-icon-wrapper:focus-within i {
        color: var(--med-primary);
    }

    .form-select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.1em;
        padding-right: 2.75rem;
        cursor: pointer;
    }

    .form-textarea-custom {
        min-height: 65px;
        height: 65px;
        padding-left: 2.75rem;
        resize: vertical;
    }

    /* Actions Bar */
    .edit-medicine-footer {
        padding: 1rem 1.75rem;
        background: var(--med-bg-subtle);
        border-top: 1px solid var(--med-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .footer-btn-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-med-back {
        background: #ffffff;
        color: var(--med-text-muted);
        border: 1.5px solid var(--med-border);
        padding: 0.55rem 1.15rem;
        border-radius: var(--med-radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-med-back:hover {
        background: var(--med-bg-subtle);
        color: var(--med-text-main);
        border-color: var(--med-border-hover);
        text-decoration: none;
    }

    .btn-med-reset {
        background: #ffffff;
        color: var(--med-text-muted);
        border: 1.5px solid var(--med-border);
        padding: 0.55rem 1.15rem;
        border-radius: var(--med-radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-med-reset:hover {
        background: #f1f5f9;
        color: var(--med-text-main);
        border-color: #cbd5e1;
    }

    .btn-med-save {
        background: linear-gradient(135deg, var(--med-primary) 0%, var(--med-primary-dark) 100%);
        color: #ffffff;
        border: none;
        padding: 0.6rem 1.6rem;
        border-radius: var(--med-radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
    }

    .btn-med-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        color: #ffffff;
    }

    /* Alerts */
    .alert-custom-success {
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        border-radius: var(--med-radius-sm);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-custom-danger {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: var(--med-radius-sm);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }

    .alert-custom-danger ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    @media (max-width: 768px) {
        .page-content {
            padding-top: calc(60px + 0.5rem) !important;
        }
        .edit-medicine-wrapper {
            margin-top: 0;
            padding: 0;
        }
        .edit-medicine-header,
        .edit-medicine-body,
        .edit-medicine-footer {
            padding: 1rem;
        }
        .edit-medicine-footer {
            flex-direction: column;
            align-items: stretch;
        }
        .footer-btn-group {
            flex-direction: column;
            width: 100%;
        }
        .btn-med-back, .btn-med-reset, .btn-med-save {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="edit-medicine-wrapper">
                    <div class="edit-medicine-card">
                        <!-- Top Bar Header -->
                        <div class="edit-medicine-header">
                            <div class="header-title-group">
                                <div class="header-icon">
                                    <i class="bi bi-capsule-pill"></i>
                                </div>
                                <div>
                                    <h3>Edit Medicine Details</h3>
                                    <p>Update medicine specifications, active components, category, and vendor details</p>
                                </div>
                            </div>
                            <div class="medicine-id-badge">
                                <i class="bi bi-hash"></i> ID: {{ $medicine->id }}
                            </div>
                        </div>

                        <!-- Notification Alerts -->
                        @if (session('success') || $errors->any())
                            <div style="padding: 1.25rem 1.75rem 0 1.75rem;">
                                @if (session('success'))
                                    <div class="alert-custom-success">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                        <div>{{ session('success') }}</div>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert-custom-danger">
                                        <div class="d-flex height-auto align-items-center gap-2 mb-2">
                                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                            <strong>Please correct the errors below:</strong>
                                        </div>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Edit Form -->
                        <form action="{{ route('medicine.update', $medicine->id) }}" method="POST">
                            @csrf

                            <div class="edit-medicine-body">
                                <div class="row">
                                    <!-- Medicine Name -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group-custom">
                                            <label for="medicine" class="form-label-custom">
                                                Medicine Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="bi bi-capsule"></i>
                                                <input type="text" 
                                                       name="medicine" 
                                                       id="medicine" 
                                                       class="form-control-custom" 
                                                       placeholder="e.g. Amoxicillin 500mg" 
                                                       value="{{ old('medicine', $medicine->medicine_name) }}" 
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Generic Name -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group-custom">
                                            <label for="generic" class="form-label-custom">
                                                Generic Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="bi bi-journal-medical"></i>
                                                <input type="text" 
                                                       name="generic" 
                                                       id="generic" 
                                                       class="form-control-custom" 
                                                       placeholder="e.g. Amoxicillin Trihydrate" 
                                                       value="{{ old('generic', $medicine->generic_name) }}" 
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Medicine Category -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group-custom">
                                            <label for="category" class="form-label-custom">
                                                Medicine Category <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="bi bi-grid-3x3-gap-fill"></i>
                                                <select name="category" id="category" class="form-select-custom" required>
                                                    <option value="" disabled>-- Select Medicine Category --</option>
                                                    @foreach($cate as $cat)
                                                        <option value="{{ $cat->id }}" {{ $medicine->category_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Medicine Company -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group-custom">
                                            <label for="company" class="form-label-custom">
                                                Medicine Company <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="bi bi-building"></i>
                                                <select name="company" id="company" class="form-select-custom" required>
                                                    <option value="" disabled>-- Select Medicine Company --</option>
                                                    @foreach($comp as $cp)
                                                        <option value="{{ $cp->id }}" {{ $medicine->company_id == $cp->id ? 'selected' : '' }}>
                                                            {{ $cp->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <div class="form-group-custom">
                                            <label for="description" class="form-label-custom">
                                                Description & Usage Instructions
                                            </label>
                                            <div class="input-icon-wrapper align-items-start">
                                                <i class="bi bi-file-earmark-text" style="top: 0.85rem;"></i>
                                                <textarea name="description" 
                                                          id="description" 
                                                          class="form-textarea-custom" 
                                                          placeholder="Enter medicine dosage guidelines, precautions, or additional notes...">{{ old('description', $medicine->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="edit-medicine-footer">
                                <a href="{{ route('medicine') }}" class="btn-med-back">
                                    <i class="bi bi-arrow-left"></i> Back to Medicine List
                                </a>
                                <div class="footer-btn-group">
                                    <button type="reset" class="btn-med-reset">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                                    </button>
                                    <button type="submit" class="btn-med-save">
                                        <i class="bi bi-check2-circle fs-6"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
