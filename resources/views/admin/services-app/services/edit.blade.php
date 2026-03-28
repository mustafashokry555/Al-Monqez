@extends('layouts.master')
@section('title')
    {{ __('admin.edit_service') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('services_app.admin.services.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.edit_service') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_service') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.services.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                service_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="service_id" value="{{ $service->id }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $service->imageLink }}" target="_blanck">
                                            <img src="{{ $service->imageLink }}" alt="{{ __('admin.image') }}"
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
                                    <label class="form-label" for="sub_category_id">{{ __('admin.sub_categories') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select class="form-control form-select @error('sub_category_id') is-invalid @enderror"
                                        id="sub_category_id" name="sub_category_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_sub_category') }}
                                        </option>
                                        @foreach ($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}"
                                                @if ($service->sub_category_id == $subCategory->id) selected @endif>{{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_category_id')
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
                                            value="{{ old("name_$language") ? old("name_$language") : $service->{"name_$language"} }}"
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
                                        <label class="form-label" for="brief_{{ $language }}">{{ __('admin.brief') }}
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <input type="input"
                                            class="form-control @error('brief_{{ $language }}') is-invalid @enderror"
                                            id="brief_{{ $language }}" name="brief_{{ $language }}"
                                            placeholder="{{ __('admin.brief') }}"
                                            value="{{ old("brief_$language") ? old("brief_$language") : $service->{"brief_$language"} }}"
                                            required>
                                        @error('brief_{{ $language }}')
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
                                        <input type="input"
                                            class="form-control @error('description_{{ $language }}') is-invalid @enderror"
                                            id="description_{{ $language }}" name="description_{{ $language }}"
                                            placeholder="{{ __('admin.description') }}"
                                            value="{{ old("description_$language") ? old("description_$language") : $service->{"description_$language"} }}"
                                            required>
                                        @error('description_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_service') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
