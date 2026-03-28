@extends('layouts.master')
@section('title')
    {{ __('admin.edit_about') }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('admin.abouts.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.abouts') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_about') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('admin.abouts.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                about_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="about_id" value="{{ $about->id }}" />
                        @foreach ($languages as $language)
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="title_{{ $language }}">{{ __('admin.title') }}
                                            ({{ __("admin.$language") }})
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <input type="input"
                                            class="form-control @error('title_{{ $language }}') is-invalid @enderror"
                                            id="title_{{ $language }}" name="title_{{ $language }}"
                                            placeholder="{{ __('admin.title') }}"
                                            value="{{ old("title_$language") ? old("title_$language") : $about->{"title_$language"} }}"
                                            required>
                                        @error('title_{{ $language }}')
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
                                        <label for="description_{{ $language }}"
                                            class="form-label">{{ __('admin.description') }}
                                            ({{ __("admin.$language") }})<span
                                                class="text-danger fw-bolder">*</span></label>
                                        <textarea class="form-control @error('description_{{ $language }}') is-invalid @enderror"
                                            id="description_{{ $language }}" name="description_{{ $language }}">{{ old("description_$language") ? old("description_$language") : $about->{"description_$language"} }}</textarea>
                                        @error('description_{{ $language }}')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_about') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <!-- Internal ckeditor js -->
    <script src="{{ URL::asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/ckeditor.js') }}"></script>
@endsection
