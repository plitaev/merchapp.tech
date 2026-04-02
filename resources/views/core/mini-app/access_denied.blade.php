@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')

    <script src="{{env('APP_URL')}}/js/telegram-web-app.js"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            let id = app.initDataUnsafe.user.id;

            @if (isset($mini_app_page->back_button_url))

            app.BackButton.show();
            app.BackButton.onClick(function() {
                window.location.href="{{$mini_app_page->back_button_url}}?telegram_chat_id="+id;
            });

            @else

            app.BackButton.hide();

            @endif

            let first_name = app.initDataUnsafe.user.first_name;

            if (first_name!="undefined") {
                document.getElementById('username').innerHTML = "😎 "+first_name;
            }
            app.ready();
        };
    </script>

    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8 dark:bg-gray-900">
        <div class="text-center">
            <p class="text-base font-semibold text-indigo-600 dark:text-indigo-400">403</p>
            <p class="mt-6 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8 dark:text-white">Доступ запрещен.</p>
        </div>
    </main>


@endsection
