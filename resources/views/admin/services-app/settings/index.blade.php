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
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style type="text/css">
        /* العنصر المختار */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd !important;
            /* لون الخلفية */
            color: #ffffff !important;
            /* لون النص */
            border: none !important;
            border-radius: 6px;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ffffff !important;
            margin-right: 6px;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffdddd !important;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: #f8f9fa !important;
            border: 1px solid #0d6efd !important;
            border-radius: 8px;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            color: #333 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white !important;
            border: none;
        }
    </style>
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
                    <form class="needs-validation" action="{{ route('services_app.admin.settings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @include('layouts.session')


                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="categories[]">الاقسام التي ضمن الضمان
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <select class="form-control select2 @error('categories') is-invalid @enderror"
                                        id="categories[]" name="categories[]" placeholder="اختر القسم" required multiple>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $selected) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('categories')
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
                                    <label class="form-label" for="warranty_days">{{ __('admin.warranty_days') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number" class="form-control @error('warranty_days') is-invalid @enderror"
                                        id="warranty_days" name="warranty_days"
                                        placeholder="{{ __('admin.warranty_days') }}"
                                        value="{{ $orderSetting ? $orderSetting->warranty_days : '' }}" required>
                                    @error('warranty_days')
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
                                    <label class="form-label" for="management_ratio">{{ __('admin.management_ratio') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number"
                                        class="form-control @error('management_ratio') is-invalid @enderror"
                                        id="management_ratio" name="management_ratio"
                                        placeholder="{{ __('admin.management_ratio') }}"
                                        value="{{ $orderSetting ? $orderSetting->management_ratio : '' }}" required>
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
                                    <label class="form-label" for="deposit_ratio">{{ __('admin.deposit_ratio') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="number" class="form-control @error('deposit_ratio') is-invalid @enderror"
                                        id="deposit_ratio" name="deposit_ratio"
                                        placeholder="{{ __('admin.deposit_ratio') }}"
                                        value="{{ $orderSetting ? $orderSetting->deposit_ratio : '' }}" required>
                                    @error('deposit_ratio')
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
                                        value="{{ $orderSetting ? $orderSetting->vat : '' }}" required>
                                    @error('vat')
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
                                    <label class="form-label" for="start_time">{{ __('admin.start_time') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="time" datetime="hh:mm:ss"
                                        class="form-control @error('start_time') is-invalid @enderror" id="start_time"
                                        name="start_time"
                                        value="{{ $orderSetting ? date('H:i', strtotime($orderSetting->start_time)) : '' }}"
                                        required>
                                    @error('start_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="end_time">{{ __('admin.end_time') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="time" datetime="hh:mm:ss"
                                        class="form-control @error('end_time') is-invalid @enderror" id="end_time"
                                        name="end_time"
                                        value="{{ $orderSetting ? date('H:i', strtotime($orderSetting->end_time)) : '' }}"
                                        required>
                                    @error('end_time')
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

    <script type="text/javascript">
        $('.select2').select2({
            placeholder: '{{ __('dashboard.Choose') }}',
            searchInputPlaceholder: '{{ __('dashboard.Search') }}',
            width: '100%'
        });
    </script>
@endsection
