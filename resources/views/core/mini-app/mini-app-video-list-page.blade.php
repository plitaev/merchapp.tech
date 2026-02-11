@extends('core.mini-app.app')

{{--
<video autoplay loop muted playsinline class="z-1 rounded-xl">
    <source src="https://svoiludi-a.akamaihd.net/video_kovalchuk.mp4" type="video/mp4"/>
</video>
--}}

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

            var ref_to_player = $('.ref-to-player');

            var old_ref = ref_to_player.attr('href');
            var new_ref = old_ref.replace('MESSENGER_USER_ID', id);
            ref_to_player.attr('href', new_ref);

            if (first_name!="undefined") {
                document.getElementById('username').innerHTML = "😎 "+first_name;
            }
            app.ready();
        };
    </script>

    <div class="isolate overflow-y-scroll bg-[#f1f1f1] h-[100vh]">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            @php
                $bscount = 0;
            @endphp

            <div class="mx-5">
                @foreach ($videos as $video)
                    @php
                        $bscount = $bscount + 1;
                    @endphp

                    <a href="/miniapp/player/{{$video->id}}/MESSENGER_USER_ID" class="ref-to-player block my-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-indigo-50 shadow-xs hover:bg-indigo-100">
                        {{$bscount}}. {{$video->name}}
                    </a>

                @endforeach
            </div>

        </div>
    </div>

@endsection
