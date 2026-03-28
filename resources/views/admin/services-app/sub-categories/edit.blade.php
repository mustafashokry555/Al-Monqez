@extends('layouts.master')
@section('title')
    {{ __('admin.edit_sub_category') }}
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
            {{ __('admin.edit_sub_category') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_sub_category') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.sub.categories.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                sub_category_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="sub_category_id" value="{{ $subCategory->id }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $subCategory->imageLink }}" target="_blanck">
                                            <img src="{{ $subCategory->imageLink }}" alt="{{ __('admin.image') }}"
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
                                    <label class="form-label" for="sub_category_type">{{ __('admin.sub_category_type') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <select
                                        class="form-control form-select @error('sub_category_type') is-invalid @enderror"
                                        id="sub_category_type" name="sub_category_type" required>
                                        <option value="" selected disabled>{{ __('admin.select_type') }}
                                        </option>
                                        @for ($i = 0; $i < 2; $i++)
                                            <option value="{{ $i }}"
                                                @if ($subCategory->sub_category_type == "$i") selected @endif>
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
                                                @if ($subCategory->location_type == "$i") selected @endif>
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
                                                @if ($subCategory->category_id == $category->id) selected @endif>{{ $category->name }}
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
                                            placeholder="{{ __('admin.name') }}"
                                            value="{{ old("name_$language") ? old("name_$language") : $subCategory->{"name_$language"} }}"
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
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_sub_category') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
