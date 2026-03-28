@extends('layouts.master')
@section('title')
    {{ __('admin.edit_slider') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('services_app.admin.sliders.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.edit_slider') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_slider') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.sliders.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                slider_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="slider_id" value="{{ $slider->id }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="image">{{ __('admin.image') }}</label>
                                    <div>
                                        <a href="{{ $slider->imageLink }}" target="_blanck">
                                            <img src="{{ $slider->imageLink }}" alt="{{ __('admin.image') }}"
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
                                    <label class="form-label" for="link">{{ __('admin.link') }}</label>
                                    <input type="url" class="form-control @error('link') is-invalid @enderror"
                                        id="link" name="link" placeholder="{{ __('admin.link') }}"
                                        value="{{ old('link') ? old('link') : $slider->link }}">
                                    @error('link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_slider') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
