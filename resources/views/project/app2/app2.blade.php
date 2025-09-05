@extends('project.app2.app')

@section('head')
@endsection

@section('content')

    <script>
        $(document).ready(function() {
            let app_chat_id = app.initDataUnsafe.user.id;

            app.BackButton.hide();
            @if ($no_rdr==0)
            let startParam = app.initDataUnsafe.start_param;
            if (startParam != undefined) window.location.href = "/pdf/web/viewer.html?bot_id={{$mini_app->bot_id}}&doc="+startParam;
            @endif

            console.log({{$mini_app->bot_id}});

            @if (isset($mini_app->bot_id))
            var dataToSend = {
                bot_id: {{$mini_app->bot_id}},
                chat_id: app_chat_id,
            };

            $.ajax({
                url: '/pdf/rights_check',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(dataToSend),
                success: function(data) {
                    console.log(data);

                    if (!data || data!=1) {
                        $(".lock-button").attr("href", "javascript:void(0);");
                    }
                },
                error: function(xhr, status, error) {
                    document.location.href="/pdf/access_denied/" + app_bot_id;
                }
            });
            @endif

        });
    </script>

    <div class="isolate overflow-y-scroll bg-[#f1f1f1] h-[100vh]">

        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            <div style="margin: 25px 0">{{$_SERVER['REQUEST_URI']}} >>></div>

            @foreach ($banners_big as $banner_big)
                <div class="mt-3 ml-3 mr-3">
                    <div class="relative flex flex-col justify-between w-full rounded-xl bg-cover shadow-xl ring-1 ring-gray-900/10">
                        <img src="{{env('APP_URL').'/content/'.$banner_big->miniapp_banner->image}}?updated_at={{base64_encode($banner_big->miniapp_banner->updated_at)}}" class="z-1 rounded-xl"/>
                        <a href="{{$banner_big->miniapp_banner->button_url}}" aria-describedby="tier-hobby" class="lock-button inset-x-0 bottom-0 absolute z-2 m-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_big->miniapp_banner->button_bg_color}}; color: {{$banner_big->miniapp_banner->button_text_color}}">{{$banner_big->miniapp_banner->button_text}}</a>
                    </div>
                </div>
            @endforeach

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

                        <div class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                            <div>
                                <img src="{{env('APP_URL').'/content/'.$banner_medium->miniapp_banner->image}}?updated_at={{base64_encode($banner_medium->miniapp_banner->updated_at)}}" class="rounded-xl"/>
                            </div>

                            <a href="{{$banner_medium->miniapp_banner->button_url}}" aria-describedby="tier-teammm" class="lock-button mt-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_medium->miniapp_banner->button_bg_color}}; color: {{$banner_medium->miniapp_banner->button_text_color}}">{{$banner_medium->miniapp_banner->button_text}}</a>
                        </div>

                        @if ($bscount==2)
                    </div>

                    @php
                        $bscount = 0;
                    @endphp
                @endif

            @endforeach

        </div>
    </div>

@endsection
