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

            <video-js id="player" class="vjs-default-skin" controls preload="auto" width="960" height="540" disablePictureInPicture>
                @foreach ($tracks_edgecenter as $track_edgecenter)
                    <source src="{{env('EDGECENTER_CDN_VIDEO')}}videos/{{env('EDGECENTER_ACCOUNT_ID')}}_{{$video->edgecenter_slug}}/{{$track_edgecenter}}" type="application/x-mpegURL" label="{{$track_edgecenter}}">
                @endforeach
            </video-js>

        </div>
    </div>

@endsection
