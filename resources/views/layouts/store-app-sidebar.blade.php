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

            @if (auth()->user()->role_id != '6')
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
            @endif

            <li class="slide">
                <a class="side-menu__item" href="{{ route('store_app.admin') }}"><i class="fe fe-home ml-3"
                        style="font-size: 16px"></i><span
                        class="side-menu__label">{{ __('admin.store_app_dashboard') }}</span></a>
            </li>

            @if (isset($super_admin) ||
                    isset($store_create) ||
                    isset($store_edit) ||
                    isset($store_delete) ||
                    isset($driver_create) ||
                    isset($driver_edit) ||
                    isset($driver_delete))
                <li class="side-item side-item-category">{{ __('admin.users') }}</li>

                @if (isset($super_admin) || isset($store_create) || isset($store_edit) || isset($store_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-shopping-bag ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.stores') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($store_edit) || isset($store_delete))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.stores.index') }}">{{ __('admin.all_stores') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($store_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.stores.create') }}">{{ __('admin.add_store') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (isset($super_admin) || isset($driver_create) || isset($driver_edit) || isset($driver_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fe fe-truck ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.delivery_drivers') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($driver_edit) || isset($driver_delete))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.drivers.index') }}">{{ __('admin.all_delivery_drivers') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($driver_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.drivers.create') }}">{{ __('admin.add_delivery_driver') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (isset($super_admin) ||
                    isset($coupon_create) ||
                    isset($coupon_edit) ||
                    isset($coupon_delete) ||
                    isset($order_control))
                <li class="side-item side-item-category">{{ __('admin.commerce') }}</li>

                @if (isset($super_admin) || isset($coupon_create) || isset($coupon_edit) || isset($coupon_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-tags ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.coupons') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($coupon_edit) || isset($coupon_delete))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.coupons.index') }}">{{ __('admin.all_coupons') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($coupon_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.coupons.create') }}">{{ __('admin.add_coupon') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) || isset($store_order_control))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('store_app.admin.orders.index') }}"><i
                                class="fa fa-street-view ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.orders') }}</span></a>
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
                    isset($classification_create) ||
                    isset($classification_edit) ||
                    isset($classification_delete) ||
                    isset($product_create) ||
                    isset($product_edit) ||
                    isset($product_delete) ||
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
                                    href="{{ route('store_app.admin.settings.index') }}">{{ __('admin.order_settings') }}</a>
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
                                        href="{{ route('store_app.admin.cities.index') }}">{{ __('admin.all_cities') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($city_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.cities.create') }}">{{ __('admin.add_city') }}</a>
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
                                        href="{{ route('store_app.admin.categories.index') }}">{{ __('admin.all_categories') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($category_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.categories.create') }}">{{ __('admin.add_category') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (isset($super_admin) ||
                        isset($classification_create) ||
                        isset($classification_edit) ||
                        isset($classification_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-qrcode ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.classifications') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($classification_edit) || isset($classification_delete))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.classifications.index') }}">{{ __('admin.all_classifications') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($classification_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.classifications.create') }}">{{ __('admin.add_classification') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                    @if (isset($super_admin) || isset($patches_control))
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                    class="fa fa-tags ml-3" style="font-size: 16px"></i><span
                                    class="side-menu__label">{{ __('admin.patches') }}</span><i
                                    class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                @if (isset($super_admin) || isset($patches_control))
                                    <li><a class="slide-item"
                                            href="{{ route('store_app.admin.patches.index') }}">{{ __('admin.all_patches') }}</a>
                                    </li>
                                @endif
                                @if (isset($super_admin) || isset($patches_control))
                                    <li><a class="slide-item"
                                            href="{{ route('store_app.admin.patches.create') }}">{{ __('admin.add_patch') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                @if (isset($super_admin) || isset($product_create) || isset($product_edit) || isset($product_delete))
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="javascript:void();"><i
                                class="fa fa-box ml-3" style="font-size: 16px"></i><span
                                class="side-menu__label">{{ __('admin.products') }}</span><i
                                class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @if (isset($super_admin) || isset($product_edit) || isset($product_delete))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.products.index') }}">{{ __('admin.all_products') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($product_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.products.create') }}">{{ __('admin.add_product') }}</a>
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
                                        href="{{ route('store_app.admin.sliders.index') }}">{{ __('admin.all_sliders') }}</a>
                                </li>
                            @endif
                            @if (isset($super_admin) || isset($slider_create))
                                <li><a class="slide-item"
                                        href="{{ route('store_app.admin.sliders.create') }}">{{ __('admin.add_slider') }}</a>
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
