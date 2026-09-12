@extends("backend.include.layout")
@section('title', 'Add Medicine - Admin Console')

@section('content')
<style>
    .medicine-intake {
         margin-top:4.2rem;
         margin-bottom:1.5rem;
        --primary: #4f46e5;
        --primary-dark: #372fd3;
        --primary-light: #eef2ff;
        --primary-ring: rgba(79, 70, 229, 0.15);
        --text-dark: #0F172A;
        --text-body: #334155;
        --text-muted: #64748B;
        --border: #E2E8F0;
    }

    .medicine-intake * { box-sizing: border-box; 
     
    }

    .medicine-intake .intake-wrap {
        padding: 1.5rem 1rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .medicine-intake .intake-shell {
        background: #fff;
        border-radius: 15px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    /* Top Bar */
    .medicine-intake .top-bar {
        padding: 1.2rem 1.4rem;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .medicine-intake .top-bar h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .medicine-intake .top-bar h3 i {
        color: var(--primary);
    }

    .medicine-intake .top-bar p {
        margin: 4px 0 0 0;
        color: var(--text-muted);
        font-size: 0.88rem;
    }

    /* Alerts */
    .medicine-intake .alert-box {
        margin: 1rem 1.4rem 0;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .medicine-intake .alert-success-box {
        background: #ecfdf5;
        color: #065f46;
    }

    .medicine-intake .alert-danger-box {
        background: #fef2f2;
        color: #991b1b;
    }

    .medicine-intake .alert-danger-box ul {
        margin: 0;
        padding-left: 18px;
    }

    /* Form */
    .medicine-intake .intake-form {
        padding: 1.4rem;
    }

    .medicine-intake .section-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }

    .medicine-intake .section-head i {
        font-size: 16px;
        color: var(--primary);
        background: var(--primary-light);
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .medicine-intake .section-head span {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .medicine-intake .field-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
        display: inline-block;
    }

    .medicine-intake .form-control,
    .medicine-intake .form-select {
        width: 100%;
        height: 42px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 0 14px;
        font-size: 0.92rem;
        background: #f9fafb;
        transition: all 0.2s ease;
    }

    .medicine-intake textarea.form-control {
        height: 6rem;
        padding: 10px 14px;
        resize: vertical;
    }

    .medicine-intake .form-control:focus,
    .medicine-intake .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-ring);
        background: #fff;
    }

    .medicine-intake .form-control.is-invalid,
    .medicine-intake .form-select.is-invalid {
        border-color: #ef4444;
        background: #fff5f5;
    }

    .medicine-intake .field-error {
        font-size: 0.78rem;
        color: #dc2626;
        margin-top: 4px;
    }

    .medicine-intake .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2364748B' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 14px center;
        background-repeat: no-repeat;
        padding-right: 40px;
    }

    /* Buttons */
    .medicine-intake .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding-top: 1.4rem;
        border-top: 1px solid var(--border);
        margin-top: 1.4rem;
        flex-wrap: wrap;
    }

    .medicine-intake .btn-back {
        margin-right: auto;
        height: 42px;
        padding: 0 18px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f3f4f6;
        color: #374151;
        text-decoration: none;
        transition: .2s;
    }

    .medicine-intake .btn-back:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .medicine-intake .btn-cancel,
    .medicine-intake .btn-save {
        height: 42px;
        padding: 0 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }

    .medicine-intake .btn-cancel {
        border: 1.5px solid var(--border);
        background: white;
        color: var(--text-body);
    }

    .medicine-intake .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .medicine-intake .btn-save {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .medicine-intake .btn-save:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
        color: #fff;
    }

    @media (max-width: 768px) {
        .medicine-intake .intake-wrap { padding: 1rem; }
        .medicine-intake .intake-form { padding: 1.1rem; }
        .medicine-intake .form-actions { flex-direction: column; align-items: stretch; }
        .medicine-intake .btn-back { margin-right: 0; justify-content: center; }
        .medicine-intake .btn-cancel, .medicine-intake .btn-save { width: 100%; justify-content: center; }
    }
</style>

<div class="medicine-intake">
    <div class="intake-wrap">
        <div class="intake-shell">
            <!-- Top Bar -->
            <div class="top-bar">
                <div>
                    <h3><i class="bi bi-capsule"></i> Add New Medicine</h3>
                    <p>Complete the medicine registration form below</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert-box alert-success-box">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-box alert-danger-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('Addmedicine.store') }}" method="POST" class="intake-form">
                @csrf

                <div class="section-block">
                    <div class="section-head">
                        <i class="bi bi-capsule"></i>
                        <span>Medicine Details</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="medicine" class="field-label">Medicine Name <span class="text-danger">*</span></label>
                            <input type="text" name="medicine" id="medicine"
                                   class="form-control @error('medicine') is-invalid @enderror"
                                   value="{{ old('medicine') }}"
                                   placeholder="Enter Medicine Name">
                            @error('medicine')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="generic" class="field-label">Generic Name <span class="text-danger">*</span></label>
                            <input type="text" name="generic" id="generic"
                                   class="form-control @error('generic') is-invalid @enderror"
                                   value="{{ old('generic') }}"
                                   placeholder="Enter Medicine Generic Name">
                            @error('generic')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="category" class="field-label">Medicine Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control form-select @error('category') is-invalid @enderror">
                                <option value="">---- Select Medicine Category ----</option>
                                @foreach($cate as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category') == $cat->id)>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="company" class="field-label">Medicine Company <span class="text-danger">*</span></label>
                            <select name="company" id="company" class="form-control form-select @error('company') is-invalid @enderror">
                                <option value="">---- Select Medicine Company ----</option>
                                @foreach($comp as $company)
                                <option value="{{ $company->id }}" @selected(old('company') == $company->id)>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            @error('company')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="description" class="field-label">
                                Description
                                <span class="text-muted" style="font-weight:400; font-size:0.8rem;">(optional)</span>
                            </label>
                            <textarea name="description" id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Write a short description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a class="btn-back" href="{{ route('medicine') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="reset" class="btn-cancel">Clear Form</button>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-save"></i> Save 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
