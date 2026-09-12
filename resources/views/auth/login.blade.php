<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'MediPortal') }} — Secure Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0E2624;
    --teal-deep:#0F3B38;
    --teal-mid:#1D6B63;
    --mint-bg:#F4FAF8;
    --panel:#FFFFFF;
    --line:#DCEAE6;
    --amber:#E8A33D;
    --amber-dark:#C9832A;
    --muted:#5C7B76;
    --error:#C1473A;
  }

  *{ box-sizing:border-box; margin:0; padding:0; }

  body{
    font-family:'Inter', sans-serif;
    background:var(--mint-bg);
    color:var(--ink);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px 16px;
  }

  .wrap{
    width:100%;
    max-width:960px;
    min-height:580px;
    background:var(--panel);
    border-radius:20px;
    display:grid;
    grid-template-columns:1.1fr 1fr;
    overflow:hidden;
    box-shadow:0 25px 50px -12px rgba(15,59,56,0.18), 0 0 0 1px rgba(15,59,56,0.05);
  }

  /* LEFT PANEL — signature branding & ECG */
  .side{
    background:radial-gradient(circle at 20% 15%, #175C55 0%, var(--teal-deep) 55%, #082220 100%);
    color:#EAF5F2;
    padding:44px 40px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    position:relative;
    overflow:hidden;
  }

  .side::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
    background-size:24px 24px;
    opacity:0.6;
  }

  .brand{
    display:flex;
    align-items:center;
    gap:10px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12.5px;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:#9FCFC7;
    z-index:1;
  }

  .brand .dot{
    width:8px; height:8px; border-radius:50%;
    background:var(--amber);
    box-shadow:0 0 0 4px rgba(232,163,61,0.25);
  }

  .side-mid{ z-index:1; margin-top:32px; }

  .side-mid h1{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:clamp(24px, 2.5vw, 32px);
    line-height:1.25;
    color:#fff;
    max-width:340px;
  }

  .side-mid p{
    margin-top:14px;
    font-size:14px;
    color:#B7D9D3;
    max-width:320px;
    line-height:1.55;
  }

  /* ECG line */
  .ecg-box{
    z-index:1;
    margin-top:28px;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.12);
    border-radius:12px;
    padding:12px 16px;
  }

  .ecg-box svg{ width:100%; height:48px; display:block; }

  .ecg-path{
    fill:none;
    stroke:var(--amber);
    stroke-width:2;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:600;
    stroke-dashoffset:600;
    animation:draw 3.2s linear infinite;
  }

  @keyframes draw{
    0%{ stroke-dashoffset:600; opacity:0.4; }
    50%{ stroke-dashoffset:0; opacity:1; }
    100%{ stroke-dashoffset:-600; opacity:0.4; }
  }

  .ecg-caption{
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    color:#7FB0A8;
    margin-top:6px;
    display:flex;
    justify-content:space-between;
  }

  .quote{
    z-index:1;
    font-size:12.5px;
    color:#8FC0B8;
    border-top:1px solid rgba(255,255,255,0.15);
    padding-top:16px;
    line-height:1.55;
  }

  .quote strong{ color:#EAF5F2; display:block; margin-bottom:2px; }

  /* RIGHT PANEL — form */
  .form-side{
    padding:44px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }

  /* Mobile only brand badge */
  .mobile-brand-bar{
    display:none;
  }

  .form-side h2{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:26px;
    color:var(--ink);
  }

  .form-side .sub{
    margin-top:6px;
    font-size:13.5px;
    color:var(--muted);
    line-height:1.45;
  }

  form{ margin-top:24px; }

  .field{ margin-bottom:18px; }

  .field label{
    display:block;
    font-size:12px;
    font-weight:600;
    letter-spacing:0.03em;
    color:var(--teal-deep);
    margin-bottom:6px;
    text-transform:uppercase;
  }

  .input-shell{
    position:relative;
    display:flex;
    align-items:center;
    border:1.5px solid var(--line);
    border-radius:10px;
    background:#FBFDFC;
    transition:border-color .18s ease, box-shadow .18s ease;
  }

  .input-shell:focus-within{
    border-color:var(--teal-mid);
    box-shadow:0 0 0 3px rgba(29,107,99,0.12);
    background:#fff;
  }

  .input-shell input{
    flex:1;
    border:none;
    outline:none;
    background:transparent;
    padding:13px 14px;
    font-size:15px;
    color:var(--ink);
    font-family:'Inter', sans-serif;
    min-height:46px;
  }

  .input-shell input::placeholder{ color:#9FB5B1; }

  .icon-btn{
    background:none;
    border:none;
    cursor:pointer;
    padding:0 14px;
    color:var(--muted);
    font-size:12px;
    font-family:'IBM Plex Mono', monospace;
    font-weight:600;
    user-select:none;
    height:46px;
    display:flex;
    align-items:center;
  }
  .icon-btn:hover{ color:var(--teal-deep); }

  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:6px 0 22px;
    font-size:13px;
    flex-wrap:wrap;
    gap:8px;
  }

  .remember{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--muted);
    cursor:pointer;
    user-select:none;
  }
  .remember input{ accent-color:var(--teal-mid); width:16px; height:16px; cursor:pointer; }

  .row-between a{
    color:var(--teal-mid);
    text-decoration:none;
    font-weight:600;
  }
  .row-between a:hover{ text-decoration:underline; }

  button.submit{
    width:100%;
    padding:14px;
    min-height:48px;
    border:none;
    border-radius:10px;
    background:var(--teal-deep);
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    transition:background .18s ease, transform .12s ease;
    box-shadow:0 4px 12px rgba(15,59,56,0.15);
  }
  button.submit:hover{ background:#0B2D2B; transform:translateY(-1px); }
  button.submit:active{ transform:scale(0.98); }
  button.submit:disabled{ opacity:0.7; cursor:progress; }

  .spinner{
    width:16px; height:16px;
    border-radius:50%;
    border:2px solid rgba(255,255,255,0.4);
    border-top-color:#fff;
    animation:spin .7s linear infinite;
    display:none;
  }
  @keyframes spin{ to{ transform:rotate(360deg); } }

  .error-msg{
    font-size:12px;
    color:var(--error);
    margin-top:5px;
    min-height:16px;
  }

  .divider{
    display:flex;
    align-items:center;
    gap:12px;
    margin:22px 0 16px;
    color:var(--muted);
    font-size:12px;
  }
  .divider::before, .divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--line);
  }

  .foot-note{
    text-align:center;
    font-size:13px;
    color:var(--muted);
  }
  .foot-note a{ color:var(--teal-deep); font-weight:600; text-decoration:none; }
  .foot-note a:hover{ text-decoration:underline; }

  .status-box{
    z-index:1;
    background:#EAF5F2;
    color:var(--teal-deep);
    border:1px solid var(--line);
    border-radius:10px;
    padding:10px 14px;
    font-size:13px;
    margin-bottom:16px;
  }

  /* focus visibility */
  a:focus-visible, button:focus-visible, input:focus-visible{
    outline:2px solid var(--amber);
    outline-offset:2px;
  }

  @media (prefers-reduced-motion: reduce){
    .ecg-path{ animation:none; stroke-dashoffset:0; }
    .spinner{ animation:none; }
  }

  /* ========================================= */
  /* MOBILE & TABLET RESPONSIVENESS            */
  /* ========================================= */
  @media (max-width: 860px){
    body{
      padding:12px;
      align-items:flex-start;
      min-height:100vh;
    }
    .wrap{
      grid-template-columns:1fr;
      min-height:auto;
      border-radius:16px;
      margin:auto 0;
      box-shadow:0 10px 25px -5px rgba(15,59,56,0.12);
    }
    
    /* Sleek compact header on mobile instead of bulky side panel */
    .side{
      padding:24px 20px 20px;
      border-radius:16px 16px 0 0;
    }
    .side-mid{
      margin-top:14px;
    }
    .side-mid h1{
      font-size:20px;
      max-width:100%;
    }
    .side-mid p{
      font-size:13px;
      margin-top:6px;
      max-width:100%;
    }
    .ecg-box, .quote{
      display:none !important;
    }
    
    .form-side{
      padding:26px 20px 32px;
    }
    .form-side h2{
      font-size:22px;
    }
    .form-side .sub{
      font-size:13px;
    }
    form{
      margin-top:20px;
    }
  }

  @media (max-width: 480px){
    body{
      padding:8px;
    }
    .wrap{
      border-radius:14px;
    }
    .side{
      padding:20px 16px 16px;
    }
    .form-side{
      padding:22px 16px 28px;
    }
    .input-shell input{
      font-size:15px;
      padding:12px;
    }
    button.submit{
      font-size:14.5px;
      padding:12px;
    }
  }
