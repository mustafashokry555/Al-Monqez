@extends('layouts.master')
@section('title')
    {{ __('admin.all_orders') }}
@endsection
@section('css')
    <!---Internal Owl Carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <!---Internal  Multislider css-->
    <link href="{{ URL::asset('assets/plugins/multislider/multislider.css') }}" rel="stylesheet">
    <!--- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .pagination-box {
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.all_orders') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.orders') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('store_app.admin.orders.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="status">{{ __('admin.statuses') }}</label>
                                    <select class="form-control form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                        <option value="" selected>{{ __('admin.all_orders') }}</option>
                                        @for ($i = 0; $i <= 5; $i++)
                                            <option value="{{ $i }}" @selected(isset($_GET['status']) && $_GET['status'] == "$i")>
                                                {{ __("admin.store_order_status_$i") }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">{{ __('admin.execution') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.all_orders') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            order_id
                        @endslot
                    @endcomponent
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
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
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
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <a class="btn btn-outline-secondary bg-warning text-dark btn-sm ml-2"
                                                title="{{ __('admin.view') }}"
                                                href="{{ route('store_app.admin.orders.show', [$order->id]) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($order->status < 2)
                                                <button type="submit"
                                                    class="modal-effect btn btn-outline-secondary bg-primary text-white btn-sm ml-2"
                                                    title="{{ __('admin.change_status') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#myModal{{ $order->id }}">
                                                    {{ __('admin.change_status') }}
                                                </button>
                                            @endif
                                            <button type="submit"
                                                class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                title="{{ __('admin.delete') }}" data-effect="effect-newspaper"
                                                data-toggle="modal" href="#myModal{{ $order->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        @if ($order->status < 2)
                                            <div class="modal" id="myModal{{ $order->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                {{ __('admin.change_order_status') }}
                                                            </h5>
                                                            <button aria-label="Close" class="close"
                                                                data-dismiss="modal" type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __("admin.change_order_status_to_$order->status") }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('store_app.admin.orders.process') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $order->id }}" />
                                                                <button type="button"
                                                                    class="btn btn-secondary waves-effect"
                                                                    data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-light">{{ __('admin.change') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="modal" id="myModal{{ $order->id }}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('admin.delete_order') }}
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal"
                                                            type="button"><span
                                                                aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ __('admin.delete_order_message') }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form class="d-inline"
                                                            action="{{ route('store_app.admin.orders.destroy') }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('Delete')
                                                            <input type="hidden" name="order_id"
                                                                value="{{ $order->id }}" />
                                                            <button type="button" class="btn btn-secondary waves-effect"
                                                                data-dismiss="modal">{{ __('admin.back') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-danger waves-effect waves-light">{{ __('admin.delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
@endsection
