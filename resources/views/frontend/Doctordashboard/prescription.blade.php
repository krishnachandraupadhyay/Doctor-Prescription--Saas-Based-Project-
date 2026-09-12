@extends("frontend.include.layout")
@section('title', 'Patient Prescription - Doctor Portal')
@section('content')
<style>
    /* ===== A4 PAGE SETUP ===== */
    @page {
        size: A4 portrait;
        margin: 0;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0mm !important;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            background-color: #ffffff !important;
            width: 100% !important;
            height: auto !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #layout-wrapper, .main-content, .page-content, .container-fluid, .row, .col-sm-12, .col-md-8, .card, .prescription {
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        body * {
            visibility: hidden;
        }

        #printableArea, #printableArea * {
            visibility: visible;
        }

        #printableArea {
            position: static !important;
            width: 210mm !important;
            max-width: 210mm !important;
            min-width: 210mm !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            background: #ffffff !important;
            display: block !important;
            height: auto !important;
            min-height: 100% !important;
            overflow: visible !important;
        }

        .print-repeat-table {
            width: 210mm !important;
            max-width: 210mm !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
            padding: 0 !important;
            display: table !important;
        }

        .print-repeat-table > thead {
            display: table-header-group !important;
        }

        .print-repeat-table > tfoot {
            display: table-footer-group !important;
        }

        .print-repeat-table > tbody {
            display: table-row-group !important;
        }

        .print-repeat-table > thead > tr > td,
        .print-repeat-table > tfoot > tr > td,
        .print-repeat-table > tbody > tr > td {
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            vertical-align: top !important;
        }

        .prescription_header,
        .layout2-header {
            width: 100% !important;
            display: block !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .prescription_footer,
        .layout2-footer-wrap {
            width: 100% !important;
            display: block !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        /* ===== HEADING & SUBHEADING TOGETHER ON SAME PAGE ===== */
        h1, h2, h3, h4, h5, h6 {
            break-after: avoid !important;
            page-break-after: avoid !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            orphans: 3 !important;
            widows: 3 !important;
        }

        .section-block {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            display: block !important;
            margin-top: 16px !important;
            margin-bottom: 6px !important;
        }

        .section-block h5 {
            break-after: avoid !important;
            page-break-after: avoid !important;
            margin-bottom: 8px !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .section-block ul li {
            margin-bottom: 4px !important;
        }

        .section-block ul,
        .section-block p,
        .section-block .table {
            break-before: avoid !important;
            page-break-before: avoid !important;
        }

        .doctor-header-block,
        .patient,
        .layout2-footer {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .table, table {
            break-inside: auto !important;
            page-break-inside: auto !important;
        }

        table tr, table td, table th {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .no-print, .app-menu, header.topbar, footer.footer, .footer, .vertical-overlay, .customizer-setting, .live-preview, #back-to-top {
            display: none !important;
        }
    }

    .card {
        padding: 2rem;
        border-radius: 1rem;
    }

    .prescription .document {
        width: 210mm;
        min-height: 297mm;
        position: relative;
        margin: auto;
        background: #fff;
        box-sizing: border-box;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }

    .print-repeat-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .print-repeat-table > thead {
        display: table-header-group;
    }

    .print-repeat-table > tfoot {
        display: table-footer-group;
    }

    .print-repeat-table > tbody {
        display: table-row-group;
    }

    .print-repeat-table > thead > tr > td,
    .print-repeat-table > tfoot > tr > td,
    .print-repeat-table > tbody > tr > td {
        border: none;
        padding: 0;
        vertical-align: top;
    }

    /* Layout 1 Header & Footer styles */
    .prescription_header {
        padding: 0 !important;
        margin: 0 !important;
        line-height: 0;
    }

    .prescription_header img {
        width: 100% !important;
        max-width: 100% !important;
        max-height: 130px;
        object-fit: cover;
        display: block;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }

    .prescription_header .line {
        margin: 8px 0 0;
        width: 100%;
        height: 2px;
        background: #1d4ed8;
    }

    .prescription_footer {
        padding: 0 0 6px 0 !important;
        margin: 0 !important;
        line-height: 0;
    }

    .prescription_footer .line {
        margin: 0 0 8px;
        width: 100%;
        height: 2px;
        background: #1d4ed8;
    }

    .prescription_footer img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        max-height: 90px !important;
        object-fit: contain !important;
        display: block;
        margin: 0 !important;
        padding: 0 0 2px 0 !important;
        border: none !important;
    }

    /* Layout 2 Header styles */
    .layout2-header {
        padding: 25px 35px 0 35px;
        box-sizing: border-box;
    }

    .header-layout2-row {
        display: flex;
        align-items: center;
        gap: 20px;
        width: 100%;
    }

    .header-clinic-logo {
        width: 115px;
        height: 115px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dbeafe;
        padding: 3px;
        background: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .header-logo-placeholder {
        width: 115px;
        height: 115px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 54px;
        border: 2px solid #dbeafe;
        flex-shrink: 0;
    }

    .header-clinic-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .clinic_name {
        font-size: 42px;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
        color: #0b318f;
        line-height: 1.12;
        margin: 0 0 6px 0;
        letter-spacing: -0.01em;
    }

    .header-clinic-subline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 20px;
    }

    .header-clinic-contacts {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-clinic-address-box {
        text-align: right;
        display: inline-flex;
        justify-content: flex-end;
        align-items: center;
        margin-left: auto;
    }

    .clinic_phone, .clinic_email, .clinic_address {
        font-size: 16.5px;
        color: #334155;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .clinic_address {
        text-align: right;
        line-height: 1.4;
    }

    .clinic_phone i, .clinic_email i, .clinic_address i {
        color: #2563eb;
        font-size: 17px;
    }

    .line {
        height: 3px;
        background: #1d4ed8;
        margin: 12px 0 10px 0;
        width: 100%;
    }

    /* Common Content Body styles */
    .content-body {
        padding: 10px 35px 20px 35px;
        box-sizing: border-box;
    }

    .doctor-header-block {
        display: block;
        margin-bottom: 6px;
    }

    .doctor_name {
        font-size: 16px;
        font-weight: 600;
    }

    .date {
        float: right;
    }

    .patient {
        margin-top: 10px;
        border: 1px solid #999;
        background: #f8fafc;
        padding: 10px 12px;
        border-radius: 8px;
    }

    .patient_id {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .patient_detail {
        display: flex;
        justify-content: space-between;
    }

    .section-block {
        margin-top: 18px;
        display: block;
    }

    .section-block h5 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .section-block ul {
        margin-bottom: 0;
        padding-left: 22px;
    }

    .section-block ul li {
        margin-bottom: 4px;
        line-height: 1.5;
        color: #334155;
    }

    .section-block p {
        margin-bottom: 0;
        line-height: 1.5;
        color: #334155;
    }

    .table {
        background: white;
        margin-top: 4px;
        margin-bottom: 0;
    }

    .table th {
        background: #2563eb;
        color: white;
    }

    /* Layout 2 Footer styles */
    .layout2-footer-wrap {
        width: 100%;
        padding: 10px 35px 25px 35px;
        box-sizing: border-box;
        display: block;
    }

    .layout2-footer {
        width: 100% !important;
    }

    .layout2-footer .layout2-footer-content {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: flex-end !important;
        width: 100% !important;
        margin-top: 10px !important;
        box-sizing: border-box !important;
    }

    .layout2-footer .layout2-doctor-signature {
        width: 200px !important;
        max-width: 42% !important;
        text-align: center !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-end !important;
        align-items: center !important;
    }

    .layout2-footer .layout2-doctor-stamp {
        width: 200px !important;
        max-width: 42% !important;
        text-align: center !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-end !important;
        align-items: center !important;
        margin-left: auto !important;
    }

    .layout2-sign-slot,
    .layout2-stamp-slot {
        height: 75px !important;
        width: 100% !important;
        display: flex !important;
        align-items: flex-end !important;
        justify-content: center !important;
        margin-bottom: 6px !important;
    }

    .layout2-sign-img,
    .layout2-stamp-img {
        max-height: 75px !important;
        max-width: 150px !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }

    .layout2-footer-label {
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        border-top: 1.5px solid #64748b !important;
        padding-top: 6px !important;
        width: 100% !important;
        text-align: center !important;
        margin: 0 !important;
        display: block !important;
    }
</style>

@php
    $doctor = Auth::guard('doctor')->user();
    $symptomItems = collect($presciption)
        ->pluck('symptoms')
        ->filter()
        ->flatMap(function ($value) {
            return array_filter(array_map('trim', explode(',', $value)));
        })
        ->unique()
        ->values();

    $testItems = collect($presciption)
        ->pluck('diagnosis_test')
        ->filter()
        ->flatMap(function ($value) {
            return array_filter(array_map('trim', explode(',', $value)));
        })
        ->unique()
        ->values();

    $followupItems = collect($presciption)
        ->pluck('followup')
        ->filter()
        ->flatMap(function ($value) {
            return array_filter(array_map('trim', explode(',', $value)));
        })
        ->unique()
        ->values();

    $adviceItems = collect($presciption)
        ->pluck('advice')
        ->filter()
        ->flatMap(function ($value) use ($suggestion) {
            $parts = array_filter(array_map('trim', explode(',', $value)));
            return array_map(function ($part) use ($suggestion) {
                if (is_numeric($part)) {
                    return $suggestion->where('id', (int)$part)->value('suggestion_name') ?? $part;
                }
                return $part;
            }, $parts);
        })
        ->unique()
        ->values();

    $nextVisitDate = collect($presciption)->pluck('next_visit_date')->filter()->first();
@endphp

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">

                            @if($doctor->prescription_type === 'fixed')

                                {{-- ===================== LAYOUT 1 (FIXED) ===================== --}}
                                <div class="prescription" id="layout1Box">

                                    <div class="live-preview">
                                        <h4>Live Preview 1</h4>
                                    </div>

                                    <div id="printableArea" class="document">
                                        @php
                                          $currentDoctor = Auth::guard('doctor')->user();
                                          $clinicDoc = \App\Models\doctor_clinic_document::where('doctor_id', Auth::guard('doctor')->id())
                                              ->when($currentDoctor && $currentDoctor->clinic_id, function($q) use ($currentDoctor) {
                                                  $q->orWhere('clinic_id', $currentDoctor->clinic_id);
                                              })->first();
                                          $headerSrc = $clinicDoc && $clinicDoc->header ? asset($clinicDoc->header) : null;
                                          $footerSrc = $clinicDoc && $clinicDoc->footer ? asset($clinicDoc->footer) : null;
                                        @endphp
                                        
                                        <table class="print-repeat-table">
                                            {{-- Repeating Header on Every Page --}}
                                            <thead>
                                                <tr>
                                                    <td>
                                                        @if($headerSrc)
                                                            <div class="prescription_header">
                                                                <img src="{{ $headerSrc }}" onerror="this.onerror=null;this.style.display='none';if(this.nextElementSibling)this.nextElementSibling.style.display='none';">
                                                                <div class="line"></div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </thead>

                                            {{-- Repeating Footer on Every Page --}}
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        @if($footerSrc)
                                                            <div class="prescription_footer">
                                                                <div class="line"></div>
                                                                <img src="{{ $footerSrc }}" onerror="this.onerror=null;this.style.display='none';if(this.previousElementSibling)this.previousElementSibling.style.display='none';">
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tfoot>

                                            {{-- Flowing Middle Content --}}
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="content-body">

                                                            <div class="doctor-header-block">
                                                                <div class="doctor_name">
                                                                    Dr. {{ $doctor->name }}
                                                                    ({{ $doctor->qualification }})

                                                                    <span class="date">
                                                                        Date : {{ date('d-m-Y') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="patient">

                                                                <div class="patient_id">
                                                                    <div>
                                                                        Registration :
                                                                        {{ $patient->registration }}
                                                                    </div>

                                                                    <div>
                                                                        Patient Id :
                                                                        {{ $patient->patient_id }}
                                                                    </div>
                                                                </div>

                                                                <div class="patient_detail">

                                                                    <div>
                                                                        Name :
                                                                        {{ $patient->patient_name }}
                                                                    </div>

                                                                    <div>
                                                                        Age :
                                                                        {{ $patient->age_year ?? $patient->age }}
                                                                    </div>

                                                                    <div>
                                                                        Gender :
                                                                        {{ $patient->gender }}
                                                                    </div>

                                                                    @php
                                                                        $payCat = optional($presciption->first())->paymentCategory ?? $patient->paymentCategory;
                                                                    @endphp
                                                                    @if($payCat)
                                                                    <div>
                                                                        Category :
                                                                        {{ $payCat->name }}
                                                                    </div>
                                                                    <div>
                                                                        Consultancy Fee :
                                                                        ₹{{ number_format($payCat->price ?? 0, 2) }}
                                                                    </div>
                                                                    @endif

                                                                </div>

                                                            </div>
                                                            

                                                            <div class="patient">

                                                                <div class="patient_id">
                                                                    <div>
                                                                        Weight :
                                                                       {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('weight') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Height :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('height') ?? '-' }}
                                                                    </div>
                                                                    <div>
                                                                        Pulse Rate :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('pulse_rate') ?? '-' }}
                                                                    </div>
                                                                </div>

                                                                <div class="patient_detail">

                                                                    <div>
                                                                        Blood Pressure:
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('blood_pressure') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Temperature :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('temperature') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Blood Group :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('blood_groups') ?? '-' }}
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            @if($symptomItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Symptoms</h5>
                                                                    <ul>
                                                                        @foreach($symptomItems as $symptom)
                                                                            <li>{{ $symptom }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            <div class="section-block">
                                                                <h5>Diagnosis</h5>
                                                                <p>{{ optional($presciption->first())->diagnosis ?? 'N/A' }}</p>
                                                            </div>

                                                            <div class="section-block">
                                                                <h5>Medicine</h5>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Medicine</th>
                                                                            <th>Dosage</th>
                                                                            <th>Timing</th>
                                                                            <th>Days</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($presciption as $prescrip)
                                                                            @if($prescrip->medicine_id)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $medicine->where('id', $prescrip->medicine_id)->value('medicine_name') ?? $prescrip->medicine_id }}</td>
                                                                                <td>{{ $dosage->where('id', $prescrip->dosage)->value('dosage_name') ?? $prescrip->dosage }} {{ $unit->where('id', $prescrip->unit)->value('unit_name') ?? $prescrip->unit }}</td>
                                                                                <td>{{ $interval->where('id', $prescrip->frequency)->value('interval_name') ?? $prescrip->frequency }}</td>
                                                                                <td>{{ $duration->where('id', $prescrip->duration)->value('duration_name') ?? $prescrip->duration }}</td>
                                                                            </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @if($testItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Test</h5>
                                                                    <ul>
                                                                        @foreach($testItems as $test)
                                                                            <li>{{ $test }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            @if($adviceItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Advice</h5>
                                                                    <ul>
                                                                        @foreach($adviceItems as $adv)
                                                                            <li>{{ $adv }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            @if($followupItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Follow-Up</h5>
                                                                    <ul>
                                                                        @foreach($followupItems as $follow)
                                                                            <li>{{ $follow }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            @if(!empty($nextVisitDate))
                                                                <div class="section-block">
                                                                    <h5>Next Visiting Schedule</h5>
                                                                    <p style="font-size: 14px; font-weight: 600; color: #1e40af;">
                                                                        <i class="bi bi-calendar2-check me-1"></i> Next Visit Date: {{ \Carbon\Carbon::parse($nextVisitDate)->format('d-m-Y') }}
                                                                        <span class="text-muted fw-normal" style="font-size: 13px;">({{ \Carbon\Carbon::parse($nextVisitDate)->diffForHumans() }})</span>
                                                                    </p>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- /#printableArea -->

                                    {{-- Print Button --}}
                                    <div class="text-center mt-3 no-print">
                                        <button type="button" onclick="window.print()" class="btn btn-primary">
                                            <i class="fas fa-print"></i> Print Prescription
                                        </button>
                                    </div>

                                </div>
                                <!-- /.prescription layout1Box -->

                            @else

                                {{-- ===================== LAYOUT 2 (CUSTOMIZE) ===================== --}}
                                <div class="prescription" id="layout2Box">

                                    <div class="live-preview">
                                        <h4>Live Preview 2</h4>
                                    </div>

                                    <div id="printableArea" class="document">

                                        @php
                                            $currentDoctor2 = Auth::guard('doctor')->user();
                                            $clinic2 = $currentDoctor2 && $currentDoctor2->clinic_id ? \App\Models\Clinic::find($currentDoctor2->clinic_id) : null;
                                            $clinicDoc2 = \App\Models\doctor_clinic_document::where('doctor_id', Auth::guard('doctor')->id())
                                                ->when($currentDoctor2 && $currentDoctor2->clinic_id, function($q) use ($currentDoctor2) {
                                                    $q->orWhere('clinic_id', $currentDoctor2->clinic_id);
                                                })->first();
                                            $logoSrc2  = $clinicDoc2 && $clinicDoc2->photo && file_exists(public_path($clinicDoc2->photo))
                                                ? asset($clinicDoc2->photo)
                                                : ($doctor->logo && file_exists(public_path($doctor->logo)) ? asset($doctor->logo) : null);
                                            $signSrc2  = $clinicDoc2 && $clinicDoc2->doctor_sign ? asset($clinicDoc2->doctor_sign) : null;
                                            $stampSrc2 = $clinicDoc2 && $clinicDoc2->clinic_stamp ? asset($clinicDoc2->clinic_stamp) : null;

                                            $clinicName2    = $clinic2->name ?? $doctor->clinic_name ?? 'Hospital / Clinic';
                                            $clinicPhone2   = $clinic2->phone ?? $doctor->phone ?? '';
                                            $clinicEmail2   = $clinic2->email ?? $doctor->email ?? '';
                                            $clinicAddress2 = $clinic2->address ?? $doctor->clinic_address ?? '';
                                        @endphp

                                        <table class="print-repeat-table">
                                            {{-- Repeating Header on Every Page --}}
                                            <thead>
                                                <tr>
                                                    <td>
                                                        <div class="layout2-header">
                                                            <div class="header-layout2-row">
                                                                {{-- Left: Logo --}}
                                                                @if($logoSrc2)
                                                                    <img src="{{ $logoSrc2 }}" alt="Clinic Logo" class="header-clinic-logo">
                                                                @else
                                                                    <div class="header-logo-placeholder">
                                                                        <i class="bi bi-hospital-fill"></i>
                                                                    </div>
                                                                @endif

                                                                {{-- Right: Hospital Name on top, and Phone/Email + Address on same horizontal baseline below --}}
                                                                <div class="header-clinic-info">
                                                                    <div class="clinic_name">{{ $clinicName2 }}</div>

                                                                    <div class="header-clinic-subline">
                                                                        <div class="header-clinic-contacts">
                                                                            @if(!empty($clinicPhone2))
                                                                            <span class="clinic_phone">
                                                                                <i class="bi bi-telephone-fill"></i> {{ $clinicPhone2 }}
                                                                            </span>
                                                                            @endif

                                                                            @if(!empty($clinicEmail2))
                                                                            <span class="clinic_email">
                                                                                <i class="bi bi-envelope-fill"></i> {{ $clinicEmail2 }}
                                                                            </span>
                                                                            @endif
                                                                        </div>

                                                                        @if(!empty($clinicAddress2))
                                                                        <div class="header-clinic-address-box">
                                                                            <span class="clinic_address">
                                                                                <i class="bi bi-geo-alt-fill"></i> {{ $clinicAddress2 }}
                                                                            </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="line"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </thead>

                                            {{-- Repeating Footer on Every Page --}}
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <div class="layout2-footer-wrap">
                                                            <div class="layout2-footer">
                                                                <div class="layout2-footer-content">
                                                                    <div class="layout2-doctor-signature">
                                                                        <div class="layout2-sign-slot">
                                                                            @if($signSrc2)
                                                                                <img src="{{ $signSrc2 }}" class="layout2-sign-img">
                                                                            @endif
                                                                        </div>
                                                                        <div class="layout2-footer-label">Doctor's Signature</div>
                                                                    </div>
                                                                    <div class="layout2-doctor-stamp">
                                                                        <div class="layout2-stamp-slot">
                                                                            @if($stampSrc2)
                                                                                <img src="{{ $stampSrc2 }}" class="layout2-stamp-img">
                                                                            @endif
                                                                        </div>
                                                                        <div class="layout2-footer-label">Clinic Stamp</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>

                                            {{-- Flowing Middle Content --}}
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="content-body">

                                                            <div class="doctor-header-block">
                                                                <div class="doctor_name">
                                                                    Dr. {{ $doctor->name }}
                                                                    ({{ $doctor->qualification }})

                                                                    <span class="date">
                                                                        Date : {{ date('d-m-Y') }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="patient">

                                                                <div class="patient_id">
                                                                    <div>
                                                                        Registration :
                                                                        {{ $patient->registration }}
                                                                    </div>

                                                                    <div>
                                                                        Patient Id :
                                                                        {{ $patient->patient_id }}
                                                                    </div>
                                                                </div>

                                                                <div class="patient_detail">

                                                                    <div>
                                                                        Name :
                                                                        {{ $patient->patient_name }}
                                                                    </div>

                                                                    <div>
                                                                        Age :
                                                                        {{ $patient->age_year ?? $patient->age }}
                                                                    </div>

                                                                    <div>
                                                                        Gender :
                                                                        {{ $patient->gender }}
                                                                    </div>

                                                                    @php
                                                                        $payCat2 = optional($presciption->first())->paymentCategory ?? $patient->paymentCategory;
                                                                    @endphp
                                                                    @if($payCat2)
                                                                    <div>
                                                                        Category :
                                                                        {{ $payCat2->name }}
                                                                    </div>
                                                                    <div>
                                                                        Consultancy Fee :
                                                                        ₹{{ number_format($payCat2->price ?? 0, 2) }}
                                                                    </div>
                                                                    @endif

                                                                </div>

                                                            </div>
                                                            

                                                            <div class="patient">

                                                                <div class="patient_id">
                                                                    <div>
                                                                        Weight :
                                                                       {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('weight') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Height :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('height') ?? '-' }}
                                                                    </div>
                                                                    <div>
                                                                        Pulse Rate :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('pulse_rate') ?? '-' }}
                                                                    </div>
                                                                </div>

                                                                <div class="patient_detail">

                                                                    <div>
                                                                        Blood Pressure:
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('blood_pressure') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Temperature :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('temperature') ?? '-' }}
                                                                    </div>

                                                                    <div>
                                                                        Blood Group :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('blood_groups') ?? '-' }}
                                                                    </div>

                                                                </div><br>
                                                                <div class="patient_detail">
                                                                    <div>
                                                                        SPO2 :
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('spo2') ?? '-' }}
                                                                    </div>
                                                                    <div>
                                                                        Sugar:
                                                                        {{ \Illuminate\Support\Facades\DB::table('presciption_data')
                                                                            ->where('patient_id', $patient->id)
                                                                            ->value('sugar') ?? '-' }}
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            @if($symptomItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Symptoms</h5>
                                                                    <ul>
                                                                        @foreach($symptomItems as $symptom)
                                                                            <li>{{ $symptom }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            <div class="section-block">
                                                                <h5>Diagnosis</h5>
                                                                @if($testItems->isNotEmpty())
                                                                    <p><strong>Tests:</strong> {{ $testItems->implode(', ') }}</p>
                                                                @else
                                                                    <p>{{ optional($presciption->first())->diagnosis ?? 'N/A' }}</p>
                                                                @endif
                                                            </div>

                                                            <div class="section-block">
                                                                <h5>Medicine</h5>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Medicine Name</th>
                                                                            <th>Dosage</th>
                                                                            <th>Duration (Day)</th>
                                                                            <th>Dose Interval</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($presciption as $data)
                                                                            @if($data->medicine_id || $data->medicine)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $medicine->where('id', $data->medicine_id)->value('medicine_name') ?? (is_object($data->medicine) ? ($data->medicine->medicine_name ?? $data->medicine->name ?? $data->medicine_id) : $data->medicine_id) }}</td>
                                                                                <td>
                                                                                    {{ $dosage->where('id', $data->dosage)->value('dosage_name') ?? (is_object($data->dosage) ? ($data->dosage->dosage_name ?? '') : $data->dosage) }}
                                                                                    {{ $unit->where('id', $data->unit)->value('unit_name') ?? (is_object($data->unit) ? ($data->unit->unit_name ?? '') : $data->unit) }}
                                                                                </td>
                                                                                <td>{{ $duration->where('id', $data->duration)->value('duration_name') ?? (is_object($data->duration) ? ($data->duration->duration_name ?? '') : $data->duration) }}</td>
                                                                                <td>{{ $interval->where('id', $data->frequency ?? $data->doseinterval)->value('interval_name') ?? ($interval->where('id', $data->doseinterval ?? $data->frequency)->value('interval_name') ?? $data->frequency ?? $data->doseinterval ?? '-') }}</td>
                                                                            </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @if($adviceItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Advice</h5>
                                                                    <ul>
                                                                        @foreach($adviceItems as $adv)
                                                                            <li>{{ $adv }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            @if($followupItems->isNotEmpty())
                                                                <div class="section-block">
                                                                    <h5>Follow-Up</h5>
                                                                    <ul>
                                                                        @foreach($followupItems as $follow)
                                                                            <li>{{ $follow }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            @if(!empty($nextVisitDate))
                                                                <div class="section-block">
                                                                    <h5>Next Visiting Schedule</h5>
                                                                    <p style="font-size: 14px; font-weight: 600; color: #1e40af;">
                                                                        <i class="bi bi-calendar2-check me-1"></i> Next Visit Date: {{ \Carbon\Carbon::parse($nextVisitDate)->format('d-m-Y') }}
                                                                        <span class="text-muted fw-normal" style="font-size: 13px;">({{ \Carbon\Carbon::parse($nextVisitDate)->diffForHumans() }})</span>
                                                                    </p>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- /#printableArea -->

                                    {{-- Print Button --}}
                                    <div class="text-center mt-3 no-print">
                                        <button type="button" onclick="window.print()" class="btn btn-primary">
                                            <i class="fas fa-print"></i> Print Prescription
                                        </button>
                                    </div>

                                </div>
                                <!-- /.prescription layout2Box -->

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection