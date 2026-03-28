@extends('layouts.master')
@section('title')
    {{ __('admin.store_app_dashboard') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">{{ __('admin.welcome_back') }}</h2>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-lg-6 col-xl-4 col-12">
            <div class="card bg-danger-gradient text-white ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon1 mt-2 text-center">
                                <i class="fab fa-btc tx-40"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mt-0 text-center">
                                <span class="text-white">{{ __('admin.total_revenue') }}</span>
                                <h2 class="text-white mb-0">{{ number_format((float) $total_revenue, 2, '.', '') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->role_id != '6')
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('store_app.admin.stores.index') }}">
                    <div class="card bg-success-gradient text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-shopping-bag tx-40"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mt-0 text-center">
                                        <span class="text-white">{{ __('admin.total_stores') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_stores }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('store_app.admin.drivers.index') }}">
                    <div class="card bg-primary-gradient text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-truck tx-40"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mt-0 text-center">
                                        <span class="text-white">{{ __('admin.total_delivery_drivers') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_drivers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('store_app.admin.categories.index') }}">
                    <div class="card bg-pink-gradient text-white ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fa fa-sitemap tx-40"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mt-0 text-center">
                                        <span class="text-white">{{ __('admin.total_categories') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_categories }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 0]) }}">
                <div class="card bg-dark text-white ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_pended_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_pended_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 1]) }}">
                <div class="card bg-secondary-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_under_preparation_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_under_preparation_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 2]) }}">
                <div class="card bg-info-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_prepared_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_prepared_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 3]) }}">
                <div class="card bg-warning-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_in_delivery_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_in_delivery_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 4]) }}">
                <div class="card bg-teal-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_delivered_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_delivered_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.orders.index', ['status' => 5]) }}">
                <div class="card bg-danger-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_canceled_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_canceled_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.classifications.index') }}">
                <div class="card bg-purple-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-qrcode tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_classifications') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_classifications }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.products.index') }}">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-box tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_products') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_products }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('store_app.admin.coupons.index') }}">
                <div class="card bg-info-gradient text-white ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-tags tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_coupons') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_coupons }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @if (auth()->user()->role_id != '6')
        <div class="row row-sm">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-striped table-responsive border-0 pb-0">
                        <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_stores') }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th class="fw-bold">#</th>
                                    <th class="fw-bold">{{ __('admin.name') }}</th>
                                    <th class="fw-bold">{{ __('admin.phone') }}</th>
                                    <th class="fw-bold">{{ __('admin.email') }}</th>
                                    <th class="fw-bold">{{ __('admin.account_status') }}</th>
                                    <th class="fw-bold">{{ __('admin.joining_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($stores) == 0)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @endif
                                @foreach ($stores as $count => $store)
                                    <tr data-id="{{ $count + 1 }}">
                                        <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                        <td class="align-middle">{{ $store->name }}</td>
                                        <td class="align-middle">{{ $store->phone }}</td>
                                        <td class="align-middle">{{ $store->email ? $store->email : __('admin.unknown') }}
                                        </td>
                                        <td class="align-middle">{{ __("admin.blocked_$store->blocked") }}</td>
                                        <td class="align-middle">{{ $store->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-striped table-responsive border-0 pb-0">
                        <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_delivery_drivers') }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th class="fw-bold">#</th>
                                    <th class="fw-bold">{{ __('admin.name') }}</th>
                                    <th class="fw-bold">{{ __('admin.phone') }}</th>
                                    <th class="fw-bold">{{ __('admin.email') }}</th>
                                    <th class="fw-bold">{{ __('admin.account_status') }}</th>
                                    <th class="fw-bold">{{ __('admin.joining_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($drivers) == 0)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @endif
                                @foreach ($drivers as $count => $driver)
                                    <tr data-id="{{ $count + 1 }}">
                                        <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                        <td class="align-middle">{{ $driver->name }}</td>
                                        <td class="align-middle">{{ $driver->phone }}</td>
                                        <td class="align-middle">
                                            {{ $driver->email ? $driver->email : __('admin.unknown') }}
                                        </td>
                                        <td class="align-middle">{{ __("admin.blocked_$driver->blocked") }}</td>
                                        <td class="align-middle">{{ $driver->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row row-sm">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-striped table-responsive border-0 pb-0">
                    <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_orders') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order_id') }}</th>
                                @if (auth()->user()->role_id != '6')
                                    <th class="fw-bold">{{ __('admin.store') }}</th>
                                @endif
                                <th class="fw-bold">{{ __('admin.client_name') }}</th>
                                <th class="fw-bold">{{ __('admin.worker_name') }}</th>
                                <th class="fw-bold">{{ __('admin.address') }}</th>
                                <th class="fw-bold">{{ __('admin.location') }}</th>
                                <th class="fw-bold">{{ __('admin.management_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.vat') }}</th>
                                <th class="fw-bold">{{ __('admin.delivery_charge') }}</th>
                                <th class="fw-bold">{{ __('admin.sub_total_price') }}</th>
                                <th class="fw-bold">{{ __('admin.coupon_code') }}</th>
                                <th class="fw-bold">{{ __('admin.discount') }}</th>
                                <th class="fw-bold">{{ __('admin.total') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                                <th class="fw-bold">{{ __('admin.order_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($orders as $count => $order)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $order->id }}</td>
                                    @if (auth()->user()->role_id != '6')
                                        <td class="align-middle">{{ $order->store_name }}</td>
                                    @endif
                                    <td class="align-middle">{{ $order->client_name }}</td>
                                    <td class="align-middle">
                                        {{ $order->driver_name ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->address }}</td>
                                    <td class="align-middle">
                                        <a target="_blank"
                                            href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}">
                                            {{ __('admin.preview') }}
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $order->management_ratio }}</td☻>
                                    <td class="align-middle">{{ $order->vat }}</td>
                                    <td class="align-middle">{{ $order->delivery_charge }}</td>
                                    <td class="align-middle">{{ $order->sub_total }}</td>
                                    @if ($order->coupon_code == null)
                                        <td class="align-middle text-center" colspan="2">{{ __('admin.not_found') }}
                                        </td>
                                    @else
                                        <td class="align-middle">{{ $order->coupon_code }}</td>
                                        <td class="align-middle">{{ $order->discount_amount }}</td>
                                    @endif
                                    <td class="align-middle">{{ $order->total }}</td>
                                    <td class="align-middle">{{ $order->created_at }}</td>
                                    <td class="align-middle">{{ __("admin.store_order_status_$order->status") }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
