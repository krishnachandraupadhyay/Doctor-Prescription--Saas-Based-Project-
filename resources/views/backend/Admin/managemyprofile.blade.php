@extends('backend.include.layout')

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
               <table class="table table-light table-hover">
                 <tr>
                   <th>Name</th>
                   <th>Email</th>
                   <th>Clinic Name</th>
                   <th>Status</th>
                   <th>Action</th>
                 </tr>
                 @foreach($doctors as $doctor)
                 <tr>
                   <td>{{ $doctor->name }}</td>
                   <td>{{ $doctor->email }}</td>
                   <td>{{ $doctor->clinic_name }}</td>
                   <td>{{ $doctor->status ? 'Active' : 'Inactive' }}</td>
                   <td>
                     <a href="{{ route('doctor.update', $doctor->id) }}" class="btn btn-primary btn-sm">Edit</a>
                     <form action="{{ route('doctor.destroy', $doctor->id) }}" method="POST" style="display:inline-block;">
                       @csrf
                       @method('DELETE')
                       <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                     </form>
                     <a href="{{ route('viewdoctor', $doctor->id) }}" class="btn btn-info btn-sm">View</a>
                     </td>
                 </tr>
                 @endforeach
                </table>
            
            </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
@endsection