</style>
</head>
<body>

<div class="wrap">

  <!-- LEFT: brand + signature ECG -->
  <div class="side">
    <div class="brand">
      <span class="dot"></span>
      {{ config('app.name', 'MediPortal') }} · Healthcare Access
    </div>

    <div class="side-mid">
      <h1>Every patient's story starts with your sign-in.</h1>
      <p>Secure portal access for Clinics, Doctors, Onboarding Managers, and Clinical Staff.</p>

      <div class="ecg-box">
        <svg viewBox="0 0 300 60" preserveAspectRatio="none">
          <path class="ecg-path" d="M0,30 L40,30 L52,30 L60,10 L70,50 L80,30 L95,30 L110,30 L120,18 L128,42 L136,30 L300,30" />
        </svg>
        <div class="ecg-caption"><span>SYSTEM STATUS</span><span id="statusText">SECURE CONNECTION</span></div>
      </div>
    </div>

    <div class="quote">
      <strong>"Uptime matters when someone's on the table."</strong>
      99.98% portal availability, audited monthly.
    </div>
  </div>

  <!-- RIGHT: login form -->
  <div class="form-side">
    <h2>Welcome to Portal</h2>
    <p class="sub">Sign in with your registered Email &amp; Password to access your dashboard.</p>

    {{-- Session Status --}}
    @if (session('status'))
      <div class="status-box" style="margin-top:16px;">
        {{ session('status') }}
      </div>
    @endif

    {{-- Login error (wrong email/password, inactive account, etc.) --}}
    @if (session('error'))
      <div class="status-box" style="margin-top:16px; background:#FBEAE8; color:var(--error); border-color:#F3CFC9;">
        {{ session('error') }}
      </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div class="field id-field">
        <label for="email">Email Address</label>
        <div class="input-shell">
          <input type="email"
                 id="email"
                 name="email"
                 value="{{ old('email') }}"
                 placeholder="name@hospital.org"
                 autocomplete="username"
                 required autofocus>
        </div>
        @error('email')
          <div class="error-msg">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password -->
      <div class="field">
        <label for="password">Password</label>
        <div class="input-shell">
          <input type="password"
                 id="password"
                 name="password"
                 placeholder="Enter your password"
                 autocomplete="current-password"
                 required>
          <button type="button" class="icon-btn" id="togglePw" aria-label="Toggle password visibility">SHOW</button>
        </div>
        @error('password')
          <div class="error-msg">{{ $message }}</div>
        @enderror
      </div>

      <div class="row-between">
        <label class="remember" for="remember_me">
          <input type="checkbox" id="remember_me" name="remember"> Keep me signed in
        </label>

        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" id="forgotLink">Forgot password?</a>
        @endif
      </div>

      <button type="submit" class="submit" id="submitBtn">
        <span class="spinner" id="spinner"></span>
        <span id="btnLabel">Sign in to Portal</span>
      </button>
    </form>

    <div class="divider">or</div>

    <p class="foot-note">New to this network? <a href="{{ route('register') }}">Request clinician access</a></p>
  </div>

</div>

<script>
  const form = document.getElementById('loginForm');
  const pwInput = document.getElementById('password');
  const toggleBtn = document.getElementById('togglePw');
  const submitBtn = document.getElementById('submitBtn');
  const spinner = document.getElementById('spinner');
  const btnLabel = document.getElementById('btnLabel');
  const statusText = document.getElementById('statusText');

  // Show / hide password
  toggleBtn.addEventListener('click', () => {
    const isPw = pwInput.type === 'password';
    pwInput.type = isPw ? 'text' : 'password';
    toggleBtn.textContent = isPw ? 'HIDE' : 'SHOW';
  });

  // Form submit handler with spinner feedback
  form.addEventListener('submit', () => {
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnLabel.textContent = 'Verifying credentials…';
    if (statusText) {
      statusText.textContent = 'AUTHENTICATING…';
    }
  });

  // Prevent bfcache navigation (Back/Forward browser arrows)
  window.addEventListener('pageshow', function (e) {
    if (e.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
      window.location.reload();
    }
  });
</script>

</body>
</html>