@extends('layouts.master')
@section('title')
    {{ __('admin.new_employee') }}
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
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('admin.admins.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.new_employee') }}
        @endslot
    @endcomponent


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.add_employee') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                permissions
                            @endslot
                        @endcomponent
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="name">{{ __('admin.name') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('admin.name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
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
                                    <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="{{ __('admin.phone') }}"
                                        value="{{ old('phone') }}" required>
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
                                    <label class="form-label" for="password">{{ __('admin.password') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="{{ __('admin.password') }}"
                                        value="{{ old('password') }}" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive col-12">
                                <label class="form-label" for="permissions">{{ __('admin.controls') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <table class="table table-bordered table-striped table-nowrap mb-0">
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <th class="text-nowrap col-1 fw-bold" scope="row">
                                                    <label class="form-switch d-flex m-0">
                                                        <input type="checkbox" name="permissions[]"
                                                            class="form-control d-none" value="{{ $permission->id }}" />
                                                        <div class="main-toggle main-toggle-success"
                                                            style="cursor: pointer">
                                                            <span data-on-label="{{ __('admin.yes') }}"
                                                                data-off-label="{{ __('admin.no') }}"></span>
                                                        </div>
                                                    </label>
                                                </th>
                                                <td class="align-middle">{{ __("admin.$permission->name") }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-4" type="submit">{{ __('admin.add_employee') }}</button>
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
