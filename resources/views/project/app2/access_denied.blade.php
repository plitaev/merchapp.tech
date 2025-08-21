@extends('project.app1.app')

@section('head')
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            let app = window.Telegram.WebApp;

            app.BackButton.show();
            app.BackButton.onClick(function() {
                window.location.href="/app2/1";
            });

            app.ready();
        });
@endsection

@section('content')

    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8 dark:bg-gray-900">
        <div class="text-center">
            <p class="text-base font-semibold text-indigo-600 dark:text-indigo-400">404</p>
            <p class="mt-6 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8 dark:text-white">У вас нет активного доступа в канал Ирины Шевченко. Чтобы получить доступ, продлите участие в клубе.</p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="https://t.me/tochka_i_club_bot?start=merchapp" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">Продлить участие</a>
            </div>
        </div>
    </main>


@endsection
