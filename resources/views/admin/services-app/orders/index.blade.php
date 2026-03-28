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
                    <form action="{{ route('services_app.admin.orders.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="status">{{ __('admin.statuses') }}</label>
                                    <select class="form-control form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                        <option value="" selected>{{ __('admin.all_orders') }}</option>
                                        @php
                                            $i = 0;
                                            if (auth()->user()->role_id == '7') {
                                                $i = 1;
                                            }
                                        @endphp
                                        @for (; $i < 5; $i++)
                                            <option value="{{ $i }}" @selected(isset($_GET['status']) && $_GET['status'] == "$i")>
                                                {{ __("admin.status_$i") }}</option>
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
                    @component('components.errors')
                        @slot('id')
                        @endslot
                    @endcomponent
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order_id') }}</th>
                                <th class="fw-bold">{{ __('admin.payment_type') }}</th>
                                <th class="fw-bold">{{ __('admin.transaction_id') }}</th>
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
                                    <td class="align-middle">
                                        {{ isset($order->payment_type) ? __("admin.payment_type_$order->payment_type") : __('admin.not_found') }}
                                    </td>
                                    <td class="align-middle">{{ $order->transaction_id ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->client_name }}</td>
                                    <td class="align-middle">{{ $order->worker_name ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->category_name }}</td>
                                    <td class="align-middle">{{ $order->sub_category_name }}</td>
                                    <td class="align-middle">{{ $order->city_name }}</td>
                                    <td class="align-middle">{{ $order->management_ratio }}</td>
                                    <td class="align-middle">{{ $order->deposit_ratio }}</td>
                                    <td class="align-middle">{{ $order->vat }}</td>
                                    <td class="align-middle">{{ $order->total ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $order->date }}</td>
                                    <td class="align-middle">{{ $order->time }}</td>
                                    <td class="align-middle">{{ __("admin.status_$order->status") }}</td>
                                    <td class="align-middle">
                                        <a class="btn btn-outline-secondary bg-warning text-dark btn-sm ml-2"
                                            title="{{ __('admin.view') }}"
                                            href="{{ route('services_app.admin.orders.show', [$order->id]) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->role_id != '7')
                                            <button type="submit"
                                                class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                title="{{ __('admin.delete') }}" data-effect="effect-newspaper"
                                                data-toggle="modal" href="#myModal{{ $order->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @else
                                            @if ($order->status == '1' && is_null($order->worker_name))
                                                <button type="submit"
                                                    class="modal-effect btn btn-outline-secondary bg-primary text-white btn-sm"
                                                    title="{{ __('admin.assign_worker') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#editModal{{ $order->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    @if (auth()->user()->role_id != '7')
                                        <div class="modal" id="myModal{{ $order->id }}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('admin.delete_order') }}
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal"
                                                            type="button"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ __('admin.delete_order_message') }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form class="d-inline"
                                                            action="{{ route('services_app.admin.orders.destroy') }}"
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
                                    @else
                                        @if ($order->status == '1' && is_null($order->worker_name))
                                            <div class="modal" id="editModal{{ $order->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.assign_worker') }}
                                                            </h5>
                                                            <button aria-label="Close" class="close"
                                                                data-dismiss="modal" type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('services_app.admin.orders.assign.worker') }}"
                                                                method="POST" id="editForm{{ $order->id }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $order->id }}" />
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="worker_id">{{ __('admin.workers') }}</label>
                                                                            <select class="form-control form-select"
                                                                                id="worker_id" name="worker_id" required>
                                                                                <option value="" selected disabled>
                                                                                    {{ __('admin.select_worker') }}
                                                                                </option>
                                                                                @foreach ($workers as $worker)
                                                                                    <option value="{{ $worker->id }}"
                                                                                        @selected(old('worker_id', $order->worker_id) == $worker->id)>
                                                                                        {{ $worker->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary waves-effect"
                                                                data-dismiss="modal">{{ __('admin.back') }}</button>
                                                            <button form="editForm{{ $order->id }}" type="submit"
                                                                class="btn btn-primary waves-effect waves-light">{{ __('admin.assign') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                </div>
                @endif
                @endif
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
