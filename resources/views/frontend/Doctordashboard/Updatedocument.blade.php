@extends("frontend.include.layout")
@section('title', 'Update Clinic Documents - Doctor Portal')
@include('frontend.include.css')

@section('content')
<style>
    :root {
        --doc-primary: #0284c7;
        --doc-primary-dark: #075985;
        --doc-primary-light: #e0f2fe;
        --doc-primary-ring: rgba(2, 132, 199, 0.15);
        --doc-accent: #0f766e;
        --doc-bg-subtle: #f8fafc;
        --doc-card-bg: #ffffff;
        --doc-text-main: #0f172a;
        --doc-text-muted: #64748b;
        --doc-text-faint: #94a3b8;
        --doc-border: #e2e8f0;
        --doc-border-hover: #cbd5e1;
        --doc-radius: 16px;
        --doc-radius-sm: 10px;
        --doc-success: #059669;
        --doc-success-bg: #ecfdf5;
        --doc-success-border: #a7f3d0;
        --doc-danger: #dc2626;
    }

    /* NOTE: .page-content is the theme's own layout class - left untouched.
       All page-specific styling lives in .doc-page-pad instead. */
    .doc-page-pad { background: var(--doc-bg-subtle); }

    .doc-upload-wrapper {
        margin: 0 auto 1.5rem auto;
        width: 100%;
        font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .doc-breadcrumb {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; color: var(--doc-text-muted);
        margin-bottom: 0.9rem; font-weight: 500;
    }
    .doc-breadcrumb a { color: var(--doc-text-muted); text-decoration: none; transition: color 0.15s ease; }
    .doc-breadcrumb a:hover { color: var(--doc-primary); }
    .doc-breadcrumb i { font-size: 0.7rem; color: var(--doc-text-faint); }
    .doc-breadcrumb .doc-breadcrumb-current { color: var(--doc-text-main); font-weight: 600; }

    .doc-page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;
    }
    .doc-page-head .doc-head-title-group { display: flex; align-items: center; gap: 0.9rem; }
    .doc-page-head .doc-head-icon {
        width: 46px; height: 46px; border-radius: 13px;
        background: linear-gradient(135deg, var(--doc-primary) 0%, var(--doc-accent) 100%);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.28); flex-shrink: 0;
    }
    .doc-page-head h1 { font-size: 1.22rem; font-weight: 700; color: var(--doc-text-main); margin: 0 0 0.2rem 0; letter-spacing: -0.01em; }
    .doc-page-head .doc-sub { margin: 0; color: var(--doc-text-muted); font-size: 0.82rem; }
    .doc-progress-badge {
        background: var(--doc-primary-light); color: var(--doc-primary-dark);
        font-weight: 600; font-size: 0.775rem; padding: 0.4rem 0.9rem;
        border-radius: 20px; border: 1px solid rgba(2, 132, 199, 0.18);
        display: inline-flex; align-items: center; gap: 0.4rem;
    }

    .doc-alert-success {
        background-color: var(--doc-success-bg); border: 1px solid var(--doc-success-border);
        color: #065f46; border-radius: var(--doc-radius-sm); padding: 0.8rem 1.1rem;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;
        font-size: 0.85rem; font-weight: 500;
    }
    .doc-alert-danger {
        background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
        border-radius: var(--doc-radius-sm); padding: 0.85rem 1.1rem;
        margin-bottom: 1rem; font-size: 0.85rem;
    }
    .doc-alert-danger ul { margin: 0; padding-left: 1.25rem; }

    .doc-section-card {
        background: var(--doc-card-bg); border-radius: var(--doc-radius);
        border: 1px solid var(--doc-border);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 20px rgba(15, 23, 42, 0.04);
        overflow: hidden; margin-bottom: 1.1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .doc-section-card.is-complete { border-color: var(--doc-success-border); }

    .doc-section-head {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 1.15rem 1.5rem; border-bottom: 1px solid var(--doc-border);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    .doc-section-num {
        width: 30px; height: 30px; border-radius: 9px;
        background: var(--doc-primary-light); color: var(--doc-primary-dark);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700; flex-shrink: 0; transition: all 0.2s ease;
    }
    .doc-section-card.is-complete .doc-section-num { background: var(--doc-success); color: #fff; }
    .doc-section-head h2 { font-size: 0.98rem; font-weight: 700; color: var(--doc-text-main); margin: 0 0 0.1rem 0; }
    .doc-section-head p { margin: 0; font-size: 0.78rem; color: var(--doc-text-muted); }
    .doc-required-tag {
        margin-left: auto; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.03em; color: var(--doc-text-faint); flex-shrink: 0;
    }
    .doc-section-card.is-complete .doc-required-tag { color: var(--doc-success); }

    .doc-section-body {
        padding: 1.4rem 1.5rem; display: grid;
        grid-template-columns: 1fr 1fr; gap: 1.1rem 1.25rem;
    }
    .doc-field.span2 { grid-column: 1 / -1; }
    .doc-field label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--doc-text-main); margin-bottom: 0.4rem; }
    .doc-field label .doc-opt { font-weight: 500; color: var(--doc-text-faint); font-size: 0.72rem; }

    .doc-field input[type="text"],
    .doc-field input[type="date"] {
        width: 100%; background: #fff; border: 1.5px solid var(--doc-border);
        border-radius: var(--doc-radius-sm); padding: 0.6rem 0.9rem;
        font-size: 0.88rem; color: var(--doc-text-main);
        transition: all 0.2s ease-in-out; box-sizing: border-box;
    }
    .doc-field input[type="text"]:hover,
    .doc-field input[type="date"]:hover { border-color: var(--doc-border-hover); }
    .doc-field input[type="text"]:focus,
    .doc-field input[type="date"]:focus {
        outline: none; border-color: var(--doc-primary); box-shadow: 0 0 0 4px var(--doc-primary-ring);
    }

    .doc-file-tile {
        display: block; position: relative; border: 1.5px dashed var(--doc-border);
        border-radius: var(--doc-radius-sm); background: var(--doc-bg-subtle);
        padding: 1.5rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s ease;
    }
    .doc-file-tile:hover { border-color: var(--doc-primary); background: var(--doc-primary-light); }
    .doc-file-tile.has-file { border-style: solid; border-color: var(--doc-success-border); background: var(--doc-success-bg); }
    .doc-file-tile input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .doc-upload-icon {
        width: 34px; height: 34px; border-radius: 50%; background: var(--doc-primary-light);
        color: var(--doc-primary-dark); display: flex; align-items: center; justify-content: center;
        margin: 0 auto 0.5rem auto; font-size: 1rem;
    }
    .doc-file-tile.has-file .doc-upload-icon { background: var(--doc-success); color: #fff; }
    .doc-tile-label { font-size: 0.85rem; font-weight: 600; color: var(--doc-text-main); word-break: break-all; }
    .doc-tile-sub { font-size: 0.72rem; color: var(--doc-text-faint); margin-top: 0.25rem; }

    .doc-field .text-danger { display: block; font-size: 0.75rem; color: var(--doc-danger); margin-top: 0.35rem; }

    .doc-action-bar {
        position: sticky; bottom: 0; background: var(--doc-card-bg);
        border: 1px solid var(--doc-border); border-radius: var(--doc-radius);
        padding: 1.1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.04); margin-top: 0.25rem;
    }
    .doc-action-hint { font-size: 0.8rem; color: var(--doc-text-muted); }
    .btn-doc-submit {
        background: linear-gradient(135deg, var(--doc-primary) 0%, var(--doc-primary-dark) 100%);
        color: #fff; border: none; padding: 0.65rem 1.8rem; border-radius: var(--doc-radius-sm);
        font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;
        cursor: pointer; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); transition: all 0.2s ease;
    }
    .btn-doc-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35); color: #fff; }

    @media (max-width: 640px) {
        .doc-section-body { grid-template-columns: 1fr; }
        .doc-action-bar { flex-direction: column; align-items: stretch; text-align: center; }
    }
