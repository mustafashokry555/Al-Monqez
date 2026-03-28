@extends('layouts.master2')

@section('title')
    {{ __('admin.error_404') }}
@endsection

@section('css')
    <!--- Internal Fontawesome css-->
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!---Ionicons css-->
    <link href="{{ URL::asset('assets/plugins/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <!---Internal Typicons css-->
    <link href="{{ URL::asset('assets/plugins/typicons.font/typicons.css') }}" rel="stylesheet">
    <!---Internal Feather css-->
    <link href="{{ URL::asset('assets/plugins/feather/feather.css') }}" rel="stylesheet">
    <!---Internal Falg-icons css-->
    <link href="{{ URL::asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- Main-error-wrapper -->
    <div class="main-error-wrapper  page page-h ">
        <h1 class="error-title mt-5"><span>404</span></h1>
        <h4 class="text-uppercase my-4">{{ __('admin.sorry') }} {{ __('admin.error_404') }}</h4>
        <a class="btn btn-outline-danger"
            href="{{ url()->previous() }}">{{ __('admin.back_to_dashboard') }}</a>
    </div>
    <!-- /Main-error-wrapper -->
@endsection
@section('js')
@endsection
