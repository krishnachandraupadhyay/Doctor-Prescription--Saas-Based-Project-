@php
    $sysThemeMode = \App\Models\SystemSetting::get('theme_mode', 'light');
    $sysSidebarColor = \App\Models\SystemSetting::get('sidebar_color', 'dark');
    $sysTopbarColor = \App\Models\SystemSetting::get('topbar_color', 'light');
    $sysSystemName = \App\Models\SystemSetting::get('system_name', 'DocPortal');
@endphp
<!doctype html>
<html lang="en" data-layout="vertical" data-layout-width="fluid" data-sidebar="{{ $sysSidebarColor }}" data-sidebar-image="none" data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-topbar="{{ $sysTopbarColor }}" data-bs-theme="{{ $sysThemeMode }}" data-theme-color="0">

<head>
    <meta charset="utf-8">
    <title>@yield('title', $sysSystemName . ' - Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Minimal Admin & Dashboard Template" name="description">
    <meta content="Themesbrand" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('backend.include.css')
</head>
<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        
        <!-- ========== App Menu ========== -->
          @includeIf('backend.include.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
           @includeif('backend.include.topbar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                @yield('content')
            <!-- End Page-content -->
              @includeIf('backend.include.footer')      
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->


    <!--start back-to-top-->
    <button class="btn btn-dark btn-icon" id="back-to-top">
        <i class="bi bi-caret-up fs-3xl"></i>
    </button>
    <!--end back-to-top-->

    <!-- JAVASCRIPT -->
    @includeif('backend.include.js')
    @stack('scripts')
   
</body>

</html>