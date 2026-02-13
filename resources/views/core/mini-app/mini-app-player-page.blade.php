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


    <script src="/js/video.min.7.20.1.js"></script>
    <script src="/js/videojs-http-streaming.2.14.2.js"></script>
    <script src="/js/silvermine-videojs-quality-selector.min.js"></script>
    <script src="/js/videojs-seek-buttons.min.js"></script>

    <link rel='stylesheet' href="/css/video-js.css"/>
    <link rel='stylesheet' href="/css/video-quality-selector.css"/>
    <link rel='stylesheet' href="/css/vjs-svoiludi.css"/>
    <link rel='stylesheet' href="/css/vjs-quality-selector.css">
    <link rel='stylesheet' href="/css/videojs-seek-buttons.css">

    <div class="m-2">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            <video-js id="player" class="vjs-default-skin" controls preload="auto" width="960" height="540" disablePictureInPicture>
                @foreach ($tracks_edgecenter as $track_edgecenter)
                    <source src="{{env('EDGECENTER_CDN_VIDEO')}}/videos/{{env('EDGECENTER_ACCOUNT_ID')}}_{{$video->edgecenter_slug}}/{{$track_edgecenter}}" type="application/x-mpegURL" label="{{$track_edgecenter}}">
                @endforeach
            </video-js>

            @if ($os == "Android")
                <a href="javascript:void(0);" onClick="Telegram.WebApp.openLink('{{env('APP_URL')}}/miniapp/external/{{$video->id}}/{{$mini_app_token}}', {try_browser: 'chrome'});">Открыть видео в браузере для просмотра во весь экран</a>
            @endif

            <div class="mt-3 mb-3">
                @foreach ($timepoints as $timepoint)
                    <a href="javascript:void(0);" onClick="player.currentTime({{\Carbon\Carbon::parse($timepoint->point)->secondsSinceMidnight()}});" class="block mt-3 mb-3 text-indigo-600 bold text-center">{{$timepoint->point}} - {{$timepoint->name}}</a>
                @endforeach
            </div>

            @if (isset($video->description))
                <pre class="text-justify">{!! $video->description !!}</pre>
            @endif

            <script type="text/javascript">
                var player = videojs("player",{controls: true, preload: 'auto', playbackRates: [0.50, 0.75, 1, 1.25, 1.5, 2, 2.5, 3], controlBar:{'pictureInPictureToggle': false, 'volumePanel':{'inline':false}}, poster:"", aspectRatio: '16:9',
                    html5: { hls: { overrideNative: true }, nativeVideoTracks: false, nativeAudioTracks: false, nativeTextTracks: false}});
                player.controlBar.addChild('QualitySelector');
                player.seekButtons({forward: 10, back: 10});
            </script>

        </div>
    </div>

@endsection
