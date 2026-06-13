@extends('core.mini-app.app')

{{--
<video autoplay loop muted playsinline class="z-1 rounded-xl">
    <source src="https://svoiludi-a.akamaihd.net/video_kovalchuk.mp4" type="video/mp4"/>
</video>
--}}

@section('head')
@endsection

@section('content')



    @if ($mini_app_platform == 'telegram')
        <script src="{{env('APP_URL')}}/js/telegram-web-app.js"></script>
        <script>
            window.onload = function() {

                if (window.Telegram.WebApp && window.Telegram.WebApp != "undefined") {
                    let telegram_app = window.Telegram.WebApp;
                    let id = telegram_app.initDataUnsafe.user.id;

                    const items = document.querySelectorAll(".ref-to-banner");

                    items.forEach(item => {
                        var old_ref = item.getAttribute('href');

                        if (!old_ref.includes('viewer.html')) {
                            var new_ref = old_ref+"?telegram_chat_id="+id;
                            item.setAttribute('href', new_ref);
                        }
                    });

                    @if (isset($mini_app_page->back_button_url))
                    telegram_app.BackButton.show();
                    telegram_app.BackButton.onClick(function() {
                        window.location.href="{{$mini_app_page->back_button_url}}?platform=telegram&telegram_chat_id="+id;
                    });
                    @else
                    telegram_app.BackButton.hide();
                    @endif

                    let first_name = telegram_app.initDataUnsafe.user.first_name;

                    if (first_name!="undefined") {
                        document.getElementById('username').innerHTML = "😎 "+first_name;
                    }
                    telegram_app.ready();
                }

                if (Window.WebApp && Window.WebApp != "undefined") {
                    let max_app = Window.WebApp;
                    let id = max_app.initDataUnsafe.user.id;

                    document.getElementById('username').innerHTML = "😎 "+id;
                }

            };
        </script>
    @endif

    @if ($mini_app_platform == 'max')
        <script src="{{env('APP_URL')}}/js/max-web-app.js"></script>

        <script>
            window.onload = function() {
                let app = window.WebApp;
                let id = app.initDataUnsafe.user.id;

                const items = document.querySelectorAll(".ref-to-banner");

                items.forEach(item => {
                    var old_ref = item.getAttribute('href');

                    if (!old_ref.includes('viewer.html')) {
                        var new_ref = old_ref + "?platform=max&max_user_id=" + id;
                        item.setAttribute('href', new_ref);
                    }
                });

                @if (isset($mini_app_page->back_button_url))

                if (app.platform != 'desktop') {
                        app.BackButton.show();
                        app.BackButton.onClick(function () {
                            window.location.href = "{{$mini_app_page->back_button_url}}?platform=max&max_user_id=" + id;
                        });
                } else {
                    document.getElementById('max-desktop-back-button-container').setAttribute('style', 'display: block');
                    document.getElementById('max-desktop-back-button').setAttribute('href', "{{$mini_app_page->back_button_url}}?platform=max&max_user_id=" + id);
                }

                @else

                if (app.platform != 'desktop') {
                    app.BackButton.hide();
                } else {
                    document.getElementById('max-desktop-back-button-container').setAttribute('style', 'display: none');
                    document.getElementById('max-desktop-back-button').setAttribute('href', "javascript:void(0);");
                }

                @endif

                if (first_name!="undefined") {
                    document.getElementById('username').innerHTML = "😎 "+first_name;
                }

            };
        </script>

    @endif

    <div class="isolate overflow-y-scroll bg-[#f1f1f1] h-[100vh]">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            @if (isset($mini_app_page->back_button_url))
                <div class="text-center mb-10" id="max-desktop-back-button-container" style="display: none">
                    <a href="javascript:void(0);" id="max-desktop-back-button" class="inline-block mx-auto rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-xs hover:bg-indigo-100 dark:bg-indigo-500/20 dark:text-indigo-400 dark:shadow-none dark:hover:bg-indigo-500/30">Вернуться назад</a>
                </div>
            @endif

            @foreach ($banners_big as $banner_big)
                <div class="mt-3 ml-3 mr-3">
                    <a href="{{$banner_big->miniapp_banner->button_url}}" class="ref-to-banner relative flex flex-col justify-between w-full rounded-xl bg-cover shadow-xl ring-1 ring-gray-900/10">
                        <img src="{{env('APP_URL').'/content/'.$banner_big->miniapp_banner->image}}?updated_at={{base64_encode($banner_big->updated_at)}}" class="z-1 rounded-xl"/>

                        @if (isset($banner_big->miniapp_banner->button_text))
                            <div aria-describedby="tier-hobby" class="inset-x-0 bottom-0 absolute z-2 m-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_big->button_bg_color}}; color: {{$banner_big->button_text_color}}">{{$banner_big->miniapp_banner->button_text}}</div>
                        @endif

                    </a>
                </div>
            @endforeach

            {{------}}

            @php
                $bscount = 0;
            @endphp

            @foreach ($banners_medium as $banner_medium)
                @php
                    $bscount = $bscount + 1;
                @endphp

                @if ($bscount==1)
                    <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                        @endif

                        <a href="{{$banner_medium->miniapp_banner->button_url}}" class="ref-to-banner flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                            <div>
                                <img src="{{env('APP_URL').'/content/'.$banner_medium->miniapp_banner->image}}?updated_at={{base64_encode($banner_medium->miniapp_banner->updated_at)}}" class="rounded-xl"/>
                            </div>

                            @if (isset($banner_medium->miniapp_banner->button_text))
                                <div aria-describedby="tier-team" class="mt-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_medium->miniapp_banner->button_bg_color}}; color: {{$banner_medium->miniapp_banner->button_text_color}}">{{$banner_medium->miniapp_banner->button_text}}</div>
                            @endif

                        </a>

                        @if ($bscount==2)
                    </div>

                    @php
                        $bscount = 0;
                    @endphp
                @endif

            @endforeach

            {{------}}

            @php
                $bscount = 0;
            @endphp

            @foreach ($banners_small as $banner_small)
                @php
                    $bscount = $bscount + 1;
                @endphp

                @if ($bscount==1)
                    <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                        @endif

                        <a href="{{$banner_small->miniapp_banner->button_url}}" class="ref-to-banner flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                            <div>
                                <img src="{{env('APP_URL').'/content/'.$banner_small->miniapp_banner->image}}?updated_at={{base64_encode($banner_small->miniapp_banner->updated_at)}}" class="rounded-xl"/>
                            </div>

                            @if (isset($banner_small->miniapp_banner->button_text))
                                <div aria-describedby="tier-team" class="mt-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_small->miniapp_banner->button_bg_color}}; color: {{$banner_small->miniapp_banner->button_text_color}}">{{$banner_small->miniapp_banner->button_text}}</div>
                            @endif

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
