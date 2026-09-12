@extends("frontend.include.layout")

@section('content')
<style>
     .patient_detail{
        background:white;
        border-radius:2rem;
        box-shadow: inset;
    }
    .patient_detail h2{
        text-align: center;
        font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .tabular_data .badge{
        border:2px solid black;
        padding:0.2rem;
    }
    .tabular_data i{
       font-size: 20px;
    }
</style>
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                {{-- {{ Auth::guard('doctor')->user()->name }} --}}
        <div class="patient_detail p-3">
              <h2>Patient details</h2>
        <div class="tabular_data">
            <table class="table table-striped table-hover">
                <tr>
                    <th> S. No. </th>
                    <th>Patient Id</th>
                    <th>Registration No:</th>
                    <th>Patient Name </th>
                    <th>Doctor's Name</th>
                    <th>Age</th>
                    <th>Aaddhar Number</th>
                    <th>Action</th>
                </tr>
                @foreach($patients as $pat)
                <tr>
                    <td>{{ $pat->id }}</td>
                    <td>{{ $pat->patient_id }}</td>
                    <td>{{ $pat->registration }}</td>
                    <td>{{ $pat->patient_name }}</td>
                    <td>{{ App\Models\Doctor::where('id', $pat->doctor_id)->value('name') }}</td>
                    <td>{{ $pat->age_year }} Year,{{ $pat->age_month }} month</td>
                    <td>{{ $pat->aaddhar_num}}</td>
                    <td>
                        <a href="{{ route('patient.edit', $pat->id )}}" class="badge m-1 bg-info"> <i class="bi bi-pencil"></i> </a>
                        <a href="{{ route('addsymptoms', $pat->id)}}" class="badge m-1 bg-warning" title="Write Symptoms & Prescription"> <i class="bi bi-prescription2"></i> </a>
                        <a href="{{route('patient.view', $pat->id) }}" class="badge m-1 bg-primary"> <i class="bi bi-eye"></i> </a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        </div>


                <!---------------------------------------------------------------------->
            </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
@endsection

