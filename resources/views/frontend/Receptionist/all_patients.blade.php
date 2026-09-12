@extends('backend.include.layout')

@section('title', 'All Patients — Receptionist Portal')

@section('content')
<div class="page-content wrapper ap-wrapper">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="ap-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="ap-page-title fw-bold mb-1">
                    <i class="bi bi-people-fill text-primary me-2"></i>All Patients
                    @if($doctor)
                        <span class="ap-doctor-tag">Dr. {{ $doctor->name }}</span>
                    @endif
                </h4>
                <p class="text-muted fs-13 mb-0">
                    <span class="text-primary fw-medium">Receptionist Desk</span> &bull; Search, view history, or register for today's visit
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="ap-date-badge px-3 py-2 border rounded-3 bg-white shadow-xs fs-13 fw-medium text-dark">
                    <i class="bi bi-calendar3 me-1.5 text-primary"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </span>
                <a href="{{ route('receptionist.dashboard') }}" class="btn btn-outline-secondary rounded-3 px-3.5 py-2 fs-13 fw-semibold shadow-xs ms-1">
                    <i class="bi bi-speedometer2 me-1.5"></i> Dashboard
                </a>
                <a href="{{ route('receptionist.patients') }}" class="btn btn-primary rounded-3 px-3.5 py-2 fs-13 fw-bold shadow-xs ms-1">
                    <i class="bi bi-person-plus-fill me-1.5"></i> Register New Patient
                </a>
            </div>
        </div>

        {{-- Success / Error Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm rounded-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search + Stats Bar --}}
        <div class="ap-card mb-4">
            <div class="ap-card-body">
                <div class="row align-items-center g-3">
                    <div class="col-md-6 col-lg-5">
                        <form method="GET" action="{{ route('receptionist.all_patients') }}" id="searchForm">
                            <div class="ap-search-wrap">
                                <i class="bi bi-search ap-search-icon"></i>
                                <input
                                    type="text"
                                    name="search"
                                    id="searchInput"
                                    class="form-control ap-search-input"
                                    placeholder="Search by name, mobile, Patient ID..."
                                    value="{{ $search }}"
                                    autocomplete="off"
                                >
                                @if($search)
                                    <a href="{{ route('receptionist.all_patients') }}" class="ap-search-clear" title="Clear">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-lg-7">
                        <div class="d-flex flex-wrap align-items-center gap-3 justify-content-md-end">
                            <div class="ap-stat-chip bg-primary-soft text-primary">
                                <i class="bi bi-people-fill me-1"></i>
                                <strong>{{ $patients->total() }}</strong> <span class="fw-normal">Total Patients</span>
                            </div>
                            <div class="ap-stat-chip bg-success-soft text-success">
                                <i class="bi bi-calendar2-check-fill me-1"></i>
                                <strong>{{ \App\Models\Patient::whereDate('created_at', today())->when($doctor, fn($q) => $q->where('doctor_id', $doctor->id))->count() }}</strong>
                                <span class="fw-normal">Today</span>
                            </div>
                            @if($search)
                                <div class="ap-stat-chip bg-warning-soft text-warning">
                                    <i class="bi bi-funnel-fill me-1"></i>
                                    <strong>{{ $patients->total() }}</strong> <span class="fw-normal">Results for "{{ $search }}"</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Patients Table --}}
        <div class="ap-card">
            <div class="ap-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="ap-card-title mb-0">
                        <i class="bi bi-table me-2 text-primary"></i>Patient Records
                    </h5>
                    <span class="badge bg-primary-soft text-primary rounded-pill fw-semibold fs-12">
                        Page {{ $patients->currentPage() }} of {{ $patients->lastPage() }}
                    </span>
                </div>
                <span class="text-muted fs-12">
                    Showing {{ $patients->firstItem() ?? 0 }}–{{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} entries
                </span>
            </div>

            <div class="ap-card-body p-0">
                @if($patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 ap-table">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width:50px;">#</th>
                                    <th>Patient Info</th>
                                    <th>Patient ID</th>
                                    <th>Contact</th>
                                    <th>Category</th>
                                    <th>Doctor</th>
                                    <th>Last Visit</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $key => $patient)
                                @php
                                    $initials  = strtoupper(substr($patient->patient_name ?? 'P', 0, 2));
                                    $colorMap  = ['bg-primary-soft text-primary','bg-success-soft text-success','bg-info-soft text-info','bg-warning-soft text-warning','bg-danger-soft text-danger','bg-purple-soft text-purple'];
                                    $colorClass = $colorMap[($patients->firstItem() + $key - 1) % count($colorMap)];
                                    $isToday   = $patient->created_at && \Carbon\Carbon::parse($patient->created_at)->isToday();
                                    $hasVisitToday = \App\Models\presciption_data::where('patient_id', $patient->id)->whereDate('created_at', today())->exists();

                                    $lastVisit = $patient->presciption_data()
                                        ->whereDate('created_at', '<', \Carbon\Carbon::today())
                                        ->latest('created_at')
                                        ->value('created_at') ?? $patient->Registration_date ?? $patient->created_at;

                                    $daysSinceLastVisit = $lastVisit ? \Carbon\Carbon::parse($lastVisit)->startOfDay()->diffInDays(now()->startOfDay()) : null;

                                    $isRevisitFree = false;
                                    if (isset($clinic) && $clinic && $clinic->has_revisit_rule && !is_null($daysSinceLastVisit)) {
                                        $isRevisitFree = $clinic->isRevisitFree($lastVisit);
                                    }
                                @endphp
                                <tr class="ap-table-row {{ $isToday ? 'row-today' : '' }}">
                                    <td class="ps-4 fw-semibold text-muted">
                                        {{ $patients->firstItem() + $key }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="ap-avatar {{ $colorClass }}">{{ $initials }}</div>
                                            <div>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $patient->patient_name ?? 'Unknown' }}</h6>
                                                    @if($isRevisitFree)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5 rounded-pill fs-10 fw-semibold" title="Qualifies for Free Follow-up Revisit within {{ $clinic->revisit_validity_days }} days">
                                                            <i class="bi bi-gift-fill me-0.5"></i>Free Revisit
                                                        </span>
                                                    @endif
                                                </div>
                                                <small class="text-muted fs-11">
                                                    {{ ucfirst($patient->gender ?? '-') }}
                                                    &bull;
                                                    {{ $patient->age_year ? $patient->age_year.' Yrs' : ($patient->age_month ? $patient->age_month.' Mos' : '—') }}
                                                    @if(!empty($patient->husband_father_name))
                                                        &bull; {{ $patient->husband_father_name }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ap-id-badge">{{ $patient->patient_id ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @if($patient->mobile)
                                            <span class="fs-13 text-dark">
                                                <i class="bi bi-telephone text-muted me-1 fs-11"></i>{{ $patient->mobile }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->paymentCategory)
                                            <span class="badge bg-info-soft text-info fw-semibold fs-11 px-2 py-1 rounded-pill">
                                                <i class="bi bi-tag-fill me-1"></i>{{ $patient->paymentCategory->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border fs-11">Standard</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-primary fw-medium fs-12">
                                            {{ $patient->doctor ? 'Dr. '.$patient->doctor->name : '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($isToday)
                                            <span class="badge bg-success-soft text-success fw-semibold fs-11">
                                                <i class="bi bi-circle-fill me-1" style="font-size:6px;vertical-align:middle;"></i>Today
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">
                                                {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y') : '—' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasVisitToday)
                                            <span class="badge bg-success-soft text-success fw-semibold fs-11">
                                                <i class="bi bi-check-circle-fill me-1"></i>Registered Today
                                            </span>
                                        @else
                                            <span class="badge bg-warning-soft text-warning fw-semibold fs-11">
                                                <i class="bi bi-hourglass me-1"></i>Not Registered
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1 flex-wrap justify-content-end">

                                            {{-- View Old Prescription (History) --}}
                                            <a href="{{ route('receptionist.viewhistory', $patient->id) }}"
                                               class="btn btn-sm ap-btn-history py-1 px-2"
                                               title="View Patient Medical History & Visits Date-Wise"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-clock-history me-1"></i>History
                                            </a>

                                            {{-- New Visit / Re-register Modal Trigger --}}
                                            @if($hasVisitToday)
                                                <a href="{{ route('staff.physical_exam', $patient->id) }}"
                                                   class="btn btn-sm ap-btn-exam py-1 px-2"
                                                   title="Already registered today — Go to Physical Exam"
                                                   data-bs-toggle="tooltip">
                                                    <i class="bi bi-activity me-1"></i>Physical Exam
                                                </a>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm ap-btn-new-rx py-1 px-2.5 fw-semibold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#revisitModal{{ $patient->id }}"
                                                        title="Register for Today's Visit & Select Category">
                                                    <i class="bi bi-calendar-plus-fill me-1"></i>New Visit
                                                </button>

                                                <!-- Re-Visit Modal -->
                                                <div class="modal fade text-start" id="revisitModal{{ $patient->id }}" tabindex="-1" aria-labelledby="revisitModalLabel{{ $patient->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                            <div class="modal-header bg-primary text-white py-3 px-4">
                                                                <h5 class="modal-title fs-15 fw-bold" id="revisitModalLabel{{ $patient->id }}">
                                                                    <i class="bi bi-calendar-check me-2"></i>Re-Register Patient for Today's Visit
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('receptionist.re_register', $patient->id) }}">
                                                                @csrf
                                                                <div class="modal-body p-4">
                                                                    
                                                                    <!-- Patient Summary Banner -->
                                                                    <div class="p-3 bg-light rounded-3 border mb-3">
                                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                                            <h6 class="fw-bold text-dark mb-0 fs-14">{{ $patient->patient_name }}</h6>
                                                                            <span class="badge bg-primary-subtle text-primary font-monospace fs-12 px-2 py-1 border border-primary-subtle">
                                                                                Reg No: {{ $patient->registration ?? $patient->patient_id }}
                                                                            </span>
                                                                        </div>
                                                                        <small class="text-muted d-block fs-12">
                                                                            {{ ucfirst($patient->gender) }} &bull; {{ $patient->age_year ? $patient->age_year.' Yrs' : 'N/A' }} &bull; <i class="bi bi-telephone me-1 text-success"></i>{{ $patient->mobile ?? 'No Phone' }}
                                                                        </small>
                                                                        <small class="text-success fw-semibold d-block fs-11 mt-1">
                                                                            <i class="bi bi-check-circle-fill me-1"></i> Retains Existing Patient Registration ID
                                                                        </small>
                                                                    </div>

                                                                    <!-- Clinic Revisit Rule Alert Banner -->
                                                                    @if($isRevisitFree)
                                                                        <div class="alert alert-success border-success-subtle d-flex align-items-start gap-2 py-2.5 px-3 mb-3 rounded-3">
                                                                            <i class="bi bi-gift-fill fs-5 text-success mt-0.5"></i>
                                                                            <div>
                                                                                <div class="fw-bold fs-13 text-success">🎉 Free Follow-up Revisit Qualified!</div>
                                                                                <div class="fs-11 text-dark">
                                                                                    Clinic policy offers <strong>Free Follow-up within {{ $clinic->revisit_validity_days }} days</strong>.
                                                                                    Patient last visited <strong>{{ $daysSinceLastVisit }} days ago</strong>. Consultation fee is waived (<strong>₹0.00</strong>).
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @elseif(isset($clinic) && $clinic && $clinic->has_revisit_rule && !is_null($daysSinceLastVisit) && $daysSinceLastVisit > $clinic->revisit_validity_days)
                                                                        <div class="alert alert-warning border-warning-subtle d-flex align-items-start gap-2 py-2.5 px-3 mb-3 rounded-3">
                                                                            <i class="bi bi-clock-history fs-5 text-warning mt-0.5"></i>
                                                                            <div>
                                                                                <div class="fw-bold fs-13 text-dark">Revisit Window Expired</div>
                                                                                <div class="fs-11 text-muted">
                                                                                    Clinic free revisit window is <strong>{{ $clinic->revisit_validity_days }} days</strong>. Last visit was <strong>{{ $daysSinceLastVisit }} days ago</strong>. Standard consultation fee applies.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Payment Category Dropdown -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold text-dark fs-13 mb-1">
                                                                            Select Payment / Fee Category <span class="text-danger">*</span>
                                                                        </label>
                                                                        <select name="payment_category_id" class="form-select border-secondary-subtle py-2 fs-13 rounded-3" required>
                                                                            <option value="" disabled {{ !$patient->payment_category_id ? 'selected' : '' }}>-- Choose Doctor's Fee Category --</option>
                                                                            @if(isset($paymentCategories) && count($paymentCategories) > 0)
                                                                                @foreach($paymentCategories as $category)
                                                                                    <option value="{{ $category->id }}" {{ $patient->payment_category_id == $category->id ? 'selected' : '' }}>
                                                                                        {{ $category->name }} — ₹{{ number_format($category->price, 2) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @else
                                                                                <option value="1">Standard Consultation</option>
                                                                            @endif
                                                                        </select>
                                                                        <small class="text-muted fs-11 mt-1 d-block">
                                                                            Doctor: <strong>Dr. {{ $doctor->name ?? $patient->doctor->name ?? 'Doctor' }}</strong>
                                                                        </small>
                                                                    </div>

                                                                    <div class="alert alert-info py-2 px-3 fs-11 mb-0 rounded-3">
                                                                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Next Step:</strong> Click 'Next' to confirm today's registration &amp; send patient to Staff Queue for Physical Examination.
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer bg-light py-2.5 px-4 border-top">
                                                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold">
                                                                        Next <i class="bi bi-arrow-right me-1"></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($patients->hasPages())
                        <div class="ap-pagination-wrap d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3 border-top">
                            <div class="text-muted fs-13">
                                Showing <strong>{{ $patients->firstItem() }}</strong> to <strong>{{ $patients->lastItem() }}</strong>
                                of <strong>{{ $patients->total() }}</strong> patients
                                @if($search) for "<em>{{ $search }}</em>" @endif
                            </div>
                            <nav aria-label="Patient Pagination">
                                <ul class="pagination ap-pagination mb-0">
                                    {{-- Previous --}}
                                    @if($patients->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $patients->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                                        </li>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @php
                                        $start = max(1, $patients->currentPage() - 2);
                                        $end   = min($patients->lastPage(), $patients->currentPage() + 2);
                                    @endphp
                                    @if($start > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $patients->url(1) }}">1</a></li>
                                        @if($start > 2)<li class="page-item disabled"><span class="page-link">…</span></li>@endif
                                    @endif
                                    @for($p = $start; $p <= $end; $p++)
                                        <li class="page-item {{ $patients->currentPage() == $p ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $patients->url($p) }}">{{ $p }}</a>
                                        </li>
                                    @endfor
                                    @if($end < $patients->lastPage())
                                        @if($end < $patients->lastPage() - 1)<li class="page-item disabled"><span class="page-link">…</span></li>@endif
                                        <li class="page-item"><a class="page-link" href="{{ $patients->url($patients->lastPage()) }}">{{ $patients->lastPage() }}</a></li>
                                    @endif

                                    {{-- Next --}}
                                    @if($patients->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $patients->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5 my-3">
                        <div class="ap-empty-icon bg-primary-soft text-primary mb-3">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                        @if($search)
                            <h5 class="fw-bold text-dark mb-1">No results found</h5>
                            <p class="text-muted fs-13 mb-3">No patients matched "<strong>{{ $search }}</strong>"</p>
                            <a href="{{ route('receptionist.all_patients') }}" class="btn btn-ap-light btn-sm">
                                <i class="bi bi-x-circle me-1"></i> Clear Search
                            </a>
                        @else
                            <h5 class="fw-bold text-dark mb-1">No Patients Found</h5>
                            <p class="text-muted fs-13 mb-3">No patients are registered for this doctor yet.</p>
                            <a href="{{ route('receptionist.patients') }}" class="btn btn-ap-primary btn-sm">
                                <i class="bi bi-person-plus-fill me-1"></i> Register First Patient
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Custom CSS --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --ap-primary: #0162e8;
        --ap-success: #22c03c;
        --ap-warning: #f5b731;
        --ap-danger:  #ee335e;
        --ap-info:    #05c3fb;
        --ap-purple:  #6c5ffc;
        --ap-border:  #e9edf4;
        --ap-muted:   #8c9097;
        --ap-bg:      #f2f4f8;
    }

    body { font-family: 'Inter', sans-serif; }

    .ap-wrapper {
        background: var(--ap-bg);
        min-height: calc(100vh - 70px);
        padding: 24px 0 48px;
    }

    /* Page Header */
    .ap-page-title { font-size: 20px; letter-spacing: -0.3px; color: #282f53; }
    .ap-doctor-tag {
        display: inline-block;
        background: rgba(1,98,232,0.1);
        color: var(--ap-primary);
        border-radius: 6px;
        padding: 2px 10px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
        vertical-align: middle;
    }
    .ap-date-badge {
        background: #fff;
        border: 1px solid var(--ap-border);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        color: #495057;
    }

    /* Buttons */
    .btn-ap-primary {
        background: var(--ap-primary); border-color: var(--ap-primary);
        color: #fff; font-weight: 600; border-radius: 7px; font-size: 13px;
        transition: all .2s;
    }
    .btn-ap-primary:hover { background: #0150c0; color:#fff; box-shadow: 0 4px 12px rgba(1,98,232,.3); }
    .btn-ap-light {
        background: #fff; border: 1px solid var(--ap-border); color: #495057;
        font-weight: 500; border-radius: 7px; font-size: 13px;
    }
    .btn-ap-light:hover { background: #f0f3f8; }

    /* Cards */
    .ap-card {
        background: #fff;
        border: 1px solid var(--ap-border);
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .ap-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--ap-border);
        background: #fff;
    }
    .ap-card-title { font-size: 15px; font-weight: 700; color: #282f53; }
    .ap-card-body { padding: 20px 24px; }

    /* Search */
    .ap-search-wrap { position: relative; }
    .ap-search-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--ap-muted); font-size: 14px; pointer-events: none;
    }
    .ap-search-input {
        padding-left: 36px; padding-right: 36px;
        border-radius: 9px; border: 1.5px solid var(--ap-border);
        font-size: 13px; height: 40px;
        transition: border-color .2s, box-shadow .2s;
    }
    .ap-search-input:focus {
        border-color: var(--ap-primary);
        box-shadow: 0 0 0 3px rgba(1,98,232,.12);
    }
    .ap-search-clear {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        color: var(--ap-muted); font-size: 15px; text-decoration: none;
        transition: color .15s;
    }
    .ap-search-clear:hover { color: var(--ap-danger); }

    /* Stat Chips */
    .ap-stat-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;
    }

    /* Soft colour helpers */
    .bg-primary-soft  { background: rgba(1,98,232,.09)!important; }
    .bg-success-soft  { background: rgba(34,192,60,.09)!important; }
    .bg-warning-soft  { background: rgba(245,183,49,.12)!important; }
    .bg-danger-soft   { background: rgba(238,51,94,.09)!important; }
    .bg-info-soft     { background: rgba(5,195,251,.09)!important; }
    .bg-purple-soft   { background: rgba(108,95,252,.09)!important; }
    .text-primary  { color: var(--ap-primary)!important; }
    .text-success  { color: var(--ap-success)!important; }
    .text-warning  { color: #c99700!important; }
    .text-danger   { color: var(--ap-danger)!important; }
    .text-info     { color: #0493bc!important; }
    .text-purple   { color: var(--ap-purple)!important; }

    /* Table */
    .ap-table thead th {
        background: #f8f9fc;
        font-size: 11px; text-transform: uppercase; font-weight: 700;
        letter-spacing: .5px; color: var(--ap-muted);
        border-bottom: 1px solid var(--ap-border); padding: 12px 14px;
        white-space: nowrap;
    }
    .ap-table tbody td { padding: 13px 14px; border-bottom: 1px solid #f1f4f9; vertical-align: middle; }
    .ap-table tbody tr:hover td { background: #f9fbff; }
    .ap-table tbody tr.row-today td { background: rgba(34,192,60,.03); }

    /* Avatar */
    .ap-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px; flex-shrink: 0;
    }

    /* ID Badge */
    .ap-id-badge {
        display: inline-block;
        background: #f0f3f8;
        border: 1px solid #dde3ed;
        border-radius: 5px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        color: #495057;
    }

    /* Action Buttons */
    .ap-btn-history {
        background: rgba(5,195,251,.1); color: #0493bc;
        border: 1px solid rgba(5,195,251,.25); font-size: 12px;
        font-weight: 600; border-radius: 6px; transition: all .2s;
        white-space: nowrap;
    }
    .ap-btn-history:hover { background: rgba(5,195,251,.18); color: #0493bc; }

    .ap-btn-new-rx {
        background: rgba(34,192,60,.1); color: #1a9e31;
        border: 1px solid rgba(34,192,60,.25); font-size: 12px;
        font-weight: 600; border-radius: 6px; transition: all .2s;
        white-space: nowrap;
    }
    .ap-btn-new-rx:hover { background: rgba(34,192,60,.2); color: #1a9e31; box-shadow: 0 3px 8px rgba(34,192,60,.2); }

    .ap-btn-exam {
        background: rgba(108,95,252,.1); color: var(--ap-purple);
        border: 1px solid rgba(108,95,252,.25); font-size: 12px;
        font-weight: 600; border-radius: 6px; transition: all .2s;
        white-space: nowrap;
    }
    .ap-btn-exam:hover { background: rgba(108,95,252,.18); color: var(--ap-purple); }

    /* Pagination */
    .ap-pagination .page-link {
        border-radius: 7px!important;
        margin: 0 2px;
        border: 1px solid var(--ap-border);
        color: #495057;
        font-size: 13px;
        padding: 6px 12px;
        transition: all .2s;
    }
    .ap-pagination .page-item.active .page-link {
        background: var(--ap-primary);
        border-color: var(--ap-primary);
        color: #fff;
        box-shadow: 0 3px 8px rgba(1,98,232,.3);
    }
    .ap-pagination .page-link:hover { background: #f0f3f8; color: var(--ap-primary); }
    .ap-pagination .page-item.disabled .page-link { opacity: .4; pointer-events: none; }

    /* Empty State */
    .ap-empty-icon {
        width: 72px; height: 72px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
    }

    /* Utility */
    .fs-11 { font-size: 11px!important; }
    .fs-12 { font-size: 12px!important; }
    .fs-13 { font-size: 13px!important; }
</style>

<script>
    // Auto-submit search form on typing (debounced)
    (function () {
        const input = document.getElementById('searchInput');
        const form  = document.getElementById('searchForm');
        if (!input || !form) return;
        let timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { form.submit(); }, 500);
        });
    })();

    // Confirm before new visit registration
    function confirmReRegister(form, name) {
        return confirm('Kya aap "' + name + '" ko aaj ke liye nayi visit ke liye register karna chahte hain?\n\nIsse seedha Physical Examination form par redirect kiya jayega.');
    }

    // Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipEls.forEach(function (el) {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });
    });
</script>
@endsection
