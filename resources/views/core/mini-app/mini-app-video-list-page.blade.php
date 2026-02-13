@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            app.BackButton.show();

            app.BackButton.onClick(function() {
                window.location.href="/app1";
            });

            let id = app.initDataUnsafe.user.id;
            let first_name = app.initDataUnsafe.user.first_name;

            const elementsArray = $('.ref-to-player');

            elementsArray.forEach(el => {
                var old_ref = el.attr('href');
                var new_ref = old_ref.replace('MESSENGER_USER_ID', id);
                el.attr('href', new_ref);
            });

            if (first_name!="undefined") {
                document.getElementById('username').innerHTML = "😎 "+first_name;
            }
            app.ready();
        };
    </script>

    <div class="isolate overflow-y-scroll bg-white h-[100vh]">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-5 ml-4 font-semibold text-xl"></div>

            @php
                $bscount = 0;
            @endphp

            <div class="mx-5">
                @foreach ($videos as $video)
                    @php
                        $bscount = $bscount + 1;
                    @endphp

                    <a href="/miniapp/player/{{$video->id}}/MESSENGER_USER_ID" class="ref-to-player px-3.5 block text-md font-semibold text-indigo-500">
                        {{$bscount}}. {{$video->name}}
                    </a>

                @if ($bscount != count($videos))
                        <hr class="block my-3.5 mx-6 text-gray-400 text-center">
                @endif

                @endforeach
            </div>

        </div>
    </div>

@endsection
