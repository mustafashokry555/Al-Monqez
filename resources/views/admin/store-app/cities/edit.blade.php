@extends('layouts.master')
@section('title')
    {{ __('admin.edit_city') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('store_app.admin.cities.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.cities') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.edit_city') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.cities.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('layouts.session')
                        @component('components.errors')
                            @slot('id')
                                city_id
                            @endslot
                        @endcomponent
                        <input type="hidden" name="city_id" value="{{ $city->id }}" />
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
                                            value="{{ old("name_$language") ? old("name_$language") : $city->{"name_$language"} }}"
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
                        <button class="btn btn-primary" type="submit">{{ __('admin.edit_city') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
