@extends('layouts.master')
@section('title')
    {{ __('admin.new_delivery_driver') }}
@endsection
@section('content')
@section('css')
    <!--Internal  Datetimepicker-slider css -->
    <link href="{{ URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection
@component('components.breadcrumb')
    @slot('li_1')
        <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
            href="{{ route('store_app.admin.drivers.index') }}">
            <i class="fas fa-arrow-left"></i>
        </a>
    @endslot
    @slot('title')
        {{ __('admin.new_delivery_driver') }}
    @endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">{{ __('admin.new_delivery_driver') }}</h4>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="{{ route('store_app.admin.drivers.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @include('layouts.session')
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="image">{{ __('admin.image') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" required>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('admin.name') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="{{ __('admin.name') }}"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('admin.email') }}</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="{{ __('admin.email') }}"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" placeholder="{{ __('admin.phone') }}"
                                    value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('admin.password') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="{{ __('admin.password') }}" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="id_number">{{ __('admin.id_number') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                    id="id_number" name="id_number" placeholder="{{ __('admin.id_number') }}"
                                    value="{{ old('id_number') }}" required>
                                @error('id_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="vehicle_license_image">{{ __('admin.vehicle_license_image') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="file"
                                    class="form-control @error('vehicle_license_image') is-invalid @enderror"
                                    id="vehicle_license_image" name="vehicle_license_image" required>
                                @error('vehicle_license_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="driving_license_image">{{ __('admin.driving_license_image') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="file"
                                    class="form-control @error('driving_license_image') is-invalid @enderror"
                                    id="driving_license_image" name="driving_license_image" required>
                                @error('driving_license_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="bank_name">{{ __('admin.bank_name') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                    id="bank_name" name="bank_name" placeholder="{{ __('admin.bank_name') }}"
                                    value="{{ old('bank_name') }}" required>
                                @error('bank_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="iban_number">{{ __('admin.iban_number') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('iban_number') is-invalid @enderror"
                                    id="iban_number" name="iban_number" placeholder="{{ __('admin.iban') }}"
                                    value="{{ old('iban_number') }}" required>
                                @error('iban_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- multiple images --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="vehicle_equipment_images">{{ __('admin.vehicle_equipment_images') }}
                                    <span class="text-danger fw-bolder">*</span></label>
                                <input type="file"
                                    class="form-control @error('vehicle_equipment_images') is-invalid @enderror"
                                    id="vehicle_equipment_images" name="vehicle_equipment_images[]" required multiple>
                                @error('vehicle_equipment_images')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">{{ __('admin.new_delivery_driver') }}</button>
                </form>
            </div>
        </div>
        <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection
@section('js')
<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
@endsection
