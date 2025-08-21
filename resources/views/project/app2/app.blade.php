@extends('project.app2.all')

@section('head')
@endsection

@section('body')
    @include('project.app2.header')
    @hasSection('pagetitle')
        <div id="pagetitle"> @yield('pagetitle') </div>
    @endif
    @hasSection('content')
        @yield('content')
    @endif
    @include('project.app2.footer')
@endsection
