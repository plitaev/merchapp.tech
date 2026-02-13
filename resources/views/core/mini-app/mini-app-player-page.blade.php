@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            app.BackButton.show();

            app.BackButton.onClick(function() {

                @if ($video->id == 3)
                    window.location.href="https://magiclife.merchapp.bot/c0afec1a1e21650aee429e2ba3598490a4bde0326e289c785364b4caf452b50b";
                @endif

                @if ($video->id == 2)
                    window.location.href="https://magiclife.merchapp.bot/e73c079725bbdfe806e3b19c8ef49c8292f51445b6e13ae901a8a6bcbc43eb4f";
                @endif

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

    <style>
        .vjs-big-play-button{background: #b2b9e2 !important;}
    </style>

    <div class="p-2">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            <video-js id="player" class="vjs-default-skin" controls preload="auto" width="960" height="540" disablePictureInPicture>

                @php $track_count = 0; @endphp

                @foreach ($tracks_edgecenter as $track_edgecenter)
                    @php
                        $track_count = $track_count + 1;
                        if (isset($tracknames_edgecenter[$track_count])) {
                            $track_name = $tracknames_edgecenter[$track_count];
                        } else {
                            $track_name = 'HD';
                        }
                    @endphp

                    <source src="{{env('EDGECENTER_CDN_VIDEO')}}/videos/{{env('EDGECENTER_ACCOUNT_ID')}}_{{$video->edgecenter_slug}}/{{$track_edgecenter}}" type="application/x-mpegURL" label="{{$track_name}}">
                @endforeach
            </video-js>

            @if ($os == "Android")
                <a href="javascript:void(0);" onClick="Telegram.WebApp.openLink('{{env('APP_URL')}}/miniapp/external/{{$video->id}}/{{$mini_app_token}}', {try_browser: 'chrome'});" class="block rounded-md bg-indigo-50 px-3.5 py-2.5 text-sm font-semibold text-indigo-600 shadow-xs text-center">Открыть видео в браузере для просмотра во весь экран</a>
            @endif

            <div class="mt-3 mb-3">
                @foreach ($timepoints as $timepoint)
                    <a href="javascript:void(0);" onClick="player.currentTime({{\Carbon\Carbon::parse($timepoint->point)->secondsSinceMidnight()}});" class="block mt-3 mb-3 text-indigo-600 bold text-center">{{$timepoint->point}} - {{$timepoint->name}}</a>
                @endforeach
            </div>

            @if (isset($video->description))
                <pre class="p-6 whitespace-pre-wrap">{!! $video->description !!}</pre>
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
