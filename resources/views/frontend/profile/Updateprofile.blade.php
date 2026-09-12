@extends("frontend.include.layout")
@section('title', 'Update Profile - Doctor Portal')
@include('frontend.include.css')
@if(Auth::guard('doctor')->user()->profile_status == 1)
<div class="modal fade show" id="profileModal" style="display:block;">
                    <div class="modal-dialog">
                       <div class="modal-content">
                             <div class="modal-header">
                                <h5>Complete Your Documentation</h5>
                              </div>

                              <div class="modal-body">
                                   Please complete your documentation before using the dashboard.
                               </div>

                            <div class="modal-footer">
                                 <a href="{{ route('UploadDocument') }}"
                                      class="btn btn-primary">
                    OK
                </a>
            </div>
        </div>
    </div>
</div>

@endif

@section('content')
<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
           @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                {{-- {{  }} --}}
<div class="page">
  <div class="page-header">
     <div class="page-head">
         <h1>Add New Doctor</h1>
         <div class="sub">Fill in every section below, then save.</div>
     </div>
      <div class="crumb">Doctor's / <b>Add New Doctor</b></div>
  </div>

 

  <form id="regForm" action="{{ route('frontend.profile.update') }}" method="POST"  enctype="multipart/form-data">
   @csrf
    <!-- SECTION 1 -->
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">1</div>
        <div><h2>Identity & contact</h2><p>How the doctor logs in and how patients reach them.</p></div>
      </div>
      <div class="section-body">
        <div class="field span2">
          <label for="name">Full name</label>
          <input type="text" id="name" name="name" value="{{Auth::guard('doctor')->user()->name}}" placeholder="Dr. Anjali Mehta" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
          @error('name')
          <span class="text-danger">{{ $message }}</span>
           @enderror
        </div>
        <div class="grid2">
          <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{Auth::guard('doctor')->user()->email }}" placeholder="you@clinic.com" readonly required>
             @error('email')
          <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="field">
            <label for="phone">Phone number</label>
            <input type="tel" id="phone" name="phone" placeholder="9876543210" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
          </div>
           @error('phone')
          <span class="text-danger">{{ $message }}</span>
           @enderror
        </div>
        <div class="field span2" style="margin-top:18px;">
          <label for="password">Password</label>
          <div class="pw-wrap">
            <input type="password" id="password" name="password" placeholder="At least 8 characters" required minlength="8">
            <button type="button" class="pw-toggle" id="pwToggle">Show</button>
          </div>
           @error('password')
          <span class="text-danger">{{ $message }}</span>
           @enderror
          <div class="pw-hint">Use a mix of letters, numbers, and a symbol.</div>
        </div>
      </div>
    </div>

    <!-- SECTION 2 -->
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">2</div>
        <div><h2>Clinic details</h2><p>Shown on the doctor's public profile.</p></div>
      </div>
      <div class="section-body">
        <div class="field span2">
          <label for="clinic_name">Clinic name</label>
          <input type="text" id="clinic_name" name="clinic_name" value="{{Auth::guard('doctor')->user()->clinic_name }}" placeholder="Mehta Family Clinic" required>
           @error('clinic_name')
          <span class="text-danger">{{ $message }}</span>
           @enderror
        </div>
        <div class="field span2" style="margin-top:18px;">
          <label for="clinic_address">Clinic address</label>
          <textarea id="clinic_address" name="clinic_address"  placeholder="Street, city, state, PIN code" required></textarea>
           @error('clinic_address')
          <span class="text-danger">{{ $message }}</span>
           @enderror
        </div>
      </div>
    </div>

    <!-- SECTION 3 -->
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">3</div>
        <div><h2>Professional credentials</h2><p>Used to verify the doctor before the profile goes live.</p></div>
      </div>
      <div class="section-body">
        <div class="grid2">
          <div class="field">
            <label for="qualification">Qualification</label>
            <input type="text" id="qualification" name="qualification" placeholder="MBBS, MD" value="{{ Auth::guard('doctor')->user()->qualification }}" required>
             @error('qualification')
             <span class="text-danger">{{ $message }}</span>
             @enderror 
          </div>
          <div class="field">
            <label for="specialization">Specialization</label>
            <input type="text" id="specialization" name="specialization" placeholder="Cardiology" value="{{ Auth::guard('doctor')->user()->specialization }}" required>
             @error('specialization')
          <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="field">
            <label for="registration_number">Registration number</label>
            <input type="text" id="registration_number" name="registration_number" placeholder="REG-0000000" value="{{ Auth::guard('doctor')->user()->registration_number }}" required>
             @error('registration_number')
          <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="field">
            <label for="Experience">Experience <span class="opt">(years)</span></label>
            <input type="number" id="Experience" name="experience" min="0" placeholder="8" value="{{ Auth::guard('doctor')->user()->Experience }}" required>
             @error('experience')
             <span class="text-danger">{{ $message }}</span>
             @enderror
          </div>
          <div class="field span2">
            <label for="license_number">License number</label>
            <input type="text" id="license_number" name="license_number" placeholder="LIC-0000000" value="{{ Auth::guard('doctor')->user()->license_number }}" required>
             @error('license_number')
             <span class="text-danger">{{ $message }}</span>
             @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 4 -->
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">4</div>
        <div><h2>Documents</h2><p>Upload the clinic logo, signature, and clinic stamp.</p></div>
      </div>
      <div class="section-body">
        <div class="upload-grid">
          <label class="upload-tile" id="tile-logo">
            <input type="file" id="logo" name="logo" accept="image/*">
            <div class="upload-body">
              <div class="upload-icon">●</div>
              <div class="upload-tile-label">Clinic logo</div>
              <div class="upload-tile-sub">PNG </div>
            </div>
          </label>
          <label class="upload-tile" id="tile-signature">
            <input type="file" id="signature" name="signature" accept="image/*">
            <div class="upload-body">
              <div class="upload-icon">✎</div>
              <div class="upload-tile-label">Signature</div>
              <div class="upload-tile-sub">PNG </div>
            </div>
          </label>
          <label class="upload-tile" id="tile-clinic_stamp">
            <input type="file" id="clinic_stamp" name="clinic_stamp" accept="image/*">
            <div class="upload-body">
              <div class="upload-icon">◎</div>
              <div class="upload-tile-label">Clinic stamp</div>
              <div class="upload-tile-sub">PNG </div>
            </div>
          </label>
        </div>
      </div>
    </div>

    <div class="action-bar">
      <button type="button" class="btn btn-ghost" id="btnCancel">Cancel</button>
      <button type="submit" class="btn btn-primary">Save doctor</button>
      
    </div>
  </form>
</div>

<div class="toast" id="toast">
  <div class="tick">✓</div>
  <div class="msg"><strong>Doctor added</strong><span>The new profile has been saved successfully.</span></div>
</div>
@include('frontend.include.js')
                <!------------------------------------------------------------------------------------------------------>
            </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
@endsection

   @if ($errors->any())
               <div class="alert alert-danger">
                   <ul>
                     @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                     @endforeach
                   </ul>
                 </div>
                @endif