<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> @yield('title') | {{ $setting ? $setting->name : __('admin.main_title') }}</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
    @include('layouts.head')
</head>

<body class="main-body app sidebar-mini">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    @auth
        <!-- Notification -->
        <audio id="notif-sound" src="{{ URL::asset('assets/sounds/notification.mp3') }}" preload="auto"
            style="display:none"></audio>
        <div id="awn-toast-container" class="awn-bottom-right"
            style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;"></div>
    @endauth
    <!-- /Loader -->
    @if (request()->is('services-app/*') || auth()->user()->role_id == '7')
        @include('layouts.services-app-sidebar')
    @elseif (request()->is('store-app/*') || auth()->user()->role_id == '6')
        @include('layouts.store-app-sidebar')
    @else
        @include('layouts.main-sidebar')
    @endif
    <!-- main-content -->
    <div class="main-content app-content">
        @include('layouts.main-header')
        <!-- container -->
        <div class="container-fluid">
            @yield('page-header')
            @yield('content')
            @include('layouts.footer-scripts')
        </div>
    </div>
</body>

</html>
