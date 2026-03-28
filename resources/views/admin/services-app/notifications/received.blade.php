@extends('layouts.master')
@section('title')
    {{ __('admin.notifications') }}
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
            {{ __('admin.all_notifications') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.notifications') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_notifications') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order') }}</th>
                                <th class="fw-bold">{{ __('admin.title') }}</th>
                                <th class="fw-bold">{{ __('admin.message') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($notifications) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($notifications as $count => $notification)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">
                                        @if ($notification->order_id == null)
                                            {{ __('admin.order_details') }}
                                        @else
                                        <a
                                            href="{{ route('services_app.admin.orders.show', [$notification->order_id]) }}">
                                            {{ __('admin.order_details') . ' #' . $notification->order_id }}
                                        </a>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $notification->title }}</td>
                                    <td class="align-middle">{{ $notification->message }}</td>
                                    <td class="align-middle">{{ $notification->created_formatted }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
