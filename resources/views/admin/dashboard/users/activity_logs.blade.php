@extends('layouts.master')
@section('title')
    {{ __('admin.all_users') }}
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
            {{ __('admin.all_users') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.users_activity_logs') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_users') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.image') }}</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.phone') }}</th>
                                <th class="fw-bold">{{ __('admin.device_type') }}</th>
                                <th class="fw-bold">{{ __('admin.is_online') }}</th>
                                <th class="fw-bold">{{ __('admin.last_active_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($activity_logs) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($activity_logs as $count => $log)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">
                                        <a href="{{ $log->imageLink }}" target="_blanck">
                                            <img src="{{ $log->imageLink }}" alt="{{ __('admin.image') }}"
                                                style="width: 50px;" />
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $log->name }}</td>
                                    <td class="align-middle">{{ $log->phone }}</td>
                                    <td class="align-middle">{{ $log->device_type ?? '-' }}</td>
                                    <td class="align-middle">
                                        @if ($log->is_online)
                                            <span class="badge badge-success">{{ __('admin.online') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('admin.offline') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $log->last_active_at ? $log->last_active_at : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $activity_logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
