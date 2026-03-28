@extends('layouts.master')
@section('title')
    {{ __('admin.order_settings') }}
@endsection
@section('css')
    <!--Internal  Datetimepicker-slider css -->
    <link href="{{ URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.order_setting_change') }}
        @endslot
        @slot('title')
            {{ __('admin.order_settings') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.order_setting_change') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.settings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @include('layouts.session')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="management_ratio">{{ __('admin.management_ratio') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number"
                                        class="form-control @error('management_ratio') is-invalid @enderror"
                                        id="management_ratio" name="management_ratio"
                                        placeholder="{{ __('admin.management_ratio') }}"
                                        value="{{ $storeSetting ? $storeSetting->management_ratio : '' }}" required>
                                    @error('management_ratio')
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
                                    <label class="form-label" for="vat">{{ __('admin.vat') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="number" class="form-control @error('vat') is-invalid @enderror"
                                        id="vat" name="vat" placeholder="{{ __('admin.vat') }}"
                                        value="{{ $storeSetting ? $storeSetting->vat : '' }}" required>
                                    @error('vat')
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
                                    <label class="form-label" for="delivery_charge">{{ __('admin.delivery_charge') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number"
                                        class="form-control @error('delivery_charge') is-invalid @enderror"
                                        id="delivery_charge" name="delivery_charge"
                                        placeholder="{{ __('admin.delivery_charge') }}"
                                        value="{{ $storeSetting ? $storeSetting->delivery_charge : '' }}" required>
                                    @error('delivery_charge')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @foreach ($languages as $language)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="store_terms_{{ $language }}" class="form-label">الشروط و الاحكام
                                            التاجر
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <textarea class="form-control @error('store_terms_{{ $language }}') is-invalid @enderror"
                                            id="store_terms_{{ $language }}" name="store_terms_{{ $language }}">{{ old("store_terms_$language") ? old("store_terms_$language") : $storeSetting->{"store_terms_$language"} }}</textarea>
                                        @error('store_terms_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach



                        @foreach ($languages as $language)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="driver_terms_{{ $language }}" class="form-label">الشروط و الاحكام
                                            المندوب
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <textarea class="form-control @error('driver_terms_{{ $language }}') is-invalid @enderror"
                                            id="driver_terms_{{ $language }}" name="driver_terms_{{ $language }}">{{ old("driver_terms_$language") ? old("driver_terms_$language") : $storeSetting->{"driver_terms_$language"} }}</textarea>
                                        @error('driver_terms_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <button class="btn btn-primary" type="submit">{{ __('admin.setting_change') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <!-- Internal ckeditor js -->
    <script src="{{ URL::asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/ckeditor.js') }}"></script>

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
