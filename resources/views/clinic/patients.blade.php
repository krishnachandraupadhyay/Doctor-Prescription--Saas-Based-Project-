@extends('clinic.include.layout')
@section('title', 'Clinic Patients - MediPortal')

@section('content')
<div class="page-content wrapper valex-dashboard-wrapper">
    <div class="container-fluid">

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 pb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-people-fill text-success me-2"></i> Patient Directory
                        </h4>
                        <p class="text-muted fs-13 mb-0">All patient records treated by doctors at {{ $clinic->name }}</p>
                    </div>

                    <form method="GET" action="{{ route('clinic.patients') }}" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search patient by name, ID, phone..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('clinic.patients') }}" class="btn btn-sm btn-light">Clear</a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-sm btn-success px-3">Search</button>
                    </form>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-12 text-uppercase">
                            <tr>
                                <th>PATIENT ID</th>
                                <th>PATIENT NAME</th>
                                <th>AGE / GENDER</th>
                                <th>PHONE</th>
                                <th>DOCTOR</th>
                                <th>VISIT DATE</th>
                            </tr>
                        </thead>
                        <tbody class="fs-13">
                            @forelse($patients as $patient)
                            <tr>
                                <td>
                                    <span class="badge bg-success-subtle text-success px-2 py-1 rounded">
                                        {{ $patient->patient_id ?? 'PAT-'.str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $patient->name }}</div>
                                    <small class="text-muted">{{ $patient->address ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ $patient->age ? $patient->age . ' yrs' : '-' }} / {{ ucfirst($patient->gender ?? '-') }}
                                </td>
                                <td>{{ $patient->phone ?? '-' }}</td>
                                <td>
                                    @php
                                        $doc = $patient->doctor ?? \App\Models\Doctor::find($patient->doctor_id);
                                    @endphp
                                    <span class="fw-semibold text-primary">Dr. {{ $doc->name ?? 'Doctor' }}</span>
                                </td>
                                <td>{{ $patient->created_at ? $patient->created_at->format('d M Y, h:i A') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-50"></i>
                                    No patient records found under this clinic.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($patients, 'hasPages') && $patients->hasPages())
                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-13">Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} patients</span>
                    <div>{{ $patients->links() }}</div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
