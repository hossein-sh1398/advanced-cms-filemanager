<!DOCTYPE html>
<html lang="fa" direction="rtl" dir="rtl" style="direction: rtl">
<!--begin::Head-->

<head>
    @include('admin.theme.head')
</head>

<body id="kt_body" class="bg-body">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url('{{ url('cdn/theme/admin/media/illustrations/sketchy-1/14.png') }}')">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Logo-->
                <a href="#" class="mb-12">
                    <img alt="Logo" src="{{ url($logo) }}" class="h-40px" />
                </a>
                <!--end::Logo-->
                <!--begin::Wrapper-->
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    @yield('auth.content')
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>

    @include('admin.theme.end')
    @include('admin.theme.toast')
    @stack('auth-js')
</body>

</html>
