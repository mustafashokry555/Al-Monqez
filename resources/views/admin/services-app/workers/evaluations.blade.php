@extends('layouts.master')
@section('title')
    {{ __('admin.evaluations') }}
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
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('services_app.admin.workers.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.evaluations') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive border-0">
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.order') }}</th>
                                <th class="fw-bold">{{ __('admin.client') }}</th>
                                <th class="fw-bold">{{ __('admin.worker') }}</th>
                                <th class="fw-bold">{{ __('admin.message') }}</th>
                                <th class="fw-bold">{{ __('admin.rating') }}</th>
                                <th class="fw-bold">{{ __('admin.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($evaluations) == 0)
                                <tr class="align-middle">
                                    <td colspan="100" class="text-center">{{ __('admin.no_data') }}</td>
                                </tr>
                            @endif
                            @foreach ($evaluations as $count => $evaluation)
                                <tr data-id="{{ $count + 1 }}">
                                    <td style="width: 80px" class="align-middle">{{ $count + 1 }}</td>
                                    <td class="align-middle"><a
                                            href="{{ route('services_app.admin.orders.show', [$evaluation->order_id]) }}">{{ __('admin.order_details') }}</a>
                                    </td>
                                    <td class="align-middle">{{ $evaluation->client_name }}</td>
                                    <td class="align-middle">{{ $evaluation->worker_name }}</td>
                                    <td class="align-middle" style="white-space: wrap !important; min-width:200px">
                                        {{ $evaluation->message }}</td>
                                    <td class="align-middle">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($evaluation->rating))
                                                <span><i class="fa fa-star" style="color: #fbbc05;"></i></span>
                                            @else
                                                <span><i class="fa fa-star"></i></span>
                                            @endif
                                        @endfor
                                        ({{ $evaluation->rating }})
                                    </td>
                                    <td class="align-middle">{{ $evaluation->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 pagination-box">
                            {{ $evaluations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
