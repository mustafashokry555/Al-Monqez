@extends('layouts.master')
@section('title')
    {{ __('admin.edit_store') }}
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
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
                href="{{ route('store_app.admin.stores.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.edit_store') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_store') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.stores.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                store_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="store_id" value="{{ $store->id }}" />
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $store->imageLink }}" target="_blanck">
                                            <img src="{{ $store->imageLink }}" alt="{{ __('admin.image') }}"
                                                class="img-thumbnail wd-100p wd-sm-200" />
                                        </a>
                                    </div>
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
                                        value="{{ $store->name }}" required>
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
                                        value="{{ $store->email }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="{{ __('admin.phone') }}"
                                        value="{{ $store->phone }}" required>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="password">{{ __('admin.password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="{{ __('admin.password') }}">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="city_id">{{ __('admin.cities') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select name="city_id" class="form-control @error('city_id') is-invalid @enderror"
                                        id="city_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_city') }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @selected($store->city_id == $city->id)>
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
                                    <select name="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                        onchange="getSubCategories(this)" required>
                                        <option value="" selected disabled>{{ __('admin.choose_category') }}
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($store->category_id == $category->id)>
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
                            @foreach ($languages as $language)
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
                                            value="{{ $store->{"address_$language"} }}" required>
                                        @error('address_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="latitude">{{ __('admin.latitude') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude" placeholder="{{ __('admin.latitude') }}"
                                        value="{{ $store->latitude }}" required>
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
                                        value="{{ $store->longitude }}" required>
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
                                    <div>
                                        <a href="{{ $store->coverImageLink }}" target="_blanck">
                                            <img src="{{ $store->coverImageLink }}" alt="{{ __('admin.cover_image') }}"
                                                class="img-thumbnail wd-100p wd-sm-200" />
                                        </a>
                                    </div>
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


                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="commercial_registration">صورة السجل التجاري <span
                                        class="text-danger fw-bolder">*</span></label>
                                <div>
                                    <a href="{{ $store->commercialRegistrationLink }}" target="_blanck">
                                        <img src="{{ $store->commercialRegistrationLink }}" alt="صورة السجل التجاري"
                                            class="img-thumbnail wd-100p wd-sm-200" />
                                    </a>
                                </div>
                                <input type="file"
                                    class="form-control @error('commercial_registration') is-invalid @enderror"
                                    id="commercial_registration" name="commercial_registration">
                                @error('commercial_registration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="license">صورة الرخصة <span
                                        class="text-danger fw-bolder">*</span></label>
                                <div>
                                    <a href="{{ $store->licenseLink }}" target="_blanck">
                                        <img src="{{ $store->licenseLink }}" alt="صورة الرخصة"
                                            class="img-thumbnail wd-100p wd-sm-200" />
                                    </a>
                                </div>
                                <input type="file" class="form-control @error('license') is-invalid @enderror"
                                    id="license" name="license">
                                @error('license')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="bank_name">اسم البنك <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                    id="bank_name" name="bank_name" placeholder="اسم البنك"
                                    value="{{ $store->bank_name }}" required>
                                @error('bank_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="account_holder_name">اسم صاحب الحساب <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text"
                                    class="form-control @error('account_holder_name') is-invalid @enderror"
                                    id="account_holder_name" name="account_holder_name" placeholder="اسم صاحب الحساب"
                                    value="{{ $store->account_holder_name }}" required>
                                @error('account_holder_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="IBAN">الايبان <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control @error('IBAN') is-invalid @enderror"
                                    id="IBAN" name="IBAN" placeholder="الايبان" value="{{ $store->IBAN }}"
                                    required>
                                @error('IBAN')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">{{ __('admin.edit') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
