@extends('layouts.master')
@section('title')
    {{ __('admin.coupons') }}
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
            {{ __('admin.all_coupons') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.coupons') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_coupons') }}</h4>
                    @if (isset($super_admin) || isset($coupon_create))
                        <a href="{{ route('store_app.admin.coupons.create') }}" class="btn btn-primary button-icon"><i
                                class="fe fe-plus ml-2 font-weight-bolder"></i>{{ __('admin.add_coupon') }}</a>
                    @endif
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            coupon_id
                        @endslot
                    @endcomponent
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                @if (auth()->user()->role_id != 6)
                                    <th class="fw-bold">{{ __('admin.store') }}</th>
                                @endif
                                <th class="fw-bold">{{ __('admin.coupon_code') }}</th>
                                <th class="fw-bold">{{ __('admin.discount_percentage') }}</th>
                                <th class="fw-bold">{{ __('admin.max_discount_amount') }}</th>
                                <th class="fw-bold">{{ __('admin.valid_from') }}</th>
                                <th class="fw-bold">{{ __('admin.valid_until') }}</th>
                                <th class="fw-bold">{{ __('admin.status') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($coupons) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($coupons as $count => $coupon)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    @if (auth()->user()->role_id != 6)
                                        <td class="align-middle">{{ $coupon->store_name }}</td>
                                    @endif
                                    <td class="align-middle">{{ $coupon->code }}</td>
                                    <td class="align-middle">{{ $coupon->discount_percentage }}%</td>
                                    <td class="align-middle">{{ $coupon->max_discount_amount }}</td>
                                    <td class="align-middle">{{ $coupon->valid_from }}</td>
                                    <td class="align-middle">{{ $coupon->valid_until }}</td>
                                    <td
                                        class="align-middle @if ($coupon->is_active) bg-success @else bg-danger @endif text-white">
                                        {{ __("admin.active_$coupon->is_active") }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            @if (isset($super_admin) || isset($coupon_edit))
                                                <a class="btn btn-outline-secondary bg-warning text-dark btn-sm ml-2"
                                                    title="{{ __('admin.edit') }}"
                                                    href="{{ route('store_app.admin.coupons.edit', [$coupon->id]) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                            @if (isset($super_admin) || isset($coupon_delete))
                                                <button type="submit"
                                                    class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                    title="{{ __('admin.delete') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#myModal{{ $coupon->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if (isset($super_admin) || isset($coupon_delete))
                                            <div class="modal" id="myModal{{ $coupon->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.delete_coupon') }}</h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.delete_coupon_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('store_app.admin.coupons.destroy') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('Delete')
                                                                <input type="hidden" name="coupon_id"
                                                                    value="{{ $coupon->id }}" />
                                                                <button type="button"
                                                                    class="btn btn-secondary waves-effect"
                                                                    data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger waves-effect waves-light">{{ __('admin.delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $coupons->links() }}
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
