@extends('layouts.master')
@section('title')
    {{ __('admin.patches') }}
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
            {{ __('admin.all_patches') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.patches') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_patches') }}</h4>
                    @if (isset($super_admin) || isset($patch_create))
                        <a href="{{ route('store_app.admin.patches.create') }}" class="btn btn-primary button-icon"><i class="fe fe-plus ml-2 font-weight-bolder"></i>{{ __('admin.add_patch') }}</a>
                    @endif
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            classification_id
                        @endslot
                    @endcomponent

                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.display_status') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($patches) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($patches as $count => $patch)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $patch->name }}</td>
                                    <td class="align-middle">
                                        {{ $patch->displayed == 0 ? __('admin.hidden') : __('admin.displayed') }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            @if (isset($super_admin) || isset($patch_edit))
                                                <form class="d-inline ml-2" action="{{ route('store_app.admin.patches.display') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="classification_id" value="{{ $patch->id }}" />
                                                    <button type="submit" class="btn btn-outline-secondary  bg-primary text-dark btn-sm" @if ($patch->displayed == 1) title="{{ __('admin.hide') }}" @else title="{{ __('admin.show') }}" @endif>
                                                        <i class="@if ($patch->displayed == 1) fas fa-eye-slash @else fas fa-eye @endif"></i>
                                                    </button>
                                                </form>
                                                <a class="btn btn-outline-secondary bg-warning text-dark btn-sm ml-2" title="{{ __('admin.edit') }}" href="{{ route('store_app.admin.patches.edit', [$patch->id]) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                            @if (isset($super_admin) || isset($patch_delete))
                                                <button type="button" class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm ml-2" title="{{ __('admin.delete') }}" data-effect="effect-newspaper" data-toggle="modal" href="#myModal{{ $patch->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if (isset($super_admin) || isset($patch_delete))
                                            <div class="modal" id="myModal{{ $patch->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.delete_patch') }}</h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.delete_patch_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline" action="{{ route('store_app.admin.patches.destroy') }}" method="POST">
                                                                @csrf
                                                                @method('Delete')
                                                                <input type="hidden" name="classification_id" value="{{ $patch->id }}" />
                                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit" class="btn btn-danger waves-effect waves-light">{{ __('admin.delete') }}</button>
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
                            {{ $patches->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
