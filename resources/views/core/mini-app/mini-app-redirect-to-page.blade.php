@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')

    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            let id = app.initDataUnsafe.user.id;

            @if (isset($mini_app_page->redirect_to_page))
                window.location.href="{{$mini_app_page->redirect_to_page}}?telegram_chat_id="+id;
            @endif

            app.ready();
        };
    </script>
@endsection
