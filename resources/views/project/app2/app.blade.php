@extends('project.app2.all')

@section('head')
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let telegram = window.Telegram.WebApp;
            telegram.ready();

            let first_name = telegram.initDataUnsafe.user.first_name;
            if (first_name!="undefined") document.getElementById('username').innerHTML = "😎 "+first_name;
        };
    </script>

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
