@extends('core.mini-app.all')

@section('head')
@endsection

@section('body')
    @include('core.mini-app.header')
    @hasSection('pagetitle')
        <div id="pagetitle"> @yield('pagetitle') </div>
    @endif
    @hasSection('content')
        @yield('content')
    @endif
    @include('core.mini-app.footer')
@endsection
