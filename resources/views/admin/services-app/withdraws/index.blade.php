@extends('layouts.master')
@section('title')
    {{ __('admin.withdraws') }}
@endsection
@section('css')
    <!---Internal Owl Carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <!---Internal  Multislider css-->
    <link href="{{ URL::asset('assets/plugins/multislider/multislider.css') }}" rel="stylesheet">
    <!--- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .pagination-box {
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.all_withdraws') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.withdraws') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('services_app.admin.withdraws.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="status">{{ __('admin.statuses') }}</label>
                                    <select class="form-control form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                        <option value="" selected>{{ __('admin.choose_status') }}</option>
                                        @for ($i = 0; $i < 3; $i++)
                                            <option value="{{ $i }}" @selected(isset($_GET['status']) && $_GET['status'] == "$i")>
                                                {{ __("admin.withdraw_status_$i") }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary waves-effect waves-light">{{ __('admin.execution') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_withdraws') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    @component('components.errors')
                        @slot('id')
                            withdraw_id
                        @endslot
                    @endcomponent

                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.user_name') }}</th>
                                <th class="fw-bold">{{ __('admin.user_phone') }}</th>
                                <th class="fw-bold">{{ __('admin.account_holder_name') }}</th>
                                <th class="fw-bold">{{ __('admin.account_number') }}</th>
                                <th class="fw-bold">{{ __('admin.iban_number') }}</th>
                                <th class="fw-bold">{{ __('admin.bank_name') }}</th>
                                <th class="fw-bold">{{ __('admin.amount') }}</th>
                                <th class="fw-bold">{{ __('admin.status') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($withdraws) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data_found') }}</td>
                                </tr>
                            @endif
                            @foreach ($withdraws as $count => $withdraw)
                                <tr data-id="{{ $count + 1 }}"
                                    style="background-color: @if ($withdraw->status == '1') #34a853 @elseif($withdraw->status == '2') #ea4335 @endif">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $withdraw->user_name }}</td>
                                    <td class="align-middle">{{ $withdraw->user_phone }}</td>
                                    <td class="align-middle">{{ $withdraw->account_holder_name }}</td>
                                    <td class="align-middle">{{ $withdraw->account_number }}</td>
                                    <td class="align-middle">{{ $withdraw->iban_number }}</td>
                                    <td class="align-middle">{{ $withdraw->bank_name }}</td>
                                    <td class="align-middle">{{ $withdraw->amount }}</td>
                                    <td class="align-middle">{{ __("admin.withdraw_status_$withdraw->status") }}</td>
                                    <td class="align-middle">
                                        @if ($withdraw->status == '0')
                                            <div class="d-flex">
                                                <button
                                                    class="modal-effect btn btn-outline-secondary bg-warning text-dark btn-sm"
                                                    title="{{ __('admin.change_withdraw_status') }}"
                                                    data-effect="effect-newspaper" data-toggle="modal"
                                                    href="#myModal{{ $withdraw->id }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            </div>

                                            <div class="modal" id="myModal{{ $withdraw->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                {{ __('admin.change_withdraw_status') }}</h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="model-form{{ $withdraw->id }}" class="d-inline"
                                                                action="{{ route('services_app.admin.withdraws.process') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="withdraw_id"
                                                                    value="{{ $withdraw->id }}" />
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label">{{ __('admin.statuses') }}
                                                                                <span
                                                                                    class="text-danger fw-bolder">*</span></label>
                                                                            <select
                                                                                class="form-control form-select @error('status') is-invalid @enderror"
                                                                                name="status" required>
                                                                                <option value="" selected disabled>
                                                                                    {{ __('admin.choose_status') }}
                                                                                </option>
                                                                                @for ($i = 1; $i < 3; $i++)
                                                                                    <option value="{{ $i }}"
                                                                                        @selected($withdraw->status == "$i")>
                                                                                        {{ __("admin.withdraw_status_$i") }}
                                                                                    </option>
                                                                                @endfor
                                                                            </select>
                                                                            @error('status')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary waves-effect"
                                                                data-dismiss="modal">{{ __('admin.back') }}</button>
                                                            <button form="model-form{{ $withdraw->id }}" type="submit"
                                                                class="btn btn-primary waves-effect waves-light">{{ __('admin.edit') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $withdraws->appends($_GET)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
@endsection
