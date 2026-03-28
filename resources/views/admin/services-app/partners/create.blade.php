@extends('layouts.master')
@section('title')
    {{ __('admin.new_partner') }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('services_app.admin.partners.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.new_partner') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.add_partner') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('services_app.admin.partners.store') }}" method="POST">
                        @csrf
                        @include('layouts.session')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="name">{{ __('admin.name') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('admin.name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
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
                                    <label class="form-label" for="coupon_code">{{ __('admin.coupon_code') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('coupon_code') is-invalid @enderror"
                                        id="coupon_code" name="coupon_code" placeholder="{{ __('admin.coupon_code') }}"
                                        value="{{ old('coupon_code') }}" required>
                                    @error('coupon_code')
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
                                        for="discount_percentage">{{ __('admin.discount_percentage') }}
                                        (%) <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('discount_percentage') is-invalid @enderror"
                                        id="discount_percentage" name="discount_percentage"
                                        placeholder="{{ __('admin.discount_percentage') }}"
                                        value="{{ old('discount_percentage') }}" required>
                                    @error('discount_percentage')
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
                                        for="max_discount_amount">{{ __('admin.max_discount_amount') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="number"
                                        class="form-control @error('max_discount_amount') is-invalid @enderror"
                                        id="max_discount_amount" name="max_discount_amount"
                                        placeholder="{{ __('admin.max_discount_amount') }}"
                                        value="{{ old('max_discount_amount') }}" required>
                                    @error('max_discount_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="valid_from">{{ __('admin.valid_from') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="date" class="form-control @error('valid_from') is-invalid @enderror"
                                        id="valid_from" name="valid_from" placeholder="{{ __('admin.valid_from') }}"
                                        value="{{ old('valid_from') }}" required>
                                    @error('valid_from')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="valid_until">{{ __('admin.valid_until') }}
                                        <span class="text-danger fw-bolder">*</span></label>
                                    <input type="date" class="form-control @error('valid_until') is-invalid @enderror"
                                        id="valid_until" name="valid_until" placeholder="{{ __('admin.valid_until') }}"
                                        value="{{ old('valid_until') }}" required>
                                    @error('valid_until')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('admin.add_partner') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