</style>

<div class="page-content doc-page-pad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="doc-upload-wrapper">

                    <nav class="doc-breadcrumb">
                        <a href="{{ route('nodashboard') }}">Dashboard</a>
                        <i class="bi bi-chevron-right"></i>
                        <span class="doc-breadcrumb-current">Upload Documents</span>
                    </nav>

                    @if (session('success'))
                        <div class="doc-alert-success">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="doc-alert-danger">
                            <div class="d-flex align-items-center gap-2 mb-2">
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

                    <div class="doc-page-head">
                        <div class="doc-head-title-group">
                            <div class="doc-head-icon"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                            <div>
                                <h1>Upload Documents</h1>
                                <p class="doc-sub">Upload each required document once. All 5 documents are submitted together.</p>
                            </div>
                        </div>
                        <div class="doc-progress-badge">
                            <i class="bi bi-list-check"></i>
                            <span id="docProgressText">0 / 5 completed</span>
                        </div>
                    </div>

                    {{--
                        Field naming convention (matches doctors_document table):
                        documents[TYPE][type]        -> document_type
                        documents[TYPE][name]        -> document_name
                        documents[TYPE][number]      -> document_number
                        documents[TYPE][file]        -> document_file
                        documents[TYPE][issue_date]  -> issue_date
                        documents[TYPE][expiry_date] -> expiry_date
                        documents[TYPE][step]        -> document_step

                        Controller side, loop like:
                        foreach ($request->documents as $key => $doc) {
                            $path = $request->file("documents.$key.file")->store('documents', 'public');
                            DoctorDocument::updateOrCreate(
                                ['doctor_id' => auth('doctor')->id(), 'document_type' => $doc['type']],
                                [
                                    'document_name'   => $doc['name'],
                                    'document_number' => $doc['number'] ?? null,
                                    'document_file'   => $path,
                                    'issue_date'      => $doc['issue_date'] ?? null,
                                    'expiry_date'     => $doc['expiry_date'] ?? null,
                                    'document_step'   => $doc['step'],
                                    'document_completed' => 1,
                                ]
                            );
                        }
                        updateOrCreate on document_type keeps this a strict "one row per type" —
                        re-uploading the same type replaces it instead of adding a duplicate row.
                    --}}

                    <form id="docForm" action="{{ route('uploadDocument') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- 1. Aadhar Card -->
                        <div class="doc-section-card" data-doc-card>
                            <div class="doc-section-head">
                                <div class="doc-section-num">1</div>
                                <div>
                                    <h2>Aadhar Card</h2>
                                    <p>Government-issued identity proof.</p>
                                </div>
                                <span class="doc-required-tag">Required</span>
                            </div>
                            <div class="doc-section-body">
                                <input type="hidden" name="documents[aadhar][type]" value="aadhar">
                                <input type="hidden" name="documents[aadhar][name]" value="Aadhar Card">
                                <input type="hidden" name="documents[aadhar][step]" value="1">

                                <div class="doc-field">
                                    <label for="aadhar_number">Aadhar number</label>
                                    <input type="text" id="aadhar_number" name="documents[aadhar][number]" maxlength="12" pattern="[0-9]{12}" placeholder="e.g. 1234 5678 9012" value="{{ old('documents.aadhar.number') }}">
                                    @error('documents.aadhar.number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field">
                                    <label for="aadhar_file">Upload file</label>
                                    <label class="doc-file-tile" data-doc-tile>
                                        <input type="file" id="aadhar_file" name="documents[aadhar][file]" accept=".jpg,.jpeg,.png,.pdf" data-doc-input required>
                                        <div class="doc-upload-icon"><i class="bi bi-upload"></i></div>
                                        <div class="doc-tile-label" data-doc-label>Choose a file</div>
                                        <div class="doc-tile-sub">JPG, PNG or PDF · max 3MB</div>
                                    </label>
                                    @error('documents.aadhar.file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. Medical License -->
                        <div class="doc-section-card" data-doc-card>
                            <div class="doc-section-head">
                                <div class="doc-section-num">2</div>
                                <div>
                                    <h2>Medical License</h2>
                                    <p>Your practicing license issued by the medical council.</p>
                                </div>
                                <span class="doc-required-tag">Required</span>
                            </div>
                            <div class="doc-section-body">
                                <input type="hidden" name="documents[license][type]" value="license">
                                <input type="hidden" name="documents[license][name]" value="Medical License">
                                <input type="hidden" name="documents[license][step]" value="2">

                                <div class="doc-field">
                                    <label for="license_number">License number</label>
                                    <input type="text" id="license_number" name="documents[license][number]" placeholder="e.g. MCI-123456" value="{{ old('documents.license.number') }}">
                                    @error('documents.license.number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field">
                                    <label for="license_expiry">Expiry date <span class="doc-opt">(optional)</span></label>
                                    <input type="date" id="license_expiry" name="documents[license][expiry_date]" value="{{ old('documents.license.expiry_date') }}">
                                    @error('documents.license.expiry_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field span2">
                                    <label for="license_file">Upload file</label>
                                    <label class="doc-file-tile" data-doc-tile>
                                        <input type="file" id="license_file" name="documents[license][file]" accept=".jpg,.jpeg,.png,.pdf" data-doc-input required>
                                        <div class="doc-upload-icon"><i class="bi bi-upload"></i></div>
                                        <div class="doc-tile-label" data-doc-label>Choose a file</div>
                                        <div class="doc-tile-sub">JPG, PNG or PDF · max 3MB</div>
                                    </label>
                                    @error('documents.license.file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 3. Clinic Registration -->
                        <div class="doc-section-card" data-doc-card>
                            <div class="doc-section-head">
                                <div class="doc-section-num">3</div>
                                <div>
                                    <h2>Clinic Registration</h2>
                                    <p>Registration certificate of your clinic or practice.</p>
                                </div>
                                <span class="doc-required-tag">Required</span>
                            </div>
                            <div class="doc-section-body">
                                <input type="hidden" name="documents[registration][type]" value="registration">
                                <input type="hidden" name="documents[registration][name]" value="Clinic Registration">
                                <input type="hidden" name="documents[registration][step]" value="3">

                                <div class="doc-field">
                                    <label for="registration_number">Registration number</label>
                                    <input type="text" id="registration_number" name="documents[registration][number]" placeholder="e.g. REG-9988" value="{{ old('documents.registration.number') }}">
                                    @error('documents.registration.number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field">
                                    <label for="registration_date">Issue date <span class="doc-opt">(optional)</span></label>
                                    <input type="date" id="registration_date" name="documents[registration][issue_date]" value="{{ old('documents.registration.issue_date') }}">
                                    @error('documents.registration.issue_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field span2">
                                    <label for="registration_file">Upload file</label>
                                    <label class="doc-file-tile" data-doc-tile>
                                        <input type="file" id="registration_file" name="documents[registration][file]" accept=".jpg,.jpeg,.png,.pdf" data-doc-input required>
                                        <div class="doc-upload-icon"><i class="bi bi-upload"></i></div>
                                        <div class="doc-tile-label" data-doc-label>Choose a file</div>
                                        <div class="doc-tile-sub">JPG, PNG or PDF · max 3MB</div>
                                    </label>
                                    @error('documents.registration.file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 4. Degree Certificate -->
                        <div class="doc-section-card" data-doc-card>
                            <div class="doc-section-head">
                                <div class="doc-section-num">4</div>
                                <div>
                                    <h2>Degree Certificate</h2>
                                    <p>Your medical degree or qualification certificate.</p>
                                </div>
                                <span class="doc-required-tag">Required</span>
                            </div>
                            <div class="doc-section-body">
                                <input type="hidden" name="documents[degree][type]" value="degree">
                                <input type="hidden" name="documents[degree][name]" value="Degree Certificate">
                                <input type="hidden" name="documents[degree][step]" value="4">

                                <div class="doc-field span2">
                                    <label for="degree_name">Degree / qualification <span class="doc-opt">(optional)</span></label>
                                    <input type="text" id="degree_name" name="documents[degree][number]" placeholder="e.g. MBBS, MD" value="{{ old('documents.degree.number') }}">
                                    @error('documents.degree.number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="doc-field span2">
                                    <label for="degree_file">Upload file</label>
                                    <label class="doc-file-tile" data-doc-tile>
                                        <input type="file" id="degree_file" name="documents[degree][file]" accept=".jpg,.jpeg,.png,.pdf" data-doc-input required>
                                        <div class="doc-upload-icon"><i class="bi bi-upload"></i></div>
                                        <div class="doc-tile-label" data-doc-label>Choose a file</div>
                                        <div class="doc-tile-sub">JPG, PNG or PDF · max 3MB</div>
                                    </label>
                                    @error('documents.degree.file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 5. Photo -->
                        <div class="doc-section-card" data-doc-card>
                            <div class="doc-section-head">
                                <div class="doc-section-num">5</div>
                                <div>
                                    <h2>Photo</h2>
                                    <p>A recent passport-size photograph.</p>
                                </div>
                                <span class="doc-required-tag">Required</span>
                            </div>
                            <div class="doc-section-body">
                                <input type="hidden" name="documents[photo][type]" value="photo">
                                <input type="hidden" name="documents[photo][name]" value="Photo">
                                <input type="hidden" name="documents[photo][step]" value="5">

                                <div class="doc-field span2">
                                    <label for="photo_file">Upload file</label>
                                    <label class="doc-file-tile" data-doc-tile>
                                        <input type="file" id="photo_file" name="documents[photo][file]" accept=".jpg,.jpeg,.png" data-doc-input required>
                                        <div class="doc-upload-icon"><i class="bi bi-upload"></i></div>
                                        <div class="doc-tile-label" data-doc-label>Choose a file</div>
                                        <div class="doc-tile-sub">JPG or PNG · max 3MB</div>
                                    </label>
                                    @error('documents.photo.file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="doc-action-bar">
                            <div class="doc-action-hint" id="docActionHint">Upload all 5 documents to continue.</div>
                            <button type="submit" class="btn-doc-submit">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Submit All Documents
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.include.js')
<script>
(function () {
    var inputs = document.querySelectorAll('[data-doc-input]');
    var totalCount = inputs.length;
    var progressText = document.getElementById('docProgressText');
    var actionHint = document.getElementById('docActionHint');

    function updateProgress() {
        var completed = 0;
        inputs.forEach(function (input) {
            if (input.files && input.files.length > 0) completed++;
        });
        progressText.textContent = completed + ' / ' + totalCount + ' completed';
        actionHint.textContent = completed === totalCount
            ? 'All documents ready — click submit to upload.'
            : 'Upload all ' + totalCount + ' documents to continue.';
    }

    inputs.forEach(function (input) {
        input.addEventListener('change', function () {
            var tile = input.closest('[data-doc-tile]');
            var label = tile.querySelector('[data-doc-label]');
            var card = input.closest('[data-doc-card]');

            if (input.files && input.files.length > 0) {
                label.textContent = input.files[0].name;
                tile.classList.add('has-file');
                card.classList.add('is-complete');
            } else {
                label.textContent = 'Choose a file';
                tile.classList.remove('has-file');
                card.classList.remove('is-complete');
            }
            updateProgress();
        });
    });

    updateProgress();
})();
</script>
@endsection
