<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('admin') }}"><img
                src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}" class="main-logo"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('admin') }}"><img
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

            @if (auth()->user()->role_id != '7')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin') }}"><i class="fe fe-home ml-3"
                            style="font-size: 16px"></i><span
                            class="side-menu__label">{{ __('admin.main_dashboard') }}</span></a>
                </li>
            @endif

            <li class="slide">
                <a class="side-menu__item" href="{{ route('services_app.admin') }}"><i class="fe fe-home ml-3"
                        style="font-size: 16px"></i><span
                        class="side-menu__label">{{ __('admin.services_app_dashboard') }}</span></a>
            </li>

            @if (auth()->user()->role_id != '7')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('store_app.admin') }}"><i class="fe fe-home ml-3"
                            style="font-size: 16px"></i><span
                            class="side-menu__label">{{ __('admin.store_app_dashboard') }}</span></a>
                </li>
            @endif

            @if (isset($super_admin) || isset($worker_create) || isset($worker_edit) || isset($worker_delete))
                <li class="side-item side-item-category">{{ __('admin.users') }}</li>

                @if (isset($super_admin) || isset($company_create) || isset($company_edit) || isset($company_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-building ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.companies') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($company_edit) || isset($company_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.companies.index') }}">{{ __('admin.all_companies') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($company_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.companies.create') }}">{{ __('admin.add_company') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($worker_create) || isset($worker_edit) || isset($worker_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-users ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.workers') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($worker_edit) || isset($worker_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.workers.index') }}">{{ __('admin.all_workers') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($worker_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.workers.create') }}">{{ __('admin.add_worker') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || (isset($worker_edit) && auth()->user()->role_id != '7'))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.workers.joining_requests.index') }}">{{ __('admin.joining_requests') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($notification_control) ||
                    isset($control_panel_control) ||
                    isset($chat_control) ||
                    isset($report_control))
                <li class="side-item side-item-category">{{ __('admin.management') }}</li>

                @if (isset($super_admin) || isset($control_panel_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('services_app.admin.maps.index') }}"><i
                                class="fe fe-map ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.control_panel') }}</span></a>
                    </li>
                @endif

                @if (isset($super_admin) || isset($chat_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('services_app.admin.chats.index') }}"><i
                                class="fa fa-comments ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.chats') }}</span></a>
                    </li>
                @endif

                @if (isset($super_admin) || isset($report_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('services_app.admin.reports.index') }}">
                            <i class="fe fe-file-text ml-3" style="font-size: 16px"></i>
                            <span class="side-menu__label">{{ __('admin.reports') }}</span>
                        </a>
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
                                    href="{{ route('services_app.admin.notifications.received') }}">{{ __('admin.received_notifications') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($partner_create) ||
                    isset($partner_edit) ||
                    isset($partner_delete) ||
                    isset($order_control) ||
                    isset($withdraw_control))
                <li class="side-item side-item-category">{{ __('admin.commerce') }}</li>

                @if (isset($super_admin) || isset($partner_create) || isset($partner_edit) || isset($partner_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-american-sign-language-interpreting ml-3"
                                style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.partners') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($partner_edit) || isset($partner_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.partners.index') }}">{{ __('admin.all_partners') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($partner_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.partners.create') }}">{{ __('admin.add_partner') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($order_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('services_app.admin.orders.index') }}"><i
                                class="fa fa-street-view ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.orders') }}</span></a>
                    </li>
                    @if (auth()->user()->role_id == '7')
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('services_app.admin.offers.index') }}"><i
                                    class="fa fa-street-view ml-3" style="font-size: 16px"></i><span
                                    class="side-menu__label">{{ __('admin.order_offers') }}</span></a>
                        </li>
                    @endif
                    @if (auth()->user()->role_id != '7')
                        <li class="slide">
                            <a class="side-menu__item"
                                href="{{ route('services_app.admin.orders.complaints.index') }}"><i
                                    class="fa fa-comments ml-3" style="font-size: 16px"></i><span
                                    class="side-menu__label">{{ __('admin.complaints') }}</span></a>
                        </li>
                    @endif
                @endif

                @if (isset($super_admin) || isset($withdraw_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('services_app.admin.withdraws.index') }}"><i
                                class="fa fa-credit-card ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.withdraws') }}</span></a>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($setting_change) ||
                    isset($city_create) ||
                    isset($city_edit) ||
                    isset($city_delete) ||
                    isset($category_create) ||
                    isset($category_edit) ||
                    isset($category_delete) ||
                    isset($sub_category_create) ||
                    isset($sub_category_edit) ||
                    isset($sub_category_delete) ||
                    isset($service_create) ||
                    isset($service_edit) ||
                    isset($service_delete) ||
                    isset($slider_create) ||
                    isset($slider_edit) ||
                    isset($slider_delete))
                <li class="side-item side-item-category">{{ __('admin.additional_data') }}</li>

                @if (isset($super_admin) || isset($setting_change))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-settings ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.settings') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            <li><a class="slide-item"
                                    href="{{ route('services_app.admin.settings.index') }}">{{ __('admin.order_settings') }}</a>
                            </li>
                            <li><a class="slide-item"
                                    href="{{ route('services_app.admin.settings.regions.edit') }}">{{ __('admin.region_coordinates') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($city_create) || isset($city_edit) || isset($city_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-map-pin ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.cities') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($city_edit) || isset($city_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.cities.index') }}">{{ __('admin.all_cities') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($city_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.cities.create') }}">{{ __('admin.add_city') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($category_create) || isset($category_edit) || isset($category_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-sitemap ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.categories') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($category_edit) || isset($category_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.categories.index') }}">{{ __('admin.all_categories') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($category_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.categories.create') }}">{{ __('admin.add_category') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($sub_category_create) || isset($sub_category_edit) || isset($sub_category_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-qrcode ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.sub_categories') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($sub_category_edit) || isset($sub_category_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.sub.categories.index') }}">{{ __('admin.all_sub_categories') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($sub_category_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.sub.categories.create') }}">{{ __('admin.add_sub_category') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($service_create) || isset($service_edit) || isset($service_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fab fa-codepen ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.services') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($service_edit) || isset($service_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.services.index') }}">{{ __('admin.all_services') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($service_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.services.create') }}">{{ __('admin.add_service') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($slider_create) || isset($slider_edit) || isset($slider_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-sliders ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.sliders') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($slider_edit) || isset($slider_delete))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.sliders.index') }}">{{ __('admin.all_sliders') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($slider_create))
                                <li><a class="slide-item"
                                        href="{{ route('services_app.admin.sliders.create') }}">{{ __('admin.add_slider') }}</a>
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
