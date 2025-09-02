@extends('project.app2.app')

@section('head')
@endsection

@section('content')

    <script src="/js/jquery-3.6.0.min.js"></script>

    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        $(document).ready(function() {
            let app = window.Telegram.WebApp;

            app.BackButton.show();
            app.BackButton.onClick(function() {
                window.location.href="/app2/1";
            });
            app.ready();
        });

    </script>

    <div style="position: relative; width: 100%; height: 100vh">
        <iframe src="{{env("APP_URL")}}/content/miniapp_pdf/{{$pdf}}.pdf" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
    </div>

@endsection
