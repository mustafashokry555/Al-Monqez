@extends('layouts.master')
@section('title')
    {{ __('admin.edit_product') }}
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
            {{ __('admin.products') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_product') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.products.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                product_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $product->imageLink }}" target="_blanck">
                                            <img src="{{ $product->imageLink }}" alt="{{ __('admin.image') }}"
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
                        <div class="row @if(auth()->user()->role_id == 6) d-none @endif">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="store_id">{{ __('admin.stores') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select name="store_id" class="form-control @error('store_id') is-invalid @enderror"
                                        id="store_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_store') }}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @selected($product->store_id == $store->id || auth()->user()->role_id == 6)>
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
                                        <span class="text-danger fw-bolder">*</span></label>
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
                                            placeholder="{{ __('admin.name') }}"
                                            value="{{ old("name_$language") ? old("name_$language") : $product->{"name_$language"} }}"
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
                                            placeholder="{{ __('admin.description') }}" rows="3" required>{{ old("description_$language") ? old("description_$language") : $product->{"description_$language"} }}</textarea>
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
                                    <input type="number" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        name="price" placeholder="{{ __('admin.price') }}"
                                        value="{{ old('price') ? old('price') : $product->price }}" required>
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
                                    <input type="number" step="0.01"
                                        class="form-control @error('sale_price') is-invalid @enderror" id="sale_price"
                                        name="sale_price" placeholder="{{ __('admin.sale_price') }}"
                                        value="{{ old('sale_price') ? old('sale_price') : $product->sale_price }}">
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
                                        value="{{ old('quantity') ? old('quantity') : $product->quantity }}" required>
                                    @error('quantity')
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
                                    <label class="form-label" for="images">{{ __('admin.images') }}</label>
                                    <div class="d-flex">
                                        @foreach ($product->images as $image)
                                            <div class="me-2 mb-2 position-relative">
                                                <a href="{{ $image->pathLink }}" target="_blanck">
                                                    <img src="{{ $image->pathLink }}" alt="{{ __('admin.image') }}"
                                                        class="img-thumbnail wd-100p wd-sm-100" />
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute delete-image-btn"
                                                    style="top: 0; right: 0;" data-image-id="{{ $image->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="file" class="form-control @error('images') is-invalid @enderror"
                                        id="images" name="images[]" multiple>
                                    @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_product') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    @section('js')
        <script>
            $(document).ready(function() {
                $('.delete-image-btn').on('click', function() {
                    var imageId = $(this).data('image-id');
                    var url = "{{ route('store_app.admin.products.image_destroy') }}";
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
                                let selected = (classification.id ==
                                    '{{ old('classification_id') ? old('classification_id') : $product->classification_id }}'
                                ) ? 'selected' : '';
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
@endsection
