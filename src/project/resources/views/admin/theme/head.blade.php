<base href="">
<title>{{ $pageInfo->title ?? 'بدون عنوان' }}</title>
<meta charset="utf-8"/>
<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Blazor, Django, Flask &amp; Laravel versions. Grab your copy now and get life-time updates for free."/>
<meta name="keywords" content="Metronic, Bootstrap, Bootstrap 5, Angular, VueJs, React, Asp.Net Core, Blazor, Django, Flask &amp; Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta property="og:locale" content="en_US"/>
<meta property="og:type" content="article"/>
<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular, Asp.Net Core, Blazor, Django, Flask &amp; Laravel Admin Dashboard Theme"/>
<meta property="og:url" content="https://keenthemes.com/metronic"/>
<meta property="og:site_name" content="Keenthemes | Metronic"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
<link rel="shortcut icon" href="{{ asset($favicon) }}"/>
<!--begin::Fonts-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used by this page)-->
{{--<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css"/>--}}
{{--<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>--}}
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(used by all pages)-->
<link href="{{ url('cdn/theme/admin/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('cdn/theme/admin/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
<!--end::Global Stylesheets Bundle-->

<link href="{{ url('cdn/theme/admin/css/custom.css') }}" rel="stylesheet" type="text/css" />

<style>
    .toastr-message {
        margin-top: -3px;
    }

    .toastr-close-button {
        margin-left: 7px;
    }
</style>
