
     <script src="{{asset('backend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/plugins.js')}}"></script>    
    <script src="{{asset('backend/assets/libs/list.js/dist/list.min.js')}} "></script>

    <!--Swiper slider js-->
    <script src="{{asset('backend/assets/libs/swiper/swiper-bundle.min.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{asset('backend/assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>

    <!--dashboard doctor init js-->
    <script src="{{asset('backend/assets/js/pages/dashboard-doctor.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('backend/assets/js/app.js')}}"></script>
    <script>
        window.addEventListener('pageshow', function (e) {
            if (e.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                window.location.reload();
            }
        });
    </script>