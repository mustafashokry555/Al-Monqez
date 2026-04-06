@extends('layouts.master')
@section('title')
    {{ __('admin.new_product') }}
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
                href="{{ route('store_app.admin.products.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.new_product') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.add_product') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.products.store') }}" method="POST"
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
                        <div class="row @if (auth()->user()->role_id == 6) d-none @endif">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="store_id">{{ __('admin.stores') }}
                                        <span class="text-danger fw-bolder">*</span>
                                    </label>
                                    <select name="store_id" class="form-control @error('store_id') is-invalid @enderror"
                                        id="store_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_store') }}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @selected(old('store_id') == $store->id || auth()->user()->role_id == 6)>
                                                {{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
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
                                    <label class="form-label" for="classification_id">{{ __('admin.classifications') }}
                                        <span class="text-danger fw-bolder">*</span>
                                    </label>
                                    <select name="classification_id"
                                        class="form-control @error('classification_id') is-invalid @enderror"
                                        id="classification_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_classification') }}
                                        </option>
                                    </select>
                                    @error('classification_id')
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
                                    <label class="form-label" for="patch_id">{{ __('admin.patch') }}</label>
                                    <select name="patch_id" class="form-control @error('patch_id') is-invalid @enderror"
                                        id="patch_id">
                                        <option value="" selected>{{ __('admin.none') }}</option>
                                        @foreach ($patches as $patch)
                                            <option value="{{ $patch->id }}" @selected(old('patch_id') == $patch->id)>
                                                {{ $patch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patch_id')
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label"
                                            for="description_{{ $language }}">{{ __('admin.description') }}
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <textarea class="form-control @error('description_{{ $language }}') is-invalid @enderror"
                                            id="description_{{ $language }}" name="description_{{ $language }}"
                                            placeholder="{{ __('admin.description') }}" rows="3" required>{{ old("description_$language") }}</textarea>
                                        @error('description_{{ $language }}')
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
                                    <label class="form-label" for="price">{{ __('admin.price') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" placeholder="{{ __('admin.price') }}"
                                        value="{{ old('price') }}" step="0.01" min="0" required>
                                    @error('price')
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
                                    <label class="form-label" for="sale_price">{{ __('admin.sale_price') }}</label>
                                    <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                        id="sale_price" name="sale_price" placeholder="{{ __('admin.sale_price') }}"
                                        value="{{ old('sale_price') }}" step="0.01" min="0">
                                    @error('sale_price')
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
                                    <label class="form-label" for="quantity">{{ __('admin.quantity') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity" placeholder="{{ __('admin.quantity') }}"
                                        value="{{ old('quantity') }}" min="0" required>
                                    @error('quantity')
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
                                    <label class="form-label" for="images">{{ __('admin.images') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="file" class="form-control @error('images') is-invalid @enderror"
                                        id="images" name="images[]" required multiple>
                                    @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('admin.add_product') }}</button>
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
        function getClassificationsByStore() {
            var storeId = $('#store_id').val();
            var classificationSelect = $('#classification_id');
            classificationSelect.empty();
            classificationSelect.append(
                `<option value="" selected disabled>{{ __('admin.choose_classification') }}</option>`);

            if (storeId) {
                $.ajax({
                    url: '{{ route('store_app.admin.classifications.get_classifications') }}',
                    type: 'GET',
                    data: {
                        store_id: storeId
                    },
                    success: function(response) {
                        response.classifications.forEach(function(classification) {
                            let selected = (classification.id == '{{ old('classification_id') }}') ?
                                'selected' : '';
                            classificationSelect.append(
                                `<option value="${classification.id}" ${selected}>${classification.name}</option>`
                            );
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }

        $(document).ready(function() {
            getClassificationsByStore();
            $('#store_id').on('change', function() {
                getClassificationsByStore();
            });
        });
    </script>
@endsection
