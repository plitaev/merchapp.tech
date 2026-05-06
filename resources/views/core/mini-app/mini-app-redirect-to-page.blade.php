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
        <script src="https://st.max.ru/js/max-web-app.js"></script>

        <script>
            window.onload = function() {
                let app = window.WebApp;
                let id = app.initDataUnsafe.user.id;

                @if (isset($mini_app_page->redirect_to_page))

                document.getElementById('max-test').innerHTML = app;
                @endif

            };
        </script>

        <div>max</div>
        <div id="max-test">333</div>

    @endif

@endsection
