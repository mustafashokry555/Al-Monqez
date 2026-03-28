@extends('layouts.master')
@section('title')
    {{ __('admin.edit_delivery_driver') }}
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
                href="{{ route('store_app.admin.drivers.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.edit_delivery_driver') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_delivery_driver') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.drivers.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                driver_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="driver_id" value="{{ $driver->id }}" />
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $driver->imageLink }}" target="_blanck">
                                            <img src="{{ $driver->imageLink }}" alt="{{ __('admin.image') }}"
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
                                        value="{{ $driver->name }}" required>
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
                                        value="{{ $driver->email }}">
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
                                        value="{{ $driver->phone }}" required>
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
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="id_number">{{ __('admin.id_number') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                        id="id_number" name="id_number" placeholder="{{ __('admin.id_number') }}"
                                        value="{{ $driver->id_number }}" required>
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
                                        for="vehicle_license_image">{{ __('admin.vehicle_license_image') }}
                                        @if (!$driver->vehicleLicenseImageLink)
                                            <span class="text-danger fw-bolder">*</span>
                                        @endif
                                    </label>
                                    @if ($driver->vehicleLicenseImageLink)
                                        <div>
                                            <a href="{{ $driver->vehicleLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $driver->vehicleLicenseImageLink }}"
                                                    alt="{{ __('admin.vehicle_license_image') }}"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('vehicle_license_image') is-invalid @enderror"
                                        id="vehicle_license_image" name="vehicle_license_image" @required(!$driver->vehicleLicenseImageLink)>
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
                                        for="driving_license_image">{{ __('admin.driving_license_image') }}
                                        @if (!$driver->drivingLicenseImageLink)
                                            <span class="text-danger fw-bolder">*</span>
                                        @endif
                                    </label>
                                    @if ($driver->drivingLicenseImageLink)
                                        <div>
                                            <a href="{{ $driver->drivingLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $driver->drivingLicenseImageLink }}"
                                                    alt="{{ __('admin.driving_license_image') }}"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('driving_license_image') is-invalid @enderror"
                                        id="driving_license_image" name="driving_license_image" @required(!$driver->drivingLicenseImageLink)>
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
                                        value="{{ old('bank_name') ? old('bank_name') : $driver->bank_name }}" required>
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
                                        id="iban_number" name="iban_number" placeholder="{{ __('admin.iban_number') }}"
                                        value="{{ old('iban_number') ? old('iban_number') : $driver->iban_number }}"
                                        required>
                                    @error('iban_number')
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
                                        for="vehicle_equipment_images">{{ __('admin.vehicle_equipment_images') }}</label>
                                    <div class="d-flex">
                                        @foreach ($driver->files as $file)
                                            <div class="me-2 mb-2 position-relative">
                                                <a href="{{ $file->fileLink }}" target="_blanck">
                                                    <img src="{{ $file->fileLink }}" alt="{{ __('admin.image') }}"
                                                        class="img-thumbnail wd-100p wd-sm-100" />
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute delete-image-btn"
                                                    style="top: 0; right: 0;" data-image-id="{{ $file->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="file"
                                        class="form-control @error('vehicle_equipment_images') is-invalid @enderror"
                                        id="vehicle_equipment_images" name="vehicle_equipment_images[]" multiple>
                                    @error('vehicle_equipment_images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
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

@section('js')
    <script>
        $(document).ready(function() {
            $('.delete-image-btn').on('click', function() {
                var imageId = $(this).data('image-id');
                var url = "{{ route('store_app.admin.drivers.image_destroy') }}";
                url += `?image_id=${imageId}`;
                //csrf token
                url += `&_token={{ csrf_token() }}`;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        // remove the image container and show success message from API
                        $(`[data-image-id="${imageId}"]`).parent().remove();
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        // try to show API message if available, fallback to generic message
                        let msg = '{{ __('messages.something_went_wrong') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        toastr.error(msg);
                    }
                });
            });
        });
    </script>
@endsection
