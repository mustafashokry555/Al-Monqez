@extends('layouts.master')
@section('title')
    {{ __('admin.new_store') }}
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
            href="{{ route('store_app.admin.stores.index') }}">
            <i class="fas fa-arrow-left"></i>
        </a>
    @endslot
    @slot('title')
        {{ __('admin.new_store') }}
    @endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">{{ __('admin.new_store') }}</h4>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="{{ route('store_app.admin.stores.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @include('layouts.session')
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
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
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="city_id">{{ __('admin.cities') }}
                                    <span class="text-danger fw-bolder">*</span>
                                </label>
                                <select name="city_id" class="form-control @error('city_id') is-invalid @enderror"
                                    id="city_id" required>
                                    <option value="" selected disabled>{{ __('admin.choose_city') }}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="category_id">{{ __('admin.categories') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <select class="form-control form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" onchange="getSubCategories(this)" required>
                                    <option value="" selected>{{ __('admin.choose_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
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
                                        for="address_{{ $language }}">{{ __('admin.address') }}
                                        ({{ __("admin.$language") }})
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="input"
                                        class="form-control @error('address_{{ $language }}') is-invalid @enderror"
                                        id="address_{{ $language }}" name="address_{{ $language }}"
                                        placeholder="{{ __('admin.address') }}"
                                        value="{{ old("address_$language") }}" required>
                                    @error('address_{{ $language }}')
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
                                <label class="form-label" for="latitude">{{ __('admin.latitude') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                    id="latitude" name="latitude" placeholder="{{ __('admin.latitude') }}"
                                    value="{{ old('latitude') }}" required>
                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="longitude">{{ __('admin.longitude') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                    id="longitude" name="longitude" placeholder="{{ __('admin.longitude') }}"
                                    value="{{ old('longitude') }}" required>
                                @error('longitude')
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
                                <label class="form-label" for="cover_image">{{ __('admin.cover_image') }}</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                    id="cover_image" name="cover_image">
                                @error('cover_image')
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
                                <label class="form-label" for="commercialRegistrationLink">صورة السجل التجاري </label>
                                <input type="file"
                                    class="form-control @error('commercialRegistrationLink') is-invalid @enderror"
                                    id="commercialRegistrationLink" name="commercialRegistrationLink">
                                @error('commercialRegistrationLink')
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
                                <label class="form-label" for="license">صورة الرخصة</label>
                                <input type="file" class="form-control @error('license') is-invalid @enderror"
                                    id="license" name="license">
                                @error('license')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" for="bank_name">اسم البنك </label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                id="bank_name" name="bank_name" placeholder="اسم البنك"
                                value="{{ old('bank_name') }}">
                            @error('bank_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" for="account_holder_name">اسم صاحب الحساب </label>
                            <input type="text"
                                class="form-control @error('account_holder_name') is-invalid @enderror"
                                id="account_holder_name" name="account_holder_name" placeholder="اسم صاحب الحساب"
                                value="{{ old('account_holder_name') }}">
                            @error('account_holder_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" for="IBAN">الايبان </label>
                            <input type="text" class="form-control @error('IBAN') is-invalid @enderror"
                                id="IBAN" name="IBAN" placeholder="الايبان" value="{{ old('IBAN') }}">
                            @error('IBAN')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <button class="btn btn-primary" type="submit">{{ __('admin.new_store') }}</button>
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
