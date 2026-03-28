@extends('layouts.master')
@section('title')
    {{ __('admin.main_dashboard') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">{{ __('admin.welcome_back') }}</h2>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('admin.admins.index') }}">
                <div class="card bg-primary-gradient text-white ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-users tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_admins') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_admins }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('admin.clients.index') }}">
                <div class="card bg-purple-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-users tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_clients') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_clients }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <a href="{{ route('admin.contacts.index') }}">
                <div class="card bg-info-gradient text-white ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-message-square tx-40"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mt-0 text-center">
                                    <span class="text-white">{{ __('admin.total_contacts') }}</span>
                                    <h2 class="text-white mb-0">{{ $total_contacts }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon1 mt-2 text-center">
                                <i class="fe fe-message-square tx-40"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mt-0 text-center">
                                <span class="text-white">{{ __('admin.total_non_read_contacts') }}</span>
                                <h2 class="text-white mb-0">{{ $total_non_read_contacts }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-4 col-12">
            <div class="card bg-danger-gradient text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="icon1 mt-2 text-center">
                                <i class="fe fe-message-square tx-40"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mt-0 text-center">
                                <span class="text-white">{{ __('admin.total_read_contacts') }}</span>
                                <h2 class="text-white mb-0">{{ $total_read_contacts }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-striped table-responsive border-0 pb-0">
                    <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_admins') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.phone') }}</th>
                                <th class="fw-bold">{{ __('admin.email') }}</th>
                                <th class="fw-bold">{{ __('admin.account_status') }}</th>
                                <th class="fw-bold">{{ __('admin.joining_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($admins) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($admins as $count => $admin)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $admin->name }}</td>
                                    <td class="align-middle">{{ $admin->phone }}</td>
                                    <td class="align-middle">{{ $admin->email ? $admin->email : __('admin.unknown') }}
                                    </td>
                                    <td class="align-middle">{{ __("admin.blocked_$admin->blocked") }}</td>
                                    <td class="align-middle">{{ $admin->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-striped table-responsive border-0 pb-0">
                    <h4 class="card-title" style="font-size: 13px;">{{ __('admin.last_5_clients') }}</h4>
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.name') }}</th>
                                <th class="fw-bold">{{ __('admin.phone') }}</th>
                                <th class="fw-bold">{{ __('admin.email') }}</th>
                                <th class="fw-bold">{{ __('admin.account_status') }}</th>
                                <th class="fw-bold">{{ __('admin.joining_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($clients) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($clients as $count => $client)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $client->name }}</td>
                                    <td class="align-middle">{{ $client->phone }}</td>
                                    <td class="align-middle">{{ $client->email ? $client->email : __('admin.unknown') }}
                                    </td>
                                    <td class="align-middle">{{ __("admin.blocked_$client->blocked") }}</td>
                                    <td class="align-middle">{{ $client->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
