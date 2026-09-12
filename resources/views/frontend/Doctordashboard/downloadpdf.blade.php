@extends("frontend.include.layout")
@section('title', 'Download Prescriptions PDF - Doctor Portal')

@section('content')
<!---------------------------------------->
<style>
    .patient-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        border: 1px solid #e9ecef;
        padding: 1.2rem 1.4rem;
    }

    .patient-card .card_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .patient-card .card_header h5 {
        font-weight: bold;
        font-size: 1.15rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .patient-card .card_header h5 i {
        color: #2563eb;
    }

    .patient-count-badge {
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ---------------- Search bar ---------------- */
    .search_bar {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search_bar form {
        position: relative;
        display: flex;
        gap: 10px;
    }

    .search_bar .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
        pointer-events: none;
    }

    .search_bar input {
        height: 38px;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        font-size: 15px;
        transition: .3s;
    }

    .search_bar input[type='search'] {
        padding-left: 32px;
        width: 260px;
    }

    .search_bar input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 8px rgba(79, 70, 229, .25);
        outline: none;
    }

    .search-btn {
        background: #4f46e5;
        color: #fff;
        border: none;
        width: 8rem;
        padding: 5px 5px;
        border-radius: 10px;
        transition: .3s;
    }

    .search-btn:hover {
        background: #372fd3;
    }

    /* ---------------- Table ---------------- */
    .patient-table th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6b7280;
        border-bottom: 2px solid #f1f1f1 !important;
        white-space: nowrap;
    }

    .patient-table td {
        vertical-align: middle;
    }

    .patient-name-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: #111827;
    }

    .patient-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #eef2ff;
        color: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .reg-badge {
        background: #f3f4f6;
        color: #374151;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .view-btn {
        border: none;
        padding: 0.45rem 1rem;
        border-radius: 8px;
        background-color: #4f46e5;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .3s;
    }

    .view-btn:hover {
        background-color: #372fd3;
        color: #fff;
    }

    @media(max-width:600px) {
        .patient-card .card_header {
            gap: 0.6rem;
        }
        .search_bar input[type='search'] {
            width: 100%;
        }
        .search_bar form {
            width: 100%;
            flex-direction: column;
        }
        .search-btn {
            width: 100%;
        }
    }
</style>
<!---------------------------------------->
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="container-fluid">
                    <div class="patient-card">
                        <div class="card_header">
                            <h5>
                                <i class="bi bi-people-fill"></i>
                                Patient List
                                <span class="patient-count-badge">{{ $patient->count() }} total</span>
                            </h5>
                            <div class="search_bar">
                                <form id="searchForm" onsubmit="return false;">
                                    <span class="position-relative">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="search" name="search" id="search"
                                            placeholder="Search Patient....">
                                    </span>
                                    <input type="submit" value="Search" class="search-btn" id="searchBtn">
                                </form>
                            </div>
                        </div>
                        <hr>
                        <p id="search_data"></p>
                        <div class="table-responsive">
                            <table class="table table-hover patient-table" id="patientTable">
                                <tr>
                                    <th>Registration No</th>
                                    <th>Patient Name</th>
                                    <th>Aadhar Number</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                @forelse($patient as $pati)
                                <tr class="patient-row"
                                    data-search="{{ strtolower($pati->registration.' '.$pati->patient_name.' '.$pati->aaddhar_num) }}">
                                    <td><span class="reg-badge">{{ $pati->registration }}</span></td>
                                    <td>
                                        <div class="patient-name-cell">
                                            <span class="patient-avatar">{{ strtoupper(substr($pati->patient_name, 0, 1)) }}</span>
                                            {{ $pati->patient_name }}
                                        </div>
                                    </td>
                                    <td>{{ $pati->aaddhar_num }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('prescription.show', $pati->id) }}" class="view-btn">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No patients found.</td>
                                </tr>
                                @endforelse
                            </table>
                            <p id="noResults" class="text-center text-muted py-4" style="display:none;">
                                No matching patients found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- container-fluid -->
<script>

function filterPatients() {

    const query = document.getElementById('search').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#patientTable .patient-row');
    let visibleCount = 0;

    rows.forEach(function (row) {
        const haystack = row.getAttribute('data-search') || '';
        const isMatch = haystack.includes(query);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCount++;
    });

    document.getElementById('noResults').style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';

}

document.getElementById('search').addEventListener('input', filterPatients);
document.getElementById('searchBtn').addEventListener('click', function (e) {
    e.preventDefault();
    filterPatients();
});

</script>
@endsection