@extends('core.mini-app.app')

@section('head')
@endsection

@section('content')

    @if ($mini_app_platform == 'telegram')
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

                const items = document.querySelectorAll(".ref-to-player");

                items.forEach(item => {
                    var old_ref = item.getAttribute('href');
                    var new_ref = old_ref.replace('MESSENGER_USER_ID', id);
                    item.setAttribute('href', new_ref);
                });

                if (first_name!="undefined") {
                    document.getElementById('username').innerHTML = "😎 "+first_name;
                }
                app.ready();
            };
        </script>
    @endif

    @if ($mini_app_platform == 'max')
        <script src="{{env('APP_URL')}}/js/max-web-app.js"></script>

        <script>
            window.onload = function() {
                let app = window.WebApp;
                let id = app.initDataUnsafe.user.id;

                @if (isset($mini_app_page->back_button_url))

                app.BackButton.show();
                app.BackButton.onClick(function() {
                    window.location.href="{{$mini_app_page->back_button_url}}?platform=max&max_user_id="+id;
                });

                @else

                //app.BackButton.hide();

                @endif

                let first_name = app.initDataUnsafe.user.first_name;
                const items = document.querySelectorAll(".ref-to-player");

                items.forEach(item => {
                    var old_ref = item.getAttribute('href');
                    var new_ref = old_ref.replace('MESSENGER_USER_ID', id);
                    item.setAttribute('href', new_ref);
                });

                if (first_name!="undefined") {
                    document.getElementById('username').innerHTML = "😎 "+first_name;
                }

            };
        </script>
    @endif

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

                    <a href="/miniapp/player/{{$video->id}}/{{$mini_app_platform}}/MESSENGER_USER_ID/{{$mini_app_page->url}}" class="ref-to-player flex justify-between align-center text-md font-semibold text-indigo-500">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#FB7185" style="width: 15%">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                        </svg>

                        <span class="text-justify ml-5" style="width: 85%">{{$bscount}}. {{$video->name}}</span>
                    </a>

                @if ($bscount != count($videos))
                        <hr class="block my-3.5 mx-6 text-gray-400 text-center">
                @endif

                @endforeach
            </div>

        </div>
    </div>

@endsection
