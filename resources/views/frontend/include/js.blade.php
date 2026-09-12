
     <script src="{{asset('frontend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins.js')}}"></script>    
    <script src="{{asset('frontend/assets/libs/list.js/dist/list.min.js')}} "></script>

    <!--Swiper slider js-->
    <script src="{{asset('frontend/assets/libs/swiper/swiper-bundle.min.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{asset('frontend/assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>

    <!--dashboard doctor init js-->
    <script src="{{asset('frontend/assets/js/pages/dashboard-doctor.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('frontend/assets/js/app.js')}}"></script>
    <script src="{{asset('frontend/assets/js/frontend/updateprofilejs.js')}}"></script>
    <script src="{{asset('frontend/assets/js/frontend/uploaddocument.js')}}"></script>
    <script>
        window.addEventListener('pageshow', function (e) {
            if (e.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                window.location.reload();
            }
        });
    </script>