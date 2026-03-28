<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ auth()->user()->role_id == 6 ? route('store_app.admin') : route('admin') }}"><img
                        src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}" class="logo-1"
                        alt="logo"></a>
                <a href="{{ auth()->user()->role_id == 6 ? route('store_app.admin') : route('admin') }}"><img
                        src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}" class="logo-2"
                        alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="javascript:void();" onclick="sidebar_session(true, 1)"><i
                        class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="javascript:void();" onclick="sidebar_session(true, 0)"><i
                        class="header-icons fe fe-x"></i></a>
            </div>
        </div>
        @include('layouts.footer')
        <div class="main-header-right">
            <div class="nav nav-item  navbar-nav-right ml-auto">
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href=""><img alt=""
                            src="{{ auth()->user()->imageLink }}"></a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user"><img alt="" src="{{ auth()->user()->imageLink }}"
                                        class=""></div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ auth()->user()->name }}</h6>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item " href="{{ route('admin.profile') }}"><i class="bx bx-user-circle"></i>
                            {{ __('admin.update_profile') }}</a>
                        <a class="dropdown-item " href="javascript:void();"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-log-out"></i> {{ __('admin.logout') }}</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main-header -->
