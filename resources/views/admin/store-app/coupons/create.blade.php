@extends('layouts.master')
@section('title')
    {{ __('admin.new_coupon') }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('store_app.admin.coupons.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.new_coupon') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.add_coupon') }}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('store_app.admin.coupons.store') }}" method="POST">
                        @csrf
                        @include('layouts.session')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="store_id">{{ __('admin.stores') }}
                                        <span class="text-danger fw-bolder">*</span>
                                    </label>
                                    <select name="store_id" class="form-control @error('store_id') is-invalid @enderror"
                                        id="store_id" required>
                                        <option value="" selected disabled>{{ __('admin.choose_store') }}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @selected(old('store_id') == $store->id)>
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
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="max_used_times">{{ __('admin.max_used_times') }}</label>
                                    <input type="number" min="1"
                                        class="form-control @error('max_used_times') is-invalid @enderror"
                                        id="max_used_times" name="max_used_times"
                                        placeholder="{{ __('admin.max_used_times_placeholder') }}"
                                        value="{{ old('max_used_times') }}">
                                    @error('max_used_times')
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
                        <button class="btn btn-primary" type="submit">{{ __('admin.add_coupon') }}</button>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
