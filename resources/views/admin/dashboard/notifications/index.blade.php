@extends('layouts.master')
@section('title')
    {{ __('admin.notifications') }}
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
                    <button data-effect="effect-newspaper" data-toggle="modal" href="#myModal"
                        class="btn btn-primary button-icon"><i
                            class="far fa-paper-plane ml-2 font-weight-bolder"></i>{{ __('admin.send_notification') }}</button>
                </div>
                <div class="card-body table-responsive border-0">
                    @include('layouts.session')
                    <table id="datatable" class="table table-bordered dt-responsive text-nowrap w-100">
                        <thead>
                            <tr style="cursor: pointer;">
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">{{ __('admin.notification_to') }}</th>
                                <th class="fw-bold">{{ __('admin.user_phone') }}</th>
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
                                    <td class="align-middle">{{ __('admin.user_type_' . $notification->type) }}</td>
                                    <td class="align-middle">{{ $notification->phone ?? __('admin.not_found') }}</td>
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

    <div class="modal" id="myModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.send_notification') }}</h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="acceptForm" class="d-inline" action="{{ route('admin.notifications.store') }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="type">{{ __('admin.notification_to') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <select type="input" class="form-control @error('type') is-invalid @enderror"
                                        id="type" name="type" required>
                                        <option value="" disabled selected>{{ __('admin.choose_users') }}</option>
                                        @for ($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}" @selected(old('type') == "$i")>
                                                {{ __('admin.user_type_' . $i) }}</option>
                                        @endfor
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row" id="phone" @if (old('type') != '0') style="display: none;" @endif>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" placeholder="{{ __('admin.phone') }}" value="{{ old('phone') }}">
                                    @error('phone')
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
                                    <label class="form-label" for="title">{{ __('admin.title') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <input type="input" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" placeholder="{{ __('admin.title') }}"
                                        value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="message" class="form-label">{{ __('admin.message') }} <span
                                            class="text-danger fw-bolder">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" required>{{ old('message') }}</textarea>
                                    @error('message')
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
                    <button form="acceptForm" type="submit"
                        class="btn btn-success waves-effect waves-light">{{ __('admin.send') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
    <script>
        var type = document.getElementById("type");
        var phone = document.getElementById("phone");

        function togglePhoneInput() {
            if (type.value == "0") {
                phone.style.display = "block";
            } else {
                phone.style.display = "none";
            }
        }

        type.addEventListener("change", togglePhoneInput);
    </script>
@endsection
