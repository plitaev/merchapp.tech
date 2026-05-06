@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')

    @if ($mini_app_platform == 'telegram')
        <script src="{{env('APP_URL')}}/js/telegram-web-app.js"></script>

        <script>
            window.onload = function() {
                let app = window.Telegram.WebApp;
                app.BackButton.show();

                if (!app.initDataUnsafe.user) {
                    window.location.href="/404";
                } else {

                    let id = app.initDataUnsafe.user.id;

                    app.BackButton.onClick(function() {
                        window.location.href="{{env("APP_URL")}}/{{$back_page}}?telegram_chat_id="+id;
                    });

                    let first_name = app.initDataUnsafe.user.first_name;

                    if (first_name!="undefined") {
                        document.getElementById('username').innerHTML = "😎 "+first_name;
                    }
                    app.ready();

                }
            };
        </script>
    @endif


    <script src="/js/video.min.7.20.1.js"></script>
    <script src="/js/videojs-http-streaming.2.14.2.js"></script>
    <script src="/js/silvermine-videojs-quality-selector.min.js"></script>
    <script src="/js/videojs-seek-buttons.min.js"></script>

    <link rel='stylesheet' href="/css/video-js.css"/>
    <link rel='stylesheet' href="/css/video-quality-selector.css"/>
    <link rel='stylesheet' href="/css/vjs-svoiludi.css"/>
    <link rel='stylesheet' href="/css/vjs-quality-selector.css">
    <link rel='stylesheet' href="/css/videojs-seek-buttons.css">

    <style>
        .vjs-big-play-button{background: #b2b9e2 !important;}
    </style>

    i am player page

@endsection
