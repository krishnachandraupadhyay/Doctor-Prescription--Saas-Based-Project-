@extends('middleend.include.layout')
@section('title', 'Doctor Profile & Documents - Onboarding Portal')
@section('content')
<style>
    .profile-card{
        background:#fff;
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 10px 30px -10px rgba(15,59,56,0.15);
        border:1px solid #EEF3F1;
    }

    .profile-card-header{
        background:linear-gradient(135deg,#175C55 0%, #0F3B38 100%);
        padding:20px 28px;
        display:flex;
        align-items:center;
        justify-content:space-between;
    }

    .profile-card-header h4{
        color:#fff;
        margin:0;
        font-weight:600;
        letter-spacing:0.02em;
        font-size:1.1rem;
    }

    .header-actions{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .status-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        background:rgba(255,255,255,0.12);
        border:1px solid rgba(255,255,255,0.25);
        color:#fff;
        font-size:12px;
        font-weight:600;
        padding:5px 12px;
        border-radius:20px;
    }

    .status-badge .dot{
        width:6px;
        height:6px;
        border-radius:50%;
        background:#5DCAA5;
    }

    .edit-btn{
        background:#fff;
        color:#0F3B38;
        border:none;
        font-size:12.5px;
        font-weight:600;
        padding:7px 16px;
        border-radius:20px;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:6px;
    }

    .body-wrap{
        padding:28px;
    }

    /* Photo column */
    .photo-col{
        text-align:center;
    }

    .photo{
        border:3px solid #fff;
        outline:1px solid #E3ECE9;
        border-radius:16px;
        height:11.5rem;
        width:11.5rem;
        overflow:hidden;
        display:flex;
        justify-content:center;
        align-items:center;
        margin:0 auto;
        box-shadow:0 4px 14px -4px rgba(0,0,0,0.15);
        background:#F5F9F8;
        position:relative;
    }

    .photo img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .verified-tick{
        position:absolute;
        bottom:6px;
        right:6px;
        width:26px;
        height:26px;
        background:#1D9E75;
        border:2px solid #fff;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        font-size:12px;
    }

    .profile-info{
        margin-top:16px;
    }

    .profile-info h5{
        margin-bottom:4px;
        font-weight:700;
        color:#0E2624;
        font-size:1.1rem;
    }

    .profile-info .spec-line{
        font-size:13.5px;
        color:#6E8783;
        font-weight:500;
        margin-bottom:10px;
    }

    .profile-info .doctor-id{
        display:inline-block;
        background:#EAF5F2;
        color:#0F3B38;
        font-size:12.5px;
        font-weight:600;
        padding:4px 14px;
        border-radius:20px;
    }

    /* Mini stat pills under photo */
    .mini-stats{
        display:flex;
        justify-content:center;
        gap:8px;
        margin-top:16px;
    }

    .mini-stat{
        flex:1;
        max-width:90px;
        background:#FAFCFB;
        border:1px solid #EEF3F1;
        border-radius:10px;
        padding:8px 4px;
    }

    .mini-stat .num{
        font-size:14px;
        font-weight:700;
        color:#0F3B38;
        display:block;
    }

    .mini-stat .txt{
        font-size:10px;
        color:#9FB5B1;
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:0.03em;
    }

    /* Details column */
    .divider-vertical{
        border-left:1px solid #EEF3F1;
        padding-left:28px;
    }

    .detail-block-title{
        font-size:12px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:0.05em;
        color:#175C55;
        margin-bottom:18px;
        display:flex;
        align-items:center;
        gap:8px;
    }

    .detail-block-title::after{
        content:'';
        flex:1;
        height:1px;
        background:#EEF3F1;
    }

    .detail-item{
        display:flex;
        align-items:flex-start;
        gap:12px;
        margin-bottom:18px;
    }

    .detail-item .ic{
        width:36px;
        height:36px;
        border-radius:10px;
        background:#EAF5F2;
        color:#175C55;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:14px;
        flex-shrink:0;
    }

    .detail-item .label{
        display:block;
        font-size:11.5px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:0.04em;
        color:#9FB5B1;
        margin-bottom:2px;
    }

    .detail-item .value{
        font-size:14.5px;
        color:#0E2624;
        font-weight:600;
    }

    .detail-item .value.muted{
        color:#C4D2CF;
        font-weight:400;
        font-style:italic;
    }

    .card-footer{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:14px 28px;
        background:#FAFCFB;
        border-top:1px solid #EEF3F1;
        font-size:12px;
        color:#9FB5B1;
    }

    .card-footer a{
        color:#175C55;
        font-weight:600;
        text-decoration:none;
    }

    @media (max-width: 767px){
        .divider-vertical{ border-left:none; border-top:1px solid #EEF3F1; padding-left:0; padding-top:20px; margin-top:24px; }
        .profile-card-header{ flex-wrap:wrap; gap:10px; }
        .body-wrap{ padding:20px; }
        .mini-stats{ max-width:280px; margin-left:auto; margin-right:auto; }
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="profile-card">

                    <div class="profile-card-header">
                        <h4>Doctor's profile</h4>
                        <div class="header-actions">
                            <span class="status-badge">
                                <span class="dot"></span>
                                Active
                            </span>
                            <a href="{{ route('doctor.onboarding.edit', $doctor->id) }}" class="edit-btn">
                                <i class="fa fa-pen"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="body-wrap">
                        <div class="row align-items-start">

                            <!-- Photo + Name -->
                            <div class="col-sm-12 col-md-4 photo-col">
                                <div class="photo">
                                    @php
                                        // $doctor is the doctor whose profile was requested via URL (e.g. /onboarding.view.doctor/1)
                                        $clinicDoc = \App\Models\doctor_clinic_document::where('doctor_id', $doctor->id)->first();
                                        $clinicPhoto = $clinicDoc->photo ?? null;
                                    @endphp
                                    <img src="{{ $clinicPhoto
                                        ? asset($clinicPhoto)
                                        : ($doctor->image
                                            ? asset('public/'.$doctor->image)
                                            : asset('assets/images/default-avatar.png')) }}"
                                         alt="Doctor">
                                    <span class="verified-tick"><i class="fa fa-check"></i></span>
                                </div>

                                <div class="profile-info">
                                    <h5>{{ $doctor->name }}</h5>
                                    <div class="spec-line">{{ $doctor->specialization ?: 'Specialisation not set' }}</div>
                                    <span class="doctor-id">ID: {{ $doctor->Doctor_Emp_id }}</span>
                                </div>

                                <div class="mini-stats">
                                    <div class="mini-stat">
                                        <span class="num">{{ $doctor->Experience ?: '—' }}</span>
                                        <span class="txt">Exp</span>
                                    </div>
                                    <div class="mini-stat">
                                        <span class="num">{{ isset($patientCount) ? $patientCount : '—' }}</span>
                                        <span class="txt">Patients</span>
                                    </div>
                                    <div class="mini-stat">
                                        <span class="num">{{ isset($rating) ? $rating : '—' }}</span>
                                        <span class="txt">Rating</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="col-sm-12 col-md-8 divider-vertical">
                                <div class="detail-block-title">Clinical information</div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-hospital"></i></span>
                                            <div>
                                                <span class="label">Clinic name</span>
                                                <span class="value {{ $doctor->clinic_name ? '' : 'muted' }}">
                                                    {{ $doctor->clinic_name ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-graduation-cap"></i></span>
                                            <div>
                                                <span class="label">Qualification</span>
                                                <span class="value {{ $doctor->qualification ? '' : 'muted' }}">
                                                    {{ $doctor->qualification ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-map-marker-alt"></i></span>
                                            <div>
                                                <span class="label">Clinic address</span>
                                                <span class="value {{ $doctor->clinic_address ? '' : 'muted' }}">
                                                    {{ $doctor->clinic_address ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-phone"></i></span>
                                            <div>
                                                <span class="label">Phone</span>
                                                <span class="value {{ $doctor->phone ? '' : 'muted' }}">
                                                    {{ $doctor->phone ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-briefcase"></i></span>
                                            <div>
                                                <span class="label">Experience</span>
                                                <span class="value {{ $doctor->Experience ? '' : 'muted' }}">
                                                    {{ $doctor->Experience ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <span class="ic"><i class="fa fa-stethoscope"></i></span>
                                            <div>
                                                <span class="label">Specialisation</span>
                                                <span class="value {{ $doctor->specialization ? '' : 'muted' }}">
                                                    {{ $doctor->specialization ?: 'Not added' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $memberList = isset($members) ? $members : collect();
                                    $receptCount = $memberList->where('role', 'receptionist')->count();
                                    $staffMemberCount = $memberList->where('role', 'staff')->count();
                                @endphp

                                <!-- Staff & Receptionist Team Section -->
                                <div class="detail-block-title mt-4">Clinic Team (Staff & Receptionists)</div>
                                @if(count($memberList) > 0)
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size: 13px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40px;">#</th>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Member ID</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($memberList as $idx => $mem)
                                                    @php
                                                        $isRecept = strtolower($mem->role) === 'receptionist';
                                                        $isStf = strtolower($mem->role) === 'staff';
                                                    @endphp
                                                    <tr>
                                                        <td class="text-muted fw-semibold">{{ $idx + 1 }}</td>
                                                        <td class="fw-bold text-dark">{{ $mem->name }}</td>
                                                        <td>
                                                            @if($isRecept)
                                                                <span class="badge" style="background:#EDE9FE; color:#6D28D9; border:1px solid #DDD6FE; border-radius:12px; padding:4px 10px; font-size:11px;">
                                                                    <i class="fa fa-user-tie me-1"></i> Receptionist
                                                                </span>
                                                            @elseif($isStf)
                                                                <span class="badge" style="background:#E0F2FE; color:#0369A1; border:1px solid #BAE6FD; border-radius:12px; padding:4px 10px; font-size:11px;">
                                                                    <i class="fa fa-user-nurse me-1"></i> Staff
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary" style="border-radius:12px; padding:4px 10px; font-size:11px;">
                                                                    {{ ucfirst($mem->role) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td><code class="text-dark">{{ $mem->member_id }}</code></td>
                                                        <td>{{ $mem->email ?? '—' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted fs-12 mb-0 fst-italic">No staff or receptionist registered under this doctor yet.</p>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <span>Doctor ID: {{ $doctor->Doctor_Emp_id }}</span>
                        <span>Total Clinic Team: {{ count($memberList) }} Members ({{ $receptCount }} Receptionist, {{ $staffMemberCount }} Staff)</span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Doctor Documents Table Section -->
        <div class="row mt-4">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 px-4 border-bottom">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-file-earmark-pdf me-2 text-primary"></i> Doctor Documents &amp; Assets
                            </h5>
                            <small class="text-muted">Total {{ isset($documentsList) ? count($documentsList) : 0 }} uploaded documents &amp; clinic branding files</small>
                        </div>
                        <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-12">
                            Total: {{ isset($documentsList) ? count($documentsList) : 0 }} Files
                        </span>
                    </div>

                    <div class="card-body p-0">
                        @if(isset($documentsList) && count($documentsList) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 border-0">
                                    <thead class="bg-light border-bottom text-uppercase text-muted fs-11 fw-bold">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 50px;">#</th>
                                            <th class="py-3">Document Title</th>
                                            <th class="py-3">Category / Type</th>
                                            <th class="py-3">Doc / Reg. Number</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="pe-4 py-3 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 fs-13">
                                        @foreach($documentsList as $index => $doc)
                                            @php
                                                $ext = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION));
                                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                            @endphp
                                            <tr>
                                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        @if($isImg)
                                                            <img src="{{ asset($doc['file']) }}"
                                                                 alt="{{ $doc['title'] }}"
                                                                 class="rounded-3 border shadow-xs object-fit-cover"
                                                                 style="cursor: pointer;"
                                                                 width="38" height="38"
                                                                 onclick="previewDocument('{{ addslashes($doc['title']) }}', '{{ asset($doc['file']) }}', true)"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="bg-primary-subtle text-primary rounded-3 align-items-center justify-content-center" style="display:none; width: 38px; height: 38px;">
                                                                <i class="bi bi-file-earmark-image fs-5"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                                <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <span class="fw-bold text-dark d-block fs-13">{{ $doc['title'] }}</span>
                                                            <small class="text-muted fs-11"><i class="bi bi-paperclip me-1"></i>{{ basename($doc['file']) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary-subtle text-dark border fs-11 px-2.5 py-1 rounded-pill">
                                                        {{ $doc['type'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-secondary font-monospace fs-12">
                                                        {{ $doc['number'] ?: 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($doc['status'] == 'Uploaded' || $doc['status'] == 'Completed')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                            <i class="bi bi-check-circle-fill me-1"></i> {{ $doc['status'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-11 fw-semibold">
                                                            <i class="bi bi-clock-history me-1"></i> {{ $doc['status'] }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 fw-bold shadow-xs text-white"
                                                            onclick="previewDocument('{{ addslashes($doc['title']) }}', '{{ asset($doc['file']) }}', {{ $isImg ? 'true' : 'false' }})">
                                                        <i class="bi bi-arrows-angle-expand me-1.5"></i> View File
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-folder-x text-muted display-4 d-block mb-2"></i>
                                <h6 class="text-muted fw-bold">No Documents Uploaded Yet</h6>
                                <p class="text-muted fs-12 mb-0">This doctor has not uploaded any official documents or clinic branding files.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Document Large Preview Modal -->
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text fs-4 text-info"></i>
                    <div>
                        <h5 class="modal-title fs-15 fw-bold text-white mb-0" id="docPreviewModalLabel">Document Large Preview</h5>
                        <small class="text-white-50 fs-11" id="docPreviewSubTitle">Document Viewer</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="docPreviewDownloadBtn" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3 fs-12 fw-semibold">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Full Screen / New Tab
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-3 bg-secondary-subtle text-center d-flex align-items-center justify-content-center" style="min-height: 520px; max-height: 82vh; overflow-y: auto;">
                <img id="docPreviewImg" src="" alt="Document Preview" class="img-fluid rounded-3 shadow" style="max-height: 76vh; width: auto; object-fit: contain; display: none;">
                <iframe id="docPreviewIframe" src="" style="width: 100%; height: 76vh; border: none; border-radius: 8px; display: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function previewDocument(title, url, isImg) {
        document.getElementById('docPreviewModalLabel').innerText = title;
        document.getElementById('docPreviewSubTitle').innerText = url.split('/').pop();
        document.getElementById('docPreviewDownloadBtn').setAttribute('href', url);

        const imgEl = document.getElementById('docPreviewImg');
        const iframeEl = document.getElementById('docPreviewIframe');

        if (isImg) {
            imgEl.src = url;
            imgEl.style.display = 'inline-block';
            iframeEl.style.display = 'none';
            iframeEl.src = '';
        } else {
            iframeEl.src = url;
            iframeEl.style.display = 'block';
            imgEl.style.display = 'none';
            imgEl.src = '';
        }

        const modalEl = document.getElementById('docPreviewModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endsection