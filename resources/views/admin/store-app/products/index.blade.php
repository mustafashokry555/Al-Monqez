@extends('layouts.master')
@section('title')
    {{ __('admin.products') }}
@endsection
@section('css')
    <!---Internal Owl Carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <!---Internal  multislider css-->
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
            {{ __('admin.all_products') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.products') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_products') }}</h4>
                    @if (isset($super_admin) || isset($product_create))
                        <a href="{{ route('store_app.admin.products.create') }}" class="btn btn-primary button-icon"><i
                                class="fe fe-plus ml-2 font-weight-bolder"></i>{{ __('admin.add_product') }}</a>
                    @endif
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            product_id
                        @endslot
                    @endcomponent
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.image') }}</th>
                                @if (auth()->user()->role_id != 6)
                                    <th class="fw-bold">{{ __('admin.store') }}</th>
                                @endif
                                <th class="fw-bold">{{ __('admin.classification') }}</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.price') }}</th>
                                <th class="fw-bold">{{ __('admin.sale_price') }}</th>
                                <th class="fw-bold">{{ __('admin.quantity') }}</th>
                                <th class="fw-bold">{{ __('admin.display_status') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($products) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($products as $count => $product)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">
                                        <a href="{{ $product->imageLink }}" target="_blanck">
                                            <img src="{{ $product->imageLink }}" alt="{{ __('admin.image') }}"
                                                style="width: 100px;" />
                                        </a>
                                    </td>
                                    @if (auth()->user()->role_id != 6)
                                        <td class="align-middle">{{ $product->store_name }}</td>
                                    @endif
                                    <td class="align-middle">{{ $product->classification_name }}</td>
                                    <td class="align-middle">{{ $product->name }}</td>
                                    <td class="align-middle">{{ $product->price }}</td>
                                    <td class="align-middle">{{ $product->sale_price }}</td>
                                    <td class="align-middle">{{ $product->quantity }}</td>
                                    <td class="align-middle">
                                        {{ $product->displayed == 0 ? __('admin.hidden') : __('admin.displayed') }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            @if (isset($super_admin) || isset($product_edit))
                                                <form class="d-inline ml-2"
                                                    action="{{ route('store_app.admin.products.display') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary  bg-primary text-dark btn-sm"
                                                        @if ($product->displayed == 1) title="{{ __('admin.hide') }}" @else title="{{ __('admin.show') }}" @endif>
                                                        <i
                                                            class="@if ($product->displayed == 1) fas fa-eye-slash @else fas fa-eye @endif"></i>
                                                    </button>
                                                </form>
                                                <a class="btn btn-outline-secondary bg-warning text-dark btn-sm ml-2"
                                                    title="{{ __('admin.edit') }}"
                                                    href="{{ route('store_app.admin.products.edit', [$product->id]) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                            @if (isset($super_admin) || isset($product_delete))
                                                <button type="submit"
                                                    class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                    title="{{ __('admin.delete') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#myModal{{ $product->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if (isset($super_admin) || isset($product_delete))
                                            <div class="modal" id="myModal{{ $product->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.delete_product') }}</h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.delete_product_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('store_app.admin.products.destroy') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('Delete')
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}" />
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
                            {{ $products->links() }}
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
