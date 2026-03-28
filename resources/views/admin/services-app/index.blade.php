@extends('layouts.master')
@section('title')
    {{ __('admin.services_app_dashboard') }}
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
                                <h2 class="text-white mb-0">{{ $total_revenue }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->role_id != '7')
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('services_app.admin.companies.index') }}">
                    <div class="card bg-purple-gradient text-white ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fa fa-building tx-40"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mt-0 text-center">
                                        <span class="text-white">{{ __('admin.total_companies') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_companies }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('services_app.admin.workers.index') }}">
                <div class="card bg-success-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-users tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_workers') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_workers }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @if (auth()->user()->role_id != '7')
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('services_app.admin.orders.index', ['status' => 0]) }}">
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
        @endif
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('services_app.admin.orders.index', ['status' => 1]) }}">
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
                                    <span class="text-white">{{ __('admin.total_accepted_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_accepted_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('services_app.admin.orders.index', ['status' => 2]) }}">
                <div class="card bg-primary-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fa fa-street-view tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_in_process_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_in_process_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('services_app.admin.orders.index', ['status' => 3]) }}">
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
                                    <span class="text-white">{{ __('admin.total_finished_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_finished_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('services_app.admin.orders.index', ['status' => 4]) }}">
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
                                    <span class="text-white">{{ __('admin.total_canceled_orders') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_canceled_orders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @if (auth()->user()->role_id != '7')
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('services_app.admin.categories.index') }}">
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
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('services_app.admin.sub.categories.index') }}">
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
                                        <span class="text-white">{{ __('admin.total_sub_categories') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_sub_categories }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-xl-4 col-12">
                <a href="{{ route('services_app.admin.services.index') }}">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fab fa-codepen tx-40"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mt-0 text-center">
                                        <span class="text-white">{{ __('admin.total_services') }}</span>
                                        <h2 class="text-white mb-0">{{ $total_services }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
    @if (auth()->user()->role_id != '7')
        <div class="row row-sm">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-striped table-responsive border-0 pb-0">
                        <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_companies') }}</h4>
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
                                @if (count($companies) == 0)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @endif
                                @foreach ($companies as $count => $company)
                                    <tr data-id="{{ $count + 1 }}">
                                        <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                        <td class="align-middle">{{ $company->name }}</td>
                                        <td class="align-middle">{{ $company->phone }}</td>
                                        <td class="align-middle">
                                            {{ $company->email ? $company->email : __('admin.unknown') }}
                                        </td>
                                        <td class="align-middle">{{ __("admin.blocked_$company->blocked") }}</td>
                                        <td class="align-middle">{{ $company->created_at }}</td>
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
                    <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_workers') }}</h4>
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
                            @if (count($workers) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($workers as $count => $worker)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $worker->name }}</td>
                                    <td class="align-middle">{{ $worker->phone }}</td>
                                    <td class="align-middle">{{ $worker->email ? $worker->email : __('admin.unknown') }}
                                    </td>
                                    <td class="align-middle">{{ __("admin.blocked_$worker->blocked") }}</td>
                                    <td class="align-middle">{{ $worker->created_at }}</td>
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
                    <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_orders') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order_id') }}</th>
                                <th class="fw-bold">{{ __('admin.client_name') }}</th>
                                <th class="fw-bold">{{ __('admin.worker_name') }}</th>
                                <th class="fw-bold">{{ __('admin.category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.sub_category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.city_name') }}</th>
                                <th class="fw-bold">{{ __('admin.management_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.deposit_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.vat') }}</th>
                                <th class="fw-bold">{{ __('admin.price') }}</th>
                                <th class="fw-bold">{{ __('admin.date') }}</th>
                                <th class="fw-bold">{{ __('admin.time') }}</th>
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
                                    <td class="align-middle">{{ $order->client_name }}</td>
                                    <td class="align-middle">
                                        {{ $order->worker_name ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->category_name }}</td>
                                    <td class="align-middle">{{ $order->sub_category_name }}</td>
                                    <td class="align-middle">{{ $order->city_name }}</td>
                                    <td class="align-middle">{{ $order->management_ratio }}</td>
                                    <td class="align-middle">{{ $order->deposit_ratio }}</td>
                                    <td class="align-middle">{{ $order->vat }}</td>
                                    <td class="align-middle">{{ $order->total ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->dateFormatted }}</td>
                                    <td class="align-middle">{{ $order->timeFormatted }}</td>
                                    <td class="align-middle">{{ __("admin.status_$order->status") }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $('.map-btn').click(function(event) {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                showMap(lat, lng);
            });
        });

        function showMap(lat, lng) {
            var url = "https://maps.google.com/?q=" + lat + "," + lng;
            window.open(url);
        }
    </script>
@endsection
