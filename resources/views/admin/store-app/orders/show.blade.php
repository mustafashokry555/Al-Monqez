@extends('layouts.master')
@section('title')
    {{ __('admin.order_details') }}
@endsection
@section('content')

    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
                href="{{ route('store_app.admin.orders.index') }}">
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
                                <th class="fw-bold">{{ __('admin.coupon_code') }}</th>
                                <th class="fw-bold">{{ __('admin.discount_percentage') }}</th>
                                <th class="fw-bold">{{ __('admin.max_discount_amount') }}</th>
                                <th class="fw-bold">{{ __('admin.transaction_id') }}</th>
                                <th class="fw-bold">{{ __('admin.address') }}</th>
                                <th class="fw-bold">{{ __('admin.location') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                                <th class="fw-bold">{{ __('admin.order_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">{{ $order->id }}</td>
                                @if ($order->coupon_code)
                                    <td class="align-middle">{{ $order->coupon_code }}</td>
                                    <td class="align-middle">{{ $order->discount_percentage }}%</td>
                                    <td class="align-middle">{{ $order->max_discount_amount }}</td>
                                @else
                                    <td class="align-middle text-center" colspan="3">{{ __('admin.not_found') }}</td>
                                @endif
                                <td class="align-middle">{{ $order->transaction_id ?? __('admin.not_found') }}</td>
                                <td class="align-middle">{{ $order->address }}</td>
                                <td class="align-middle">
                                    <a target="_blank"
                                        href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}">
                                        {{ __('admin.preview') }}
                                    </a>
                                </td>
                                <td class="align-middle">{{ $order->created_at }}</td>
                                <td class="align-middle">{{ __("admin.store_order_status_$order->status") }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body table-responsive border-0 pt-0">
                    <h4 class="mb-3 text-center">{{ __('admin.order_summary') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.sub_total_price') }}</th>
                                <th class="fw-bold">{{ __('admin.discount') }}</th>
                                <th class="fw-bold">{{ __('admin.discounted_price') }}</th>
                                <th class="fw-bold">{{ __('admin.vat') }}</th>
                                <th class="fw-bold">{{ __('admin.vat_value') }}</th>
                                <th class="fw-bold">{{ __('admin.delivery_charge') }}</th>
                                <th class="fw-bold">{{ __('admin.total') }}</th>
                                <th class="fw-bold">{{ __('admin.management_ratio') }}</th>
                                <th class="fw-bold">{{ __('admin.management_dues') }}</th>
                                <th class="fw-bold">{{ __('admin.store_dues') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">{{ $order->sub_total }}</td>
                                @if ($order->coupon_code)
                                    <td class="align-middle">{{ $order->discount_amount }}</td>
                                    <td class="align-middle">{{ $order->discounted_total }}</td>
                                @else
                                    <td class="align-middle text-center" colspan="2">{{ __('admin.not_found') }}</td>
                                @endif
                                <td class="align-middle">{{ $order->vat }}%</td>
                                <td class="align-middle">{{ $order->vat_amount }}</td>
                                <td class="align-middle">{{ $order->delivery_charge }}</td>
                                <td class="align-middle">{{ $order->total }}</td>
                                <td class="align-middle">{{ $order->management_ratio }}</td>
                                <td class="align-middle">{{ $order->management_amount }}</td>
                                <td class="align-middle">{{ $order->store_dues }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body table-responsive border-0 pt-0">
                    <h4 class="mb-3 text-center">{{ __('admin.products') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">{{ __('admin.image') }}</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.quantity') }}</th>
                                <th class="fw-bold">{{ __('admin.price') }}</th>
                                <th class="fw-bold">{{ __('admin.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr>
                                    <td class="align-middle">
                                        <a href="{{ $product->imageLink }}" target="_blanck">
                                            <img src="{{ $product->imageLink }}" alt="{{ __('admin.image') }}"
                                                style="width: 20px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $product->name }}</td>
                                    <td class="align-middle">{{ $product->quantity }}</td>
                                    <td class="align-middle">{{ $product->price }}</td>
                                    <td class="align-middle">{{ $product->quantity * $product->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mx-3">
                    <div class="card-body table-responsive border-0 p-0 px-2 col-lg-4">
                        <h4 class="mb-3 text-center">{{ __('admin.store') }}</h4>
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
                                @if (!$order->store_name)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="align-middle">
                                            <a href="{{ $order->store_image_link }}" target="_blanck">
                                                <img src="{{ $order->store_image_link }}" alt="{{ __('admin.image') }}"
                                                    style="width: 20px;" />
                                            </a>
                                        </td>
                                        <td class="align-middle">{{ $order->store_name }}</td>
                                        <td class="align-middle">{{ $order->store_phone }}</td>
                                        <td class="align-middle">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= ceil($order->store_rating))
                                                    <span><i class="fa fa-star" style="color: #fbbc05;"></i></span>
                                                @else
                                                    <span><i class="fa fa-star"></i></span>
                                                @endif
                                            @endfor
                                            ({{ $order->store_rating }})
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body table-responsive border-0 p-0 px-2 col-lg-4">
                        <h4 class="mb-3 text-center">{{ __('admin.delivery_driver') }}</h4>
                        <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th class="fw-bold">{{ __('admin.image') }}</th>
                                    <th class="fw-bold">{{ __('admin.name') }}</th>
                                    <th class="fw-bold">{{ __('admin.phone') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$order->driver_name)
                                    <tr class="align-middle">
                                        <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="align-middle">
                                            <a href="{{ $order->driver_image_link }}" target="_blanck">
                                                <img src="{{ $order->driver_image_link }}" alt="{{ __('admin.image') }}"
                                                    style="width: 20px;" />
                                            </a>
                                        </td>
                                        <td class="align-middle">{{ $order->driver_name }}</td>
                                        <td class="align-middle">{{ $order->driver_phone }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body table-responsive border-0 p-0 px-2 col-lg-4">
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
            </div>
        </div> <!-- end col -->
    </div>

@endsection
