@extends('layouts.master')
@section('title')
    {{ __('admin.new_worker') }}
@endsection
@section('content')
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
@component('components.breadcrumb')
    @slot('li_1')
        <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
            href="{{ route('services_app.admin.workers.index') }}">
            <i class="fas fa-arrow-left"></i>
        </a>
    @endslot
    @slot('title')
        {{ __('admin.new_worker') }}
    @endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title m-0">{{ __('admin.new_worker') }}</h4>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="{{ route('services_app.admin.workers.store') }}" method="POST"
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
                    <div class="row @if (auth()->user()->role_id == '7') d-none @endif">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="company_id">{{ __('admin.companies') }}
                                </label>
                                <select name="company_id" class="form-control @error('company_id') is-invalid @enderror"
                                    id="company_id">
                                    <option value="" selected>{{ __('admin.without_company') }}</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" @selected(old('company_id') == $company->id || auth()->user()->role_id == '7')>
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
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="sub_category_ids">{{ __('admin.sub_categories') }}
                                    <span class="text-danger fw-bolder">*</span></label>
                                <select
                                    class="form-control select2 @error('sub_category_ids') is-invalid @enderror @error('sub_category_ids.*') is-invalid @enderror"
                                    multiple="multiple" id="sub_category_ids" name="sub_category_ids[]" required>
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
                                <select
                                    class="form-control select2 @error('service_ids') is-invalid @enderror @error('service_ids.*') is-invalid @enderror"
                                    multiple="multiple" id="service_ids" name="service_ids[]" required>
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
                                    value="{{ old('id_number') }}">
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
                                    for="vehicle_license_image">{{ __('admin.vehicle_license_image') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="file"
                                    class="form-control @error('vehicle_license_image') is-invalid @enderror"
                                    id="vehicle_license_image" name="vehicle_license_image">
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
                                    id="driving_license_image" name="driving_license_image">
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
                                    id="iban_number" name="iban_number" placeholder="{{ __('admin.iban_number') }}"
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('admin.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">{{ __('admin.new_worker') }}</button>
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

                let oldSubCategories =
                    {!! old('sub_category_ids') ? json_encode(old('sub_category_ids')) : json_encode([]) !!};

                let oldServices =
                    {!! old('service_ids') ? json_encode(old('service_ids')) : json_encode([]) !!};

                // Build subcategory options and service optgroups/options
                subCategories.forEach(sub => {

                    // ---- SUBCATEGORY OPTION ----
                    let isSubSelected = oldSubCategories.includes(sub.id.toString()) ? "selected" :
                        "";
                    subCategorySelector.innerHTML += `
                    <option value="${sub.id}" aria-valuenow="${sub.sub_category_type}" ${isSubSelected}>
                        ${sub.name}
                    </option>`;

                    // ---- SERVICES UNDER THIS SUBCATEGORY ----
                    if (sub.services && sub.services.length > 0) {
                        // create optgroup for services (label = subcategory name)
                        let $optgroup = $('<optgroup>').attr('label', sub.name);

                        // determine whether this subcategory is currently selected (so services should be enabled)
                        let parentSelected = oldSubCategories.includes(sub.id.toString());

                        sub.services.forEach(service => {
                            // A service is only selected if it was in oldServices AND its parent subcategory is selected.
                            let shouldBeSelected = parentSelected && oldServices.includes(
                                service.id.toString());
                            // disabled unless parentSelected
                            let $option = $('<option>')
                                .val(service.id)
                                .text(service.name)
                                .attr('data-subcategory', sub.id)
                                .prop('disabled', !parentSelected)
                                .prop('selected', shouldBeSelected);

                            $optgroup.append($option);
                        });

                        $(serviceSelector).append($optgroup);
                    }
                });

                // Initialize Select2 separately for each select (so we can update service select without destroying subcategory select)
                $('#sub_category_ids').select2({
                    placeholder: '{{ __('admin.choose_sub_category') }}',
                    width: '100%',
                    dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}",
                    language: {
                        noResults: function() {
                            return '{{ __('admin.no_results') }}';
                        },
                        searching: function() {
                            return '{{ __('admin.searching') }}';
                        }
                    },
                    minimumResultsForSearch: 0
                });

                $('#service_ids').select2({
                    placeholder: '{{ __('admin.choose_service') }}',
                    width: '100%',
                    dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}",
                    language: {
                        noResults: function() {
                            return '{{ __('admin.no_results') }}';
                        },
                        searching: function() {
                            return '{{ __('admin.searching') }}';
                        }
                    },
                    minimumResultsForSearch: 0
                });

                // After building, enforce enable/disable logic (in case of mismatch between old services & old subcategories)
                enforceServiceEnableState();
                manageVehicleRegistrationInputs(subCategorySelector);

                // Listen for changes on subcategory select so we can enable/disable services live
                $('#sub_category_ids').on('change', function() {
                    enforceServiceEnableState();
                    manageVehicleRegistrationInputs(this);
                });
            }
        });
    }

    function enforceServiceEnableState() {
        let selectedSubIds = $('#sub_category_ids').val() || []; // array

        // Loop services and disable/enable
        $('#service_ids option').each(function() {
            let parentId = $(this).data('subcategory').toString();

            if (selectedSubIds.includes(parentId)) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true).prop('selected', false);
            }
        });

        // Refresh Select2
        $('#service_ids').select2('destroy');

        $('#service_ids').select2({
            placeholder: '{{ __('admin.choose_service') }}',
            width: '100%',
            dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}",
            minimumResultsForSearch: 0
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
