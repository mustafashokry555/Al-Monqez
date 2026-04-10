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
                            driver_id
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
                            @if (count($drivers) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($drivers as $count => $driver)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">
                                        <a href="{{ $driver->imageLink }}" target="_blanck">
                                            <img src="{{ $driver->imageLink }}"
                                                alt="{{ __('admin.image') }}" style="width: 100px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $driver->name }}</td>
                                    <td class="align-middle">{{ $driver->email ? $driver->email : __('admin.unknown') }}</td>
                                    <td class="align-middle">{{ $driver->phone }}</td>
                                    <td class="align-middle">{{ $driver->id_number ?? __('admin.not_found') }}</td>
                                    <td class="align-middle">
                                        @if ($driver->vehicleLicenseImageLink)
                                            <a href="{{ $driver->vehicleLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $driver->vehicleLicenseImageLink }}"
                                                    alt="{{ __('admin.vehicle_license_image') }}" style="width: 100px;" />
                                            </a>
                                        @else
                                            {{ __('admin.not_found') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if ($driver->drivingLicenseImageLink)
                                            <a href="{{ $driver->drivingLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $driver->drivingLicenseImageLink }}"
                                                    alt="{{ __('admin.driving_license_image') }}" style="width: 100px;" />
                                            </a>
                                        @else
                                            {{ __('admin.not_found') }}
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $driver->bank_name }}</td>
                                    <td class="align-middle">{{ $driver->iban_number }}</td>
                                    <td class="align-middle">{{ __("admin.accepted_$driver->accepted") }}</td>
                                    <td class="align-middle">{{ $driver->created_at }}</td>

                                    <td class="align-middle">
                                        <div class="d-flex">
                                            @if (isset($super_admin) || isset($driver_edit))
                                                <form class="d-inline ml-2" action="{{ route('store_app.admin.drivers.joining_requests.accept') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}" />
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary bg-success text-dark btn-sm"
                                                        title="{{ __('admin.approval') }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if (isset($super_admin) || isset($driver_delete))
                                                <form class="d-inline ml-2" action="{{ route('store_app.admin.drivers.destroy') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}" />
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                        title="{{ __('admin.delete') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $drivers->links() }}
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
