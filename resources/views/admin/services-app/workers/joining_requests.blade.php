@extends('layouts.master')
@section('title')
    {{ __('admin.all_joining_requests') }}
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
            {{ __('admin.all_joining_requests') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.joining_requests') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_joining_requests') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            worker_id
                        @endslot
                    @endcomponent
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.image') }}</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.email') }}</th>
                                <th class="fw-bold">{{ __('admin.phone') }}</th>
                                <th class="fw-bold">{{ __('admin.city_name') }}</th>
                                <th class="fw-bold">{{ __('admin.category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.sub_category_name') }}</th>
                                <th class="fw-bold">{{ __('admin.id_number') }}</th>
                                <th class="fw-bold">{{ __('admin.vehicle_license_image') }}</th>
                                <th class="fw-bold">{{ __('admin.driving_license_image') }}</th>
                                <th class="fw-bold">{{ __('admin.bank_name') }}</th>
                                <th class="fw-bold">{{ __('admin.iban_number') }}</th>
                                <th class="fw-bold">{{ __('admin.account_status') }}</th>
                                <th class="fw-bold">{{ __('admin.joining_date') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
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
                                    <td class="align-middle">
                                        <a href="{{ $worker->imageLink }}" target="_blanck">
                                            <img src="{{ $worker->imageLink }}"
                                                alt="{{ __('admin.image') }}" style="width: 100px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $worker->name }}</td>
                                    <td class="align-middle">{{ $worker->email ? $worker->email : __('admin.unknown') }}
                                    </td>
                                    <td class="align-middle">{{ $worker->phone }}</td>
                                    <td class="align-middle">{{ $worker->city_name }}</td>
                                    <td class="align-middle">{{ $worker->category_name }}</td>
                                    <td class="align-middle">{{ implode(', ', $worker->subCategories->pluck('name')->toArray()) }}</td>
                                    <td class="align-middle">{{ $worker->id_number ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">
                                        @if ($worker->vehicle_license_image_link)
                                            <a href="{{ $worker->vehicle_license_image_link }}" target="_blanck">
                                                <img src="{{ $worker->vehicle_license_image_link }}"
                                                    alt="{{ __('admin.image') }}" style="width: 100px;" />
                                            </a>
                                        @else
                                            {{ __('admin.not_found') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if ($worker->driving_license_image_link)
                                            <a href="{{ $worker->driving_license_image_link }}" target="_blanck">
                                                <img src="{{ $worker->driving_license_image_link }}"
                                                    alt="{{ __('admin.image') }}" style="width: 100px;" />
                                            </a>
                                        @else
                                            {{ __('admin.not_found') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $worker->bank_name }}</td>
                                    <td class="align-middle">{{ $worker->iban_number }}</td>
                                    <td class="align-middle">{{ __("admin.accepted_$worker->accepted") }}</td>
                                    <td class="align-middle">{{ $worker->created_at }}</td>

                                    <td class="align-middle">
                                        <div class="d-flex">
                                            {{-- <button class="modal-effect btn btn-outline-secondary bg-warning text-dark btn-sm ml-2"
                                                title="{{ __('admin.files') }}" data-effect="effect-newspaper"
                                                data-toggle="modal" href="#filesModel{{ $worker->id }}">
                                                {{ __('admin.files') }}
                                            </button> --}}
                                            @if (isset($super_admin) || isset($worker_edit))
                                                <form class="d-inline ml-2" action="{{ route('services_app.admin.workers.joining_requests.accept') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="worker_id" value="{{ $worker->id }}" />
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary  bg-success text-dark btn-sm"
                                                        title="{{ __('admin.approval') }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if (isset($super_admin) || isset($worker_delete))
                                                <form class="d-inline ml-2" action="{{ route('services_app.admin.workers.destroy') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="worker_id" value="{{ $worker->id }}" />
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary  bg-danger text-dark btn-sm"
                                                        title="{{ __('admin.delete') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- <div class="modal" id="filesModel{{ $worker->id }}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('admin.files') }}
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal"
                                                            type="button"><span
                                                                aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if (count($worker->files) > 0)
                                                            <div class="row demo-gallery">
                                                                <ul id="lightgallery"
                                                                    class="list-unstyled row row-sm pr-0">
                                                                    @foreach ($worker->files as $file)
                                                                        <li class="col-sm-6 col-lg-4">
                                                                            <a href="{{ $file->file_link }}" target="_blank">
                                                                                <img class="img-responsive"
                                                                                    src="{{ URL::asset('uploads/defaults/file.png') }}"
                                                                                    alt="Thumb-1">
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <p class="text-center">{{ __('admin.no_files') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $workers->links() }}
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
