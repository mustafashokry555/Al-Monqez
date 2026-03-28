@extends('layouts.master')
@section('title')
    {{ __('admin.contact_message') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}" href="{{ route('admin.contacts.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.contact_message') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <!-- Right Sidebar -->
            <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0 ml-3">
                                <img class="rounded-circle avatar-sm" src="{{ URL::asset('uploads/defaults/default.png') }}"
                                    alt="Generic placeholder image">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="d-flex font-size-14 mb-0">
                                    <p class="me-1 mb-0">{{ $contact->name }} ({{ $contact->phone }})</p>
                                </h5>
                                <small class="text-muted">{{ $contact->email }}</small>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <h4 class="d-inline">{{ __('admin.message_number') }} : </h4><span
                                    class="font-size-20 text-primary">{{ $contact->id }}</span>
                            </div>
                        </div>
                        <h3>{{ $contact->subject }}</h3>
                        <p>{{ $contact->message }}</p>
                        <p><span class="text-muted">{{ $contact->created_at }}</span>
                        <p>
                            @if (isset($super_admin) || isset($contact_delete))
                                <form action="{{ route('admin.contacts.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                                    <button type="submit" class="btn btn-danger waves-effect mt-4"><i
                                            class="mdi mdi-trash-can-outline me-1"></i>
                                        {{ __('admin.delete_message') }}</button>
                                </form>
                            @endif
                    </div>

                </div>
            </div>
            <!-- card -->

        </div>
        <!-- end Col -->

    </div>
    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
