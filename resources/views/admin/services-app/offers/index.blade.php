@extends('layouts.master')
@section('title')
    {{ __('admin.all_offers') }}
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
            {{ __('admin.all_offers') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.offers') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.all_offers') }}</h4>
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
                                <th class="fw-bold">{{ __('admin.client_image') }}</th>
                                <th class="fw-bold">{{ __('admin.client_name') }}</th>
                                <th class="fw-bold">{{ __('admin.category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.sub_category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.city_name') }}</th>
                                <th class="fw-bold">{{ __('admin.price') }}</th>
                                <th class="fw-bold">{{ __('admin.date') }}</th>
                                <th class="fw-bold">{{ __('admin.time') }}</th>
                                <th class="fw-bold">{{ __('admin.status') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($offers) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($offers as $count => $offer)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $offer->order_id }}</td>
                                    <td class="align-middle">
                                        <a href="{{ $offer->clientImageLink }}" target="_blanck">
                                            <img src="{{ $offer->clientImageLink }}" alt="{{ __('admin.image') }}"
                                                style="width: 100px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $offer->client_name }}</td>
                                    <td class="align-middle">{{ $offer->category_name }}</td>
                                    <td class="align-middle">{{ $offer->sub_category_name }}</td>
                                    <td class="align-middle">{{ $offer->city_name }}</td>
                                    <td class="align-middle">{{ $offer->price ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">{{ $offer->date }}</td>
                                    <td class="align-middle">{{ $offer->time }}</td>
                                    <td class="align-middle">
                                        {{ $offer->price ? __('admin.pending') : __('admin.offer_not_sent') }}</td>
                                    <td class="align-middle">
                                        @if (!$offer->price)
                                            <button type="submit"
                                                class="modal-effect btn btn-outline-secondary bg-primary text-white btn-sm"
                                                title="{{ __('admin.send_offer') }}" data-effect="effect-newspaper"
                                                data-toggle="modal" href="#myModal{{ $offer->id }}">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        @endif
                                    </td>
                                    @if (!$offer->price)
                                        <div class="modal" id="myModal{{ $offer->id }}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('admin.send_offer') }}
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal"
                                                            type="button"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="offerForm" class="d-inline"
                                                            action="{{ route('services_app.admin.offers.send') }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="order_id"
                                                                value="{{ $offer->order_id }}" />
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="price{{ $offer->id }}">{{ __('admin.price') }}
                                                                            <span
                                                                                class="text-danger fw-bolder">*</span></label>
                                                                        <input type="number" class="form-control"
                                                                            id="price{{ $offer->id }}" name="price"
                                                                            placeholder="{{ __('admin.price') }}"
                                                                            required />
                                                                        @error('price')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect"
                                                            data-dismiss="modal">{{ __('admin.back') }}</button>
                                                        <button form="offerForm" type="submit"
                                                            class="btn btn-primary waves-effect waves-light">{{ __('admin.send') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $offers->links() }}
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
