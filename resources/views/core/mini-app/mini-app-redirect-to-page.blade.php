@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')

    @if ($mini_app_platform == 'telegram')
        <script src="{{env('APP_URL')}}/js/telegram-web-app.js"></script>

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
    @endif

    @if ($mini_app_platform == 'max')
        <script src="{{env('APP_URL')}}/js/max-web-app.js"></script>

        <script>
            window.onload = function() {
                let app = window.WebApp;
                let id = app.initDataUnsafe.user.id;

                @if (isset($mini_app_page->redirect_to_page))
                    window.location.href="{{$mini_app_page->redirect_to_page}}?max_user_id="+id;
                @endif

            };
        </script>
    @endif

@endsection
