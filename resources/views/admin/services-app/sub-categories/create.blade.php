@extends('layouts.master')
@section('title')
    {{ __('admin.new_sub_category') }}
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
                href="{{ route('services_app.admin.sub.categories.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.new_sub_category') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.add_sub_category') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.sub.categories.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @include('layouts.session')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-switch">
                                        <input type="checkbox" name="show"
                                            class="form-control d-none @error('show') is-invalid @enderror" />
                                        <div class="main-toggle main-toggle-success" style="cursor: pointer">
                                            <span data-on-label="{{ __('admin.show') }}"
                                                data-off-label="{{ __('admin.hide') }}"></span>
                                        </div>
                                    </label>
                                    @error('show')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
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
                                    <label class="form-label" for="sub_category_type">{{ __('admin.sub_category_type') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <select
                                        class="form-control form-select @error('sub_category_type') is-invalid @enderror"
                                        id="sub_category_type" name="sub_category_type" required>
                                        <option value="" selected disabled>{{ __('admin.select_type') }}
                                        </option>
                                        @for ($i = 0; $i < 2; $i++)
                                            <option value="{{ $i }}"
                                                @if (old('sub_category_type') == "$i") selected @endif>
                                                {{ __("admin.sub_category_type_$i") }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('sub_category_type')
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
                                    <label class="form-label" for="location_type">{{ __('admin.location_type') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select class="form-control form-select @error('location_type') is-invalid @enderror"
                                        id="location_type" name="location_type" required>
                                        <option value="" selected disabled>{{ __('admin.select_type') }}
                                        </option>
                                        @for ($i = 0; $i < 2; $i++)
                                            <option value="{{ $i }}"
                                                @if (old('location_type') == "$i") selected @endif>
                                                {{ __("admin.location_type_$i") }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('location_type')
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
                                    <label class="form-label" for="category_id">{{ __('admin.main_categories') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select class="form-control form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_main_category') }}
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if (old('category_id') == $category->id) selected @endif>{{ $category->name }}
                                            </option>
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
                                        <label class="form-label" for="name_{{ $language }}">{{ __('admin.name') }}
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <input type="input"
                                            class="form-control @error('name_{{ $language }}') is-invalid @enderror"
                                            id="name_{{ $language }}" name="name_{{ $language }}"
                                            placeholder="{{ __('admin.name') }}" value="{{ old("name_$language") }}"
                                            required>
                                        @error('name_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary" type="submit">{{ __('admin.add_sub_category') }}</button>
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
