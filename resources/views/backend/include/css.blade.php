    @php
        $sysFavicon = \App\Models\SystemSetting::get('system_favicon');
        $themeColor = \App\Models\SystemSetting::get('theme_color', '#0162e8');
        $themeRgb = \App\Models\SystemSetting::hexToRgb($themeColor);
        $sidebarColor = \App\Models\SystemSetting::get('sidebar_color', 'dark');
    @endphp

    <!-- App favicon -->
    @if(!empty($sysFavicon) && file_exists(public_path($sysFavicon)))
        <link rel="shortcut icon" href="{{ asset($sysFavicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('frontend/assets/images/favicon.ico') }}">
    @endif
    
    <!-- Fonts css load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link id="fontsLink" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Swiper slider css -->
    <link href="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css -->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Valex Theme Css -->
    <link href="{{ asset('frontend/assets/css/valex-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- custom Css -->
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Dynamic Theme Styling based on System Settings -->
    <style>
        :root {
            --valex-primary: {{ $themeColor }} !important;
            --valex-primary-rgb: {{ $themeRgb }} !important;
            --tb-primary: {{ $themeColor }} !important;
            --tb-primary-rgb: {{ $themeRgb }} !important;
            --bs-primary: {{ $themeColor }} !important;
            --bs-primary-rgb: {{ $themeRgb }} !important;
        }
        .btn-primary, .bg-primary, .badge-primary {
            background-color: {{ $themeColor }} !important;
            border-color: {{ $themeColor }} !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            filter: brightness(0.92);
        }
        .text-primary {
            color: {{ $themeColor }} !important;
        }
        .valex-brand-logo .valex-brand-icon {
            background: {{ $themeColor }} !important;
        }
        @if($sidebarColor === 'gradient')
        .navbar-menu {
            background: linear-gradient(180deg, {{ $themeColor }} 0%, #111c43 100%) !important;
        }
        @elseif($sidebarColor === 'light')
        .navbar-menu {
            background: #ffffff !important;
            border-right: 1px solid #e9edf4;
        }
        .navbar-menu .navbar-nav .nav-link {
            color: #495057 !important;
        }
        .navbar-menu .menu-title span {
            color: #8c9097 !important;
        }
        .navbar-brand-box {
            background: #ffffff !important;
            border-bottom: 1px solid #e9edf4;
        }
        .sidebar-user {
            background: #f8fafc !important;
            border-top: 1px solid #e9edf4;
        }
        .sidebar-user h6 {
            color: #1e293b !important;
        }
        @endif

        .customizer-setting, #theme-settings-offcanvas, .customizer-btn, [data-bs-target="#theme-settings-offcanvas"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* Prevent any unintended backdrop from blurring or dimming the screen */
        .offcanvas-backdrop {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
        #preloader {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
    </style>
