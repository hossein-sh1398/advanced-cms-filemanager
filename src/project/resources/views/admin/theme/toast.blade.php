<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toastr-top-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl ": true
    };

    @if(session()->has('success'))
    toastr.success('{{ session()->get('success') }}');
    @endif
    @if(session()->has('error'))
    toastr.error('{{ session()->get('error') }}');
    @endif
</script>

<?php
session()->forget('success');
session()->forget('error');
