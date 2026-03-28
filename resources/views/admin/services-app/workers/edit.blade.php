@extends('layouts.master')
@section('title')
    {{ __('admin.edit_worker') }}
@endsection
@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal  Datetimepicker-slider css -->
    <link href="{{ URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
    <style>
        .select2-selection {
            padding: 0.125rem 0.75rem;
            border: 1px solid #e1e5ef !important;
        }

        .select2-dropdown {
            border: 1px solid #e1e5ef !important;
            border-top: none !important;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
                href="{{ route('services_app.admin.workers.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.edit_worker') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_worker') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.workers.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                worker_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="worker_id" value="{{ $worker->id }}" />
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $worker->imageLink }}" target="_blanck">
                                            <img src="{{ $worker->imageLink }}" alt="{{ __('admin.image') }}"
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
                        <div class="row @if (auth()->user()->role_id == '7') d-none @endif">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="company_id">{{ __('admin.companies') }}</label>
                                    <select name="company_id" class="form-control @error('company_id') is-invalid @enderror"
                                        id="company_id">
                                        <option value="" selected>{{ __('admin.without_company') }}</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @selected($worker->company_id == $company->id || auth()->user()->role_id == '7')>
                                                {{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
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
                                        value="{{ $worker->name }}" required>
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
                                        value="{{ $worker->email }}">
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
                                        value="{{ $worker->phone }}" required>
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
                                            <option value="{{ $city->id }}" @selected($worker->city_id == $city->id)>
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
                                            <option value="{{ $category->id }}" @selected($worker->category_id == $category->id)>
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
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="sub_category_ids">{{ __('admin.sub_categories') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <select name="sub_category_ids[]"
                                        class="form-control select2 @error('sub_category_ids') is-invalid @enderror  @error('sub_category_ids.*') is-invalid @enderror"
                                        multiple="multiple" id="sub_category_ids" required>
                                        <option value="" selected disabled>{{ __('admin.choose_sub_category') }}
                                        </option>
                                    </select>
                                    @error('sub_category_ids')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('sub_category_ids.*')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="service_ids">{{ __('admin.services') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <select name="service_ids[]"
                                        class="form-control select2 @error('service_ids') is-invalid @enderror  @error('service_ids.*') is-invalid @enderror"
                                        multiple="multiple" id="service_ids" required>
                                        <option value="" selected disabled>{{ __('admin.choose_service') }}
                                        </option>
                                    </select>
                                    @error('service_ids')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('service_ids.*')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="id_number">{{ __('admin.id_number') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                        id="id_number" name="id_number" placeholder="{{ __('admin.id_number') }}"
                                        value="{{ $worker->id_number }}">
                                    @error('id_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row" id="vehicle_registration" style="display: none;">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                        for="vehicle_license_image">{{ __('admin.vehicle_license_image') }}
                                        @if (!$worker->vehicleLicenseImageLink)
                                            <span class="text-danger fw-bolder">*</span>
                                        @endif
                                    </label>
                                    @if ($worker->vehicleLicenseImageLink)
                                        <div>
                                            <a href="{{ $worker->vehicleLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $worker->vehicleLicenseImageLink }}"
                                                    alt="{{ __('admin.vehicle_license_image') }}"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('vehicle_license_image') is-invalid @enderror"
                                        id="vehicle_license_image" name="vehicle_license_image" @required(!$worker->vehicleLicenseImageLink)>
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
                                        @if (!$worker->drivingLicenseImageLink)
                                            <span class="text-danger fw-bolder">*</span>
                                        @endif
                                    </label>
                                    @if ($worker->drivingLicenseImageLink)
                                        <div>
                                            <a href="{{ $worker->drivingLicenseImageLink }}" target="_blanck">
                                                <img src="{{ $worker->drivingLicenseImageLink }}"
                                                    alt="{{ __('admin.driving_license_image') }}"
                                                    class="img-thumbnail wd-100p wd-sm-200" />
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file"
                                        class="form-control @error('driving_license_image') is-invalid @enderror"
                                        id="driving_license_image" name="driving_license_image" @required(!$worker->drivingLicenseImageLink)>
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
                                        value="{{ old('bank_name') ? old('bank_name') : $worker->bank_name }}" required>
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
                                        value="{{ old('iban_number') ? old('iban_number') : $worker->iban_number }}"
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
                                        @foreach ($worker->files as $file)
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('admin.description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') ? old('description') : $worker->description }}</textarea>
                                    @error('description')
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
    <script>
        $(document).ready(function() {
            $('.delete-image-btn').on('click', function() {
                var imageId = $(this).data('image-id');
                var url = "{{ route('services_app.admin.workers.image_destroy') }}";
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
    <script>
        var categorySelector = document.getElementById('category_id');
        var subCategorySelector = document.getElementById('sub_category_ids');
        var serviceSelector = document.getElementById('service_ids');
        var vehicleRegistrationInputs = document.getElementById('vehicle_registration');

        function getSubCategories(categorySelector) {
            subCategorySelector.innerHTML = ``;
            serviceSelector.innerHTML = ``;

            $.ajax({
                url: '{{ route('services_app.admin.sub.categories.all') }}?category_id=' + categorySelector.value,
                type: 'GET',
                dataType: 'json',
                success: function(subCategories) {

                    // Old selected values (from DB)
                    let selectedSubCategories = {!! json_encode($worker->subCategories->pluck('sub_category_id')->toArray()) !!};
                    let selectedServices = {!! json_encode($worker->services->pluck('service_id')->toArray()) !!};

                    subCategories.forEach(sub => {

                        // ---- SUBCATEGORY OPTION ----
                        let isSubSelected = selectedSubCategories.includes(sub.id) ? "selected" : "";
                        subCategorySelector.innerHTML += `
                        <option value="${sub.id}" aria-valuenow="${sub.sub_category_type}" ${isSubSelected}>
                            ${sub.name}
                        </option>`;

                        // ---- SERVICES UNDER SUBCATEGORY ----
                        if (sub.services && sub.services.length > 0) {
                            let $optgroup = $('<optgroup>').attr('label', sub.name);

                            let parentSelected = selectedSubCategories.includes(sub.id);

                            sub.services.forEach(service => {
                                let shouldSelect = parentSelected && selectedServices.includes(
                                    service.id);

                                let $option = $('<option>')
                                    .val(service.id)
                                    .text(service.name)
                                    .attr('data-subcategory', sub.id)
                                    .prop('disabled', !parentSelected)
                                    .prop('selected', shouldSelect);

                                $optgroup.append($option);
                            });

                            $(serviceSelector).append($optgroup);
                        }
                    });

                    // Initialize Select2
                    initSelect2();

                    enforceServiceEnableState();
                    manageVehicleRegistrationInputs(subCategorySelector);

                    $('#sub_category_ids').on('change', function() {
                        enforceServiceEnableState();
                        manageVehicleRegistrationInputs(this);
                    });
                }
            });
        }

        function initSelect2() {
            $('#sub_category_ids').select2({
                placeholder: '{{ __('admin.choose_sub_category') }}',
                width: '100%',
                dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
            });

            $('#service_ids').select2({
                placeholder: '{{ __('admin.choose_service') }}',
                width: '100%',
                dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
            });
        }

        function enforceServiceEnableState() {
            let selectedSubIds = $('#sub_category_ids').val() || [];

            $('#service_ids option').each(function() {
                let parentId = $(this).data('subcategory').toString();

                if (selectedSubIds.includes(parentId)) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', true).prop('selected', false);
                }
            });

            $('#service_ids').select2('destroy');
            $('#service_ids').select2({
                placeholder: '{{ __('admin.choose_service') }}',
                width: '100%',
                dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
            });
        }

        function manageVehicleRegistrationInputs(selector) {
            let selectedOptions = [...selector.selectedOptions || []];

            let mustShow = selectedOptions.some(opt => opt.getAttribute("aria-valuenow") == "0");

            vehicleRegistrationInputs.style.display = mustShow ? "block" : "none";
        }

        window.addEventListener("load", () => getSubCategories(categorySelector));
    </script>
@endsection
