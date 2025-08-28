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

    <iframe style="width: 100%; height: 100vh; overflow-y: scroll" src="/content/miniapp_pdf/{{$pdf}}.pdf" scrolling="yes"/>

@endsection
