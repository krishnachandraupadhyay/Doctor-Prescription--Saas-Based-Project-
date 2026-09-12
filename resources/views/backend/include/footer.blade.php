<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted">Copyright &copy; <script>document.write(new Date().getFullYear())</script> <a href="javascript:void(0);" class="text-primary fw-semibold text-decoration-none">{{ \App\Models\SystemSetting::get('system_name', 'DocPortal') }}</a> &bull; {{ \App\Models\SystemSetting::get('footer_text', 'Doctor Prescription & Clinical Management Suite.') }}</span>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <span class="text-muted">Hospital &amp; Clinical Administration.</span>
            </div>
        </div>
    </div>
</footer>