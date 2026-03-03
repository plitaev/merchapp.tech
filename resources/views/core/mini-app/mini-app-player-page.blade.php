@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')
    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            app.BackButton.show();
            let id = app.initDataUnsafe.user.id;

            app.BackButton.onClick(function() {
                window.location.href="{{env("APP_URL")}}/{{$back_page}}?telegram_chat_id="+id;
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

            <video-js id="player" class="vjs-default-skin" controls preload="auto" width="960" height="540" disablePictureInPicture playsinline>

                @php $track_count = 0; @endphp

                @if (!isset($video->other_hls_tracks))
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
                @endif

                @if (isset($video->other_hls_tracks))

                    @php
                        $track_count = 0;
                        $tracks_other = explode(',', $video->other_hls_tracks);
                        $tracknames_other = explode(',', $video->other_hls_track_names);
                    @endphp

                    @foreach ($tracks_other as $track_other)
                        @php
                                 if (isset($tracknames_other[$track_count])) {
                                    $track_name = $tracknames_other[$track_count];
                                } else {
                                    $track_name = 'HD';
                                }

                                 $track_count = $track_count + 1;
                        @endphp

                        <source src="{{env('OTHER_HLS_TRACK_URL')}}/{{$video->other_hls_video_id}}/{{$track_other}}" type="application/x-mpegURL" label="{{$track_name}}">
                    @endforeach
                @endif

            </video-js>

            @if ($os == "Android" || $os == "MacOS")
                <a href="javascript:void(0);" onClick="Telegram.WebApp.openLink('{{env('APP_URL')}}/miniapp/external/{{$video->id}}/{{$mini_app_token}}', {try_browser: 'chrome'});" class="block rounded-md bg-indigo-50 px-3.5 py-2.5 text-sm font-semibold text-indigo-600 shadow-xs text-center">Открыть видео в браузере для просмотра во весь экран</a>
            @endif

            <div class="mt-3 mb-3">
                @foreach ($timepoints as $timepoint)
                    <a href="javascript:void(0);" onClick="player.currentTime({{\Carbon\Carbon::parse($timepoint->point)->secondsSinceMidnight()}});" class="block mt-3 mb-3 text-indigo-600 bold">{{$timepoint->point}} - {{$timepoint->name}}</a>
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
