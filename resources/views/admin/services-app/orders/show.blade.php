@extends('layouts.master')
@section('title')
    {{ __('admin.order_details') }}
@endsection
@section('content')

    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('services_app.admin.orders.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.orders') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive border-0">
                    <h4 class="mb-3 text-center">{{ __('admin.order_details') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.order_id') }}</th>
                                <th class="fw-bold">{{ __('admin.payment_type') }}</th>
                                <th class="fw-bold">{{ __('admin.coupon_code') }}</th>
                                <th class="fw-bold">{{ __('admin.discount_percentage') }}</th>
                                <th class="fw-bold">{{ __('admin.max_discount_amount') }}</th>
                                <th class="fw-bold">{{ __('admin.transaction_id') }}</th>
                                <th class="fw-bold">{{ __('admin.category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.sub_category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.description') }}</th>
                                <th class="fw-bold">{{ __('admin.city_name') }}</th>
                                <th class="fw-bold">{{ __('admin.date') }}</th>
                                <th class="fw-bold">{{ __('admin.time') }}</th>
                                <th class="fw-bold">{{ __('admin.order_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">{{ $order->id }}</td>
                                <td class="align-middle">
                                    {{ isset($order->payment_type) ? __("admin.payment_type_$order->payment_type") : __('admin.not_found') }}
                                </td>
                                @if ($order->coupon_code)
                                    <td class="align-middle">{{ $order->coupon_code }}</td>
                                    <td class="align-middle">{{ $order->discount_percentage }}%</td>
                                    <td class="align-middle">{{ $order->max_discount_amount }}</td>
                                @else
                                    <td class="align-middle text-center" colspan="3">{{ __('admin.not_found') }}</td>
                                @endif
                                <td class="align-middle">{{ $order->transaction_id ?? __('admin.not_found') }}</td>
                                <td class="align-middle">{{ $order->category_name }}</td>
                                <td class="align-middle">{{ $order->sub_category_name }}</td>
                                <td class="align-middle" style="max-width: 200px; text-wrap: auto;">
                                    {{ $order->description }}</td>
                                <td class="align-middle">{{ $order->city_name }}</td>
                                <td class="align-middle">{{ $order->date }}</td>
                                <td class="align-middle">{{ $order->time }}</td>
                                <td class="align-middle">{{ __("admin.status_$order->status") }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body table-responsive border-0 pt-0">
                    <h4 class="mb-3 text-center">{{ __('admin.order_summary') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.price') }}</th>
                                <th class="fw-bold">{{ __('admin.discount') }}</th>
                                <th class="fw-bold">{{ __('admin.discounted_price') }}</th>
                                <th class="fw-bold">{{ __('admin.vat') }}</th>
                                <th class="fw-bold">{{ __('admin.vat_value') }}</th>
                                <th class="fw-bold">{{ __('admin.price_with_vat') }}</th>
                                <th class="fw-bold">{{ __('admin.deposit_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.deposit_with_vat') }}</th>
                                <th class="fw-bold">{{ __('admin.e_paid_amount') }}</th>
                                <th class="fw-bold">{{ __('admin.cash_paid_amount') }}</th>
                                <th class="fw-bold">{{ __('admin.management_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.management_dues') }}</th>
                                <th class="fw-bold">{{ __('admin.worker_dues') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">{{ $order->total }}</td>
                                @if ($order->coupon_code)
                                    <td class="align-middle">{{ $order->discount }}</td>
                                    <td class="align-middle">{{ $order->discounted_price }}</td>
                                @else
                                    <td class="align-middle text-center" colspan="2">{{ __('admin.not_found') }}</td>
                                @endif
                                <td class="align-middle">{{ $order->vat }}%</td>
                                <td class="align-middle">{{ $order->vat_value }}</td>
                                <td class="align-middle">{{ $order->total_price }}</td>
                                <td class="align-middle">{{ $order->deposit_ratio }}%</td>
                                <td class="align-middle">{{ $order->deposit_price }}</td>
                                <td class="align-middle">{{ $order->e_paid_amount }}</td>
                                <td class="align-middle">{{ $order->cash_paid_amount }}</td>
                                <td class="align-middle">{{ $order->management_ratio }}</td>
                                <td class="align-middle">{{ $order->management_value }}</td>
                                <td class="align-middle">{{ $order->worker_dues }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body table-responsive border-0 pt-0">
                    <h4 class="mb-3 text-center">{{ __('admin.services') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.image') }}</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->services as $service)
                                <tr>
                                    <td class="align-middle">
                                        <a href="{{ $service->imageLink }}" target="_blanck">
                                            <img src="{{ $service->imageLink }}" alt="{{ __('admin.image') }}"
                                                style="width: 20px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $service->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mx-3">
                    <div class="card-body table-responsive border-0 p-0 px-2 col-lg-6">
                        <h4 class="mb-3 text-center">{{ __('admin.worker') }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th class="fw-bold">{{ __('admin.image') }}</th>
                                    <th class="fw-bold">{{ __('admin.name') }}</th>
                                    <th class="fw-bold">{{ __('admin.phone') }}</th>
                                    <th class="fw-bold">{{ __('admin.rating') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$order->worker_name)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="align-middle">
                                            <a href="{{ $order->worker_image_link }}" target="_blanck">
                                                <img src="{{ $order->worker_image_link }}" alt="{{ __('admin.image') }}"
                                                    style="width: 20px;" />
                                            </a>
                                        </td>
                                        <td class="align-middle">{{ $order->worker_name }}</td>
                                        <td class="align-middle">{{ $order->worker_phone }}</td>
                                        <td class="align-middle">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= ceil($order->worker_rating))
                                                    <span><i class="fa fa-star" style="color: #fbbc05;"></i></span>
                                                @else
                                                    <span><i class="fa fa-star"></i></span>
                                                @endif
                                            @endfor
                                            ({{ $order->worker_rating }})
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body table-responsive border-0 p-0 px-2 col-lg-6">
                        <h4 class="mb-3 text-center">{{ __('admin.client') }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th class="fw-bold">{{ __('admin.image') }}</th>
                                    <th class="fw-bold">{{ __('admin.name') }}</th>
                                    <th class="fw-bold">{{ __('admin.phone') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$order->client_name)
                                    <tr class="align-middle">
                                        <td colspan="15" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="align-middle">
                                            <a href="{{ $order->client_image_link }}" target="_blanck">
                                                <img src="{{ $order->client_image_link }}" alt="{{ __('admin.image') }}"
                                                    style="width: 20px;" />
                                            </a>
                                        </td>
                                        <td class="align-middle">{{ $order->client_name }}</td>
                                        <td class="align-middle">{{ $order->client_phone }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-body table-responsive border-0 pt-0">
                    <h4 class="mb-3 text-center">{{ __('admin.order_locations') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.type') }}</th>
                                <th class="fw-bold">{{ __('admin.title') }}</th>
                                <th class="fw-bold">{{ __('admin.location') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->locations as $location)
                                <tr>
                                    <td class="align-middle">{{ __("admin.point_type_$location->type") }}</td>
                                    <td class="align-middle">{{ $location->title }}</td>
                                    <td class="align-middle">
                                        <a target="_blank"
                                            href="https://www.google.com/maps/search/?api=1&query={{ $location->latitude }},{{ $location->longitude }}">
                                            {{ __('admin.preview') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($order->problemImages) > 0)
                    <div class="card-body table-responsive border-0 pt-0">
                        <h4 class="mb-3 text-center">{{ __('admin.order_problem_images') }}</h4>
                        <div class="mt-2 w-100 d-flex flex-wrap">
                            @foreach ($order->problemImages as $image)
                                <a href="{{ $image->imageLink }}" target="_blanck">
                                    <img src="{{ $image->imageLink }}" alt="{{ __('admin.image') }}"
                                        class="img-thumbnail wd-100p wd-sm-200" style="height:150px;" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if (count($order->beforeImages) > 0)
                    <div class="card-body table-responsive border-0 pt-0">
                        <h4 class="mb-3 text-center">{{ __('admin.order_before_images') }}</h4>
                        <div class="mt-2 w-100 d-flex flex-wrap">
                            @foreach ($order->beforeImages as $image)
                                <a href="{{ $image->imageLink }}" target="_blanck">
                                    <img src="{{ $image->imageLink }}" alt="{{ __('admin.image') }}"
                                        class="img-thumbnail wd-100p wd-sm-200" style="height:150px;" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if (count($order->afterImages) > 0)
                    <div class="card-body table-responsive border-0 pt-0">
                        <h4 class="mb-3 text-center">{{ __('admin.order_after_images') }}</h4>
                        <div class="mt-2 w-100 d-flex flex-wrap">
                            @foreach ($order->afterImages as $image)
                                <a href="{{ $image->imageLink }}" target="_blanck">
                                    <img src="{{ $image->imageLink }}" alt="{{ __('admin.image') }}"
                                        class="img-thumbnail wd-100p wd-sm-200" style="height:150px;" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div> <!-- end col -->
    </div>

@endsection
