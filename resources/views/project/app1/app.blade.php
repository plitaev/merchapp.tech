@extends('project.app1.all')

@section('head')
@endsection

@section('body')
    @include('project.app1.header')
    @hasSection('pagetitle')
        <div id="pagetitle"> @yield('pagetitle') </div>
    @endif
    @hasSection('content')
        @yield('content')
    @endif
    @include('project.app1.footer')
@endsection
