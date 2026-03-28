@extends('layouts.master')
@section('title')
    {{ __('admin.complaints') }}
@endsection
@section('css')
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
            {{ __('admin.all_complaints') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.complaints') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_complaints') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order') }}</th>
                                <th class="fw-bold">{{ __('admin.message') }}</th>
                                <th class="fw-bold">{{ __('admin.status') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($complaints) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($complaints as $count => $complaint)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route('services_app.admin.orders.show', [$complaint->order_id]) }}">
                                            {{ __('admin.order_details') . ' #' . $complaint->order_id }}
                                        </a>
                                    </td>
                                    <td class="align-middle" style="white-space: normal;">{{ $complaint->message }}</td>
                                    <td class="align-middle">{{ __("admin.complaint_status_$complaint->status") }}</td>
                                    <td class="align-middle">{{ $complaint->created_at }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            @if ($complaint->status == '0')
                                                <button
                                                    class="modal-effect btn btn-outline-secondary bg-success text-dark btn-sm ml-2"
                                                    title="{{ __('admin.accept') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#acceptModal{{ $complaint->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button
                                                    class="modal-effect btn btn-outline-secondary bg-danger text-dark btn-sm"
                                                    title="{{ __('admin.reject') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#rejectModal{{ $complaint->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            @if ($complaint->status == '1')
                                                <button
                                                    class="modal-effect btn btn-outline-secondary bg-primary text-white btn-sm"
                                                    title="{{ __('admin.complete') }}" data-effect="effect-newspaper"
                                                    data-toggle="modal" href="#completeModal{{ $complaint->id }}">
                                                    {{ __('admin.complete') }}
                                                </button>
                                            @endif
                                        </div>
                                        @if ($complaint->status == '0')
                                            <div class="modal" id="acceptModal{{ $complaint->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.accept_complaint') }}
                                                            </h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.accept_complaint_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('services_app.admin.orders.complaints.process') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="complaint_id"
                                                                    value="{{ $complaint->id }}" />
                                                                <input type="hidden" name="status" value="1" />
                                                                <button type="button"
                                                                    class="btn btn-secondary waves-effect"
                                                                    data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-success waves-effect waves-light">{{ __('admin.accept') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal" id="rejectModal{{ $complaint->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.reject_complaint') }}
                                                            </h5>
                                                            <button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.reject_complaint_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('services_app.admin.orders.complaints.process') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="complaint_id"
                                                                    value="{{ $complaint->id }}" />
                                                                <input type="hidden" name="status" value="3" />
                                                                <button type="button"
                                                                    class="btn btn-secondary waves-effect"
                                                                    data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger waves-effect waves-light">{{ __('admin.reject') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($complaint->status == '1')
                                            <div class="modal" id="completeModal{{ $complaint->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('admin.complete_complaint') }}
                                                            </h5>
                                                            <button aria-label="Close" class="close"
                                                                data-dismiss="modal" type="button"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('admin.complete_complaint_message') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form class="d-inline"
                                                                action="{{ route('services_app.admin.orders.complaints.process') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="complaint_id"
                                                                    value="{{ $complaint->id }}" />
                                                                <input type="hidden" name="status" value="2" />
                                                                <button type="button"
                                                                    class="btn btn-secondary waves-effect"
                                                                    data-dismiss="modal">{{ __('admin.back') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-light">{{ __('admin.complete') }}</button>
                                                            </form>
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
                            {{ $complaints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
