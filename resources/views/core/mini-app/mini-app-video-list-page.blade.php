@extends('core.mini-app.app')

{{--
<video autoplay loop muted playsinline class="z-1 rounded-xl">
    <source src="https://svoiludi-a.akamaihd.net/video_kovalchuk.mp4" type="video/mp4"/>
</video>
--}}

@section('head')
@endsection

@section('content')

    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            app.BackButton.show();

            app.BackButton.onClick(function() {
                window.location.href="/app1";
            });

            let first_name = app.initDataUnsafe.user.first_name;

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

            @foreach ($videos as $video)
                @php
                    $bscount = $bscount + 1;
                @endphp

                @if ($bscount==1)
                    <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                        @endif

                        <a href="" class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                            <div>
                                <img src="{{env('APP_URL').'/content/'.$video->image}}?updated_at={{base64_encode($video->updated_at)}}" class="rounded-xl"/>
                            </div>

                            <div aria-describedby="tier-team" class="mt-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_medium->miniapp_banner->button_bg_color}}; color: {{$banner_medium->miniapp_banner->button_text_color}}">{{$banner_medium->miniapp_banner->button_text}}</div>
                        </a>

                        @if ($bscount==2)
                    </div>

                    @php
                        $bscount = 0;
                    @endphp
                @endif

            @endforeach

            {{------}}

        </div>
    </div>

@endsection
