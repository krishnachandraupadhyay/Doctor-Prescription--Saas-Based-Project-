<!doctype html>
<html lang="en" data-layout="vertical" data-layout-width="fluid" data-sidebar="dark" data-sidebar-image="img-1" data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-topbar="light" data-bs-theme="light" data-theme-color="0">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Clinic Portal - MediPortal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Clinic Management Portal" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('backend.include.css')
</head>
<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        
        <!-- ========== App Menu ========== -->
        @include('clinic.include.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        @include('clinic.include.topbar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            @yield('content')
            @include('clinic.include.footer')      
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button class="btn btn-dark btn-icon" id="back-to-top">
        <i class="bi bi-caret-up fs-3xl"></i>
    </button>
    <!--end back-to-top-->

    @include('backend.include.js')
</body>
</html>
