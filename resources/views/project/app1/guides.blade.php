@extends('project.app1.app')

@section('head')
@endsection

@section('content')
    <script src="{{env('APP_URL')}}/js/telegram-web-app.js"></script>

    <script>
        let app = window.Telegram.WebApp;
        app.BackButton.show();

        app.BackButton.onClick(function() {
            window.location.href="https://edu2.tech/app1";
        });

        app.ready();
    </script>

    <div class="isolate overflow-y-scroll bg-[#f1f1f1] h-1/4">
        <div class="flow-root pb-24 sm:pb-32 mb-10">

            <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                <a class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10"
                href="https://edu2.tech/pdf/web/viewer.html?doc=2">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_1.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_1.pdf" aria-describedby="tier-team" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </a>

                <div class="flex flex-col justify-between ml-3 mr-1.5 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_2.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_2.pdf" aria-describedby="tier-hobby" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>
            </div>

            <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                <div class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_3.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_3.pdf" aria-describedby="tier-team" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>

                <div class="flex flex-col justify-between ml-3 mr-1.5 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_4.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_4.pdf" aria-describedby="tier-hobby" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>
            </div>

            <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                <div class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_5.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_5.pdf" aria-describedby="tier-team" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>

                <div class="flex flex-col justify-between ml-3 mr-1.5 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_6.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_6.pdf" aria-describedby="tier-hobby" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>
            </div>

            <div class="mx-auto grid max-w-md grid-cols-2 mt-3">
                <div class="flex flex-col justify-between ml-1.5 mr-3 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_7.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_7.pdf" aria-describedby="tier-team" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>

                <div class="flex flex-col justify-between ml-3 mr-1.5 p-1.5 rounded-xl bg-white shadow-xl ring-1 ring-gray-900/10">
                    <div>
                        <img src="https://svoiludi-a.akamaihd.net/kovalchuk_app1/guides/kovalchuk_guide_8.png" class="rounded-xl"/>
                    </div>

                    <a href="https://svoiludi-a.akamaihd.net/kovalchuk_app1/pdf/kovalchuk_guide_8.pdf" aria-describedby="tier-hobby" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Открыть</a>
                </div>
            </div>

        </div>
    </div>

    <div class="isolate bg-[#f1f1f1] h-3/4">
        <a href="{{env('APP_URL')}}/app1" aria-describedby="tier-hobby" class="mt-2 block rounded-md bg-gray-400 px-3.5 py-2 text-center text-sm/6 font-semibold text-white shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">НАЗАД</a>
    </div>

@endsection
