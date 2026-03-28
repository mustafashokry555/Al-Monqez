@extends('layouts.master')
@section('title')
    {{ __('admin.contacts') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.all_contacts') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.all_contacts') }}!
        @endslot
        @section('css')
            <style>
                .pagination-box {
                    display: flex;
                    justify-content: flex-end;
                }

                .message-readed::after {
                    width: 0 !important;
                    height: 0 !important;
                }
            </style>
        @endsection
    @endcomponent
    <div class="row">
        <div class="col-12">
            <!-- Right Sidebar -->
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-0">{{ __('admin.all_contacts') }}</h4>
                    </div>
                    <div class="main-content-body main-content-body-mail card-body">
                        @include('layouts.session')
                        @if (count($contacts) > 0)
                            <div class="main-mail-options p-0">
                            </div>
                            @foreach ($contacts as $contact)
                                @if (isset($super_admin) || isset($contact_edit))
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}">
                                @endif
                                <div class="main-mail-list @if ($contact->read == 0) font-weight-bolder @endif">
                                    <div class="main-mail-item unread">
                                        <div class="main-img-user @if ($contact->read == 1) message-readed @endif">
                                            <img alt="{{ __('admin.image') }}"
                                                src="{{ URL::asset('uploads/defaults/default.png') }}"></div>
                                        <div class="main-mail-body">
                                            <div class="main-mail-from text-dark">
                                                {{ $contact->name }} ({{ $contact->phone }})
                                                <span
                                                    class="bg-dark text-white badge mr-2 lh-base">{{ $contact->subject }}</span>
                                            </div>
                                            <div class="main-mail-subject text-dark">
                                                {{ $contact->email }}
                                            </div>
                                        </div>
                                        <div class="main-mail-date align-middle">
                                            {{ $contact->created_at }}
                                        </div>
                                    </div>
                                </div>
                                @if (isset($super_admin) || isset($contact_edit))
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center">
                                {{ __('admin.no_messages') }}
                            </div>
                        @endif
                        <div class="row mt-3">
                            <div class="col-12 pagination-box">
                                {{ $contacts->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end Col-9 -->

        </div>

    </div><!-- End row -->
    <!-- End Page-content -->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
