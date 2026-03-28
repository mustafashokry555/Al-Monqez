<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active"
            href="{{ auth()->user()->role_id == 6 ? route('store_app.admin') : route('admin') }}"><img
                src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}" class="main-logo"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active"
            href="{{ auth()->user()->role_id == 6 ? route('store_app.admin') : route('admin') }}"><img
                src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}" class="logo-icon"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{ auth()->user()->imageLink }}"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ auth()->user()->name }}</h4>
                </div>
            </div>
        </div>

        <ul class="side-menu">
            <li class="side-item side-item-category">{{ __('admin.menu') }}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin') }}"><i class="fe fe-home ml-3"
                        style="font-size: 16px"></i><span
                        class="side-menu__label">{{ __('admin.main_dashboard') }}</span></a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('services_app.admin') }}"><i class="fe fe-home ml-3"
                        style="font-size: 16px"></i><span
                        class="side-menu__label">{{ __('admin.services_app_dashboard') }}</span></a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('store_app.admin') }}"><i class="fe fe-home ml-3"
                        style="font-size: 16px"></i><span
                        class="side-menu__label">{{ __('admin.store_app_dashboard') }}</span></a>
            </li>

            @if (isset($super_admin) ||
                    isset($admin_create) ||
                    isset($admin_edit) ||
                    isset($admin_delete) ||
                    isset($client_create) ||
                    isset($client_edit) ||
                    isset($client_delete))
                <li class="side-item side-item-category">{{ __('admin.users') }}</li>

                @if (isset($super_admin) || isset($client_create) || isset($client_edit) || isset($client_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-users ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.clients') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($client_edit) || isset($client_delete))
                                <li><a class="slide-item"
                                        href="{{ route('admin.clients.index') }}">{{ __('admin.all_clients') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($client_create))
                                <li><a class="slide-item"
                                        href="{{ route('admin.clients.create') }}">{{ __('admin.add_client') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($admin_create) || isset($admin_edit) || isset($admin_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-users ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.employees') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($admin_edit) || isset($admin_delete))
                                <li><a class="slide-item"
                                        href="{{ route('admin.admins.index') }}">{{ __('admin.all_employees') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($admin_create))
                                <li><a class="slide-item"
                                        href="{{ route('admin.admins.create') }}">{{ __('admin.add_employee') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($contact_control) ||
                    isset($notification_control) ||
                    isset($users_activity_log_control))
                <li class="side-item side-item-category">{{ __('admin.management') }}</li>

                @if (isset($super_admin) || isset($activity_log_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('admin.users.activity_logs') }}"><i
                                class="fe fe-activity ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.users_activity_logs') }}</span></a>
                    </li>
                @endif

                @if (isset($super_admin) || isset($contact_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('admin.contacts.index') }}"><i
                                class="fe fe-message-square ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.contacts') }}</span></a>
                    </li>
                @endif

                @if (isset($super_admin) || isset($notification_control))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="far fa-paper-plane ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.notifications') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            <li><a class="slide-item"
                                    href="{{ route('admin.notifications.index') }}">{{ __('admin.sent_notifications') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($setting_change) ||
                    isset($social_create) ||
                    isset($social_edit) ||
                    isset($social_delete) ||
                    isset($term_create) ||
                    isset($term_edit) ||
                    isset($term_delete) ||
                    isset($about_create) ||
                    isset($about_edit) ||
                    isset($about_delete))
                <li class="side-item side-item-category">{{ __('admin.additional_data') }}</li>

                @if (isset($super_admin) || isset($setting_change))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-settings ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.settings') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            <li><a class="slide-item"
                                    href="{{ route('admin.settings.index') }}">{{ __('admin.main_settings') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($social_create) || isset($social_edit) || isset($social_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-aperture ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.socials') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($social_edit) || isset($social_delete))
                                <li><a class="slide-item"
                                        href="{{ route('admin.socials.index') }}">{{ __('admin.all_socials') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($social_create))
                                <li><a class="slide-item"
                                        href="{{ route('admin.socials.create') }}">{{ __('admin.add_social') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($term_create) || isset($term_edit) || isset($term_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-type ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.terms') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($term_edit) || isset($term_delete))
                                <li><a class="slide-item"
                                        href="{{ route('admin.terms.index') }}">{{ __('admin.all_terms') }}</a></li>
                            @endif
                            @if (isset($super_admin) || isset($term_create))
                                <li><a class="slide-item"
                                        href="{{ route('admin.terms.create') }}">{{ __('admin.add_term') }}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($about_create) || isset($about_edit) || isset($about_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-info ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.abouts') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($about_edit) || isset($about_delete))
                                <li><a class="slide-item"
                                        href="{{ route('admin.abouts.index') }}">{{ __('admin.all_abouts') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($about_create))
                                <li><a class="slide-item"
                                        href="{{ route('admin.abouts.create') }}">{{ __('admin.add_about') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
