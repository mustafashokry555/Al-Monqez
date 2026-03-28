<!-- Favicon -->
<link rel="icon" href="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}"
    type="image/x-icon" />
<!--  Custom Scroll bar-->
<link href="{{ URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet" />
<!--  Sidebar css -->
<link href="{{ URL::asset('assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">
<!-- Icons css -->
<link href="{{ URL::asset('assets/css-rtl/icons.css') }}" rel="stylesheet">
<!-- Sidemenu css -->
<link rel="stylesheet" href="{{ URL::asset('assets/css-rtl/sidemenu.css') }}">
<!--- Style css -->
<link href="{{ URL::asset('assets/css-rtl/style.css') }}" rel="stylesheet">
<!-- Fonts Css -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
@if (isset($super_admin) || isset($order_control))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endif
<!-- Style Css-->
<style>
    * {
        font-family: 'Cairo', "Roboto", sans-serif;
    }

    .card-header {
        border-bottom: 1px solid #dde2ef
    }

    .main-toggle span::before,
    .main-toggle span::after {
        font-size: 9px;
        top: 3px;
    }

    .main-toggle span::after {
        right: -30px;
    }

    .alert-dismissible .close {
        right: auto;
        left: 0;
    }
</style>
@yield('css')
