@extends('backend.include.layout')  
@section('title', 'Prescription Designs - Admin Console')
@section('content')
<style>
    .layout-scroll-box {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 5px;
    }

    .layout-scroll-box img {
        display: block;
        width: 100%;
        height: auto;
    }

    .layout-scroll-box::-webkit-scrollbar {
        width: 8px;
    }
    .layout-scroll-box::-webkit-scrollbar-thumb {
        background-color: #b5b5b5;
        border-radius: 4px;
    }

    /* footer se gap dene ke liye */
    .page-content.wrapper {
        padding-bottom: 60px;
    }
</style>

<div class="page-content wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <h3 class="text-center">Layout 1</h3>
                        <div class="fixed-version layout-scroll-box">
                            <img src="{{ asset('prescription_type/Layout1.jpg') }}" class="img-fluid" alt="Prescription Layout 1">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <h3 class="text-center">Layout 2</h3>
                        <div class="layout-scroll-box">
                            <img src="{{ asset('prescription_type/Layout2.jpg') }}" class="img-fluid" alt="Prescription Layout 2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
@endsection