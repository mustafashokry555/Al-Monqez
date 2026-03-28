@extends('layouts.master')
@section('title')
    {{ __('admin.reports') }}
@endsection
@section('css')
    <style>
        .pagination-box {
            display: flex;
            justify-content: flex-end;
        }

        .btn-download {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-download:hover {
            background: #218838;
            border-color: #1e7e34;
            color: white;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.all_reports') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.reports') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">{{ __('admin.all_reports') }}</h4>
                </div>
                <div class="card-body table-responsive border-0">
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.file_name') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                                <th class="fw-bold">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($reports) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($reports as $count => $report)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle">{{ $report->file_name }}</td>
                                    <td class="align-middle">{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <a class="btn btn-outline-secondary bg-success text-dark btn-sm ml-2 btn-download"
                                                title="{{ __('admin.download') }}" href="{{ $report->file_url_link }}"
                                                target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
