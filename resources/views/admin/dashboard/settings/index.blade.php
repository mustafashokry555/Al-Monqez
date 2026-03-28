@extends('layouts.master')
@section('title')
    {{ __('admin.main_settings') }}
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
            {{ __('admin.setting_change') }}
        @endslot
        @slot('title')
            {{ __('admin.main_settings') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.setting_change') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('admin.settings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @include('layouts.session')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-switch">
                                        <input type="checkbox" name="site_status"
                                            class="form-control d-none @error('site_status') is-invalid @enderror"
                                            @if ($setting) @if ($setting->site_status == 1)checked="" @endif
                                            @endif/>
                                        <div class="main-toggle main-toggle-success @if ($setting) @if ($setting->site_status == 1)on @endif @endif"
                                            style="cursor: pointer">
                                            <span data-on-label="{{ __('admin.open') }}"
                                                data-off-label="{{ __('admin.close') }}"></span>
                                        </div>
                                    </label>
                                    @error('site_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @foreach ($languages as $language)
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label"
                                            for="name_{{ $language }}">{{ __('admin.site_name') }}
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <input type="input"
                                            class="form-control @error('name_{{ $language }}') is-invalid @enderror"
                                            id="name_{{ $language }}" name="name_{{ $language }}"
                                            placeholder="{{ __('admin.name') }}"
                                            @if ($setting) value="{{ $setting->{"name_$language"} }}" @endif
                                            required>
                                        @error('name_{{ $language }}')
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
                                        <label class="form-label"
                                            for="closed_message_{{ $language }}">{{ __('admin.closed_message') }}
                                            ({{ __("admin.$language") }}) <span
                                                class="text-danger fw-bolder">*</span></label>
                                        <input type="input"
                                            class="form-control @error('closed_message_{{ $language }}') is-invalid @enderror"
                                            id="closed_message_{{ $language }}"
                                            name="closed_message_{{ $language }}"
                                            placeholder="{{ __('admin.closed_message') }}"
                                            @if ($setting) value="{{ $setting->{"closed_message_$language"} }}" @endif
                                            required>
                                        @error('closed_message_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="{{ __('admin.phone') }}"
                                        @if ($setting) value="{{ $setting->phone }}" @endif required>
                                    @error('phone')
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
                                    <label class="form-label" for="email">{{ __('admin.email') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="{{ __('admin.email') }}"
                                        @if ($setting) value="{{ $setting->email }}" @endif required>
                                    @error('email')
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
                                    <label class="form-label" for="logo">{{ __('admin.logo') }}</label>
                                    @if ($setting)
                                        <div>
                                            <a href="{{ $setting->logoLink }}" target="_blanck">
                                                <img src="{{ $setting->logoLink }}" alt="{{ __('admin.logo') }}"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                        id="logo" name="logo">
                                    @error('logo')
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
                                    <label class="form-label" for="store_image">صورة المتجر </label>
                                    @if ($setting)
                                        <div>
                                            <a href="{{ $setting->storeImageLink }}" target="_blanck">
                                                <img src="{{ $setting->storeImageLink }}" alt="صورة المتجر"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('store_image') is-invalid @enderror"
                                        id="store_image" name="store_image">
                                    @error('store_image')
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
                                    <label class="form-label" for="services_image"> صورة الخدمات </label>
                                    @if ($setting)
                                        <div>
                                            <a href="{{ $setting->servicesImageLink }}" target="_blanck">
                                                <img src="{{ $setting->servicesImageLink }}" alt=" صورة الخدمات "
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('services_image') is-invalid @enderror"
                                        id="services_image" name="services_image">
                                    @error('services_image')
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
                                    <label class="form-label"
                                        for="android_app_link">{{ __('admin.android_app_link') }}</label>
                                    <input type="url"
                                        class="form-control @error('android_app_link') is-invalid @enderror"
                                        id="android_app_link" name="android_app_link"
                                        placeholder="{{ __('admin.android_app_link') }}"
                                        @if ($setting) value="{{ $setting->android_app_link }}" @endif>
                                    @error('android_app_link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="ios_app_link">{{ __('admin.ios_app_link') }}</label>
                                    <input type="url"
                                        class="form-control @error('ios_app_link') is-invalid @enderror"
                                        id="ios_app_link" name="ios_app_link"
                                        placeholder="{{ __('admin.ios_app_link') }}"
                                        @if ($setting) value="{{ $setting->ios_app_link }}" @endif>
                                    @error('ios_app_link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                        for="registration_link">{{ __('admin.registration_link') }}</label>
                                    <input type="url"
                                        class="form-control @error('registration_link') is-invalid @enderror"
                                        id="registration_link" name="registration_link"
                                        placeholder="{{ __('admin.registration_link') }}"
                                        @if ($setting) value="{{ $setting->registration_link }}" @endif>
                                    @error('registration_link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="app_version">{{ __('admin.app_version') }}</label>
                                    <input type="input" class="form-control @error('app_version') is-invalid @enderror"
                                        id="app_version" name="app_version" placeholder="{{ __('admin.app_version') }}"
                                        @if ($setting) value="{{ $setting->app_version }}" @endif>
                                    @error('app_version')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('admin.setting_change') }}</button>
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
