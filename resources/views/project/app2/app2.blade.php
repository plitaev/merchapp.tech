@extends('project.app1.app')

@section('head')
@endsection

@section('content')

    <div class="bg-white dark:bg-gray-900">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto dark:hidden" />
                        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="" class="h-8 w-auto not-dark:hidden" />
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-200">
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Product</a>
                    <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Features</a>
                    <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Marketplace</a>
                    <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Company</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Log in <span aria-hidden="true">&rarr;</span></a>
                </div>
            </nav>
            <el-dialog>
                <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
                    <div tabindex="0" class="fixed inset-0 focus:outline-none">
                        <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10 dark:bg-gray-900 dark:sm:ring-gray-100/10">
                            <div class="flex items-center justify-between">
                                <a href="#" class="-m-1.5 p-1.5">
                                    <span class="sr-only">Your Company</span>
                                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto dark:hidden" />
                                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="" class="h-8 w-auto not-dark:hidden" />
                                </a>
                                <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700 dark:text-gray-200">
                                    <span class="sr-only">Close menu</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-6 flow-root">
                                <div class="-my-6 divide-y divide-gray-500/10 dark:divide-white/10">
                                    <div class="space-y-2 py-6">
                                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">Product</a>
                                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">Features</a>
                                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">Marketplace</a>
                                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">Company</a>
                                    </div>
                                    <div class="py-6">
                                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">Log in</a>
                                    </div>
                                </div>
                            </div>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <div class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20 dark:text-gray-400 dark:ring-white/10 dark:hover:ring-white/20">
                        Announcing our next round of funding. <a href="#" class="font-semibold text-indigo-600 dark:text-indigo-400"><span aria-hidden="true" class="absolute inset-0"></span>Read more <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl dark:text-white">Data to enrich your online business</h1>
                    <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8 dark:text-gray-400">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">Get started</a>
                        <a href="#" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Learn more <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>
            <div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75"></div>
            </div>
        </div>
    </div>

    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script>
        window.onload = function() {
            let app = window.Telegram.WebApp;
            console.log(app);
            app.BackButton.hide();

            let first_name = app.initDataUnsafe.user.first_name;

            if (first_name!="undefined") {
                document.getElementById('username').innerHTML = "😎 "+first_name;
            }

            app.ready();

            @if ($no_rdr==0)
            let startParam = app.initDataUnsafe.start_param;
            if (startParam != undefined) window.location.href = "/pdf/web/viewer.html?doc="+startParam;
            @endif

        };
    </script>

    <div class="isolate overflow-y-scroll bg-[#f1f1f1] h-[100vh]">
        <div class="flow-root pb-24 sm:pb-32">
            <div id="username" class="mt-2 mb-2 ml-4 font-semibold text-xl"></div>

            @foreach ($banners_big as $banner_big)
                <div class="mt-3 ml-3 mr-3">
                    <div class="relative flex flex-col justify-between w-full rounded-xl bg-cover shadow-xl ring-1 ring-gray-900/10">
                        <img src="{{env('APP_URL').'/content/'.$banner_big->miniapp_banner->image}}?updated_at={{base64_encode($banner_big->miniapp_banner->updated_at)}}" class="z-1 rounded-xl"/>
                        <a href="{{$banner_big->miniapp_banner->button_url}}" aria-describedby="tier-hobby" class="inset-x-0 bottom-0 absolute z-2 m-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_big->miniapp_banner->button_bg_color}}; color: {{$banner_big->miniapp_banner->button_text_color}}">{{$banner_big->miniapp_banner->button_text}}</a>
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

                            <a href="{{$banner_medium->miniapp_banner->button_url}}" aria-describedby="tier-team" class="mt-2 block rounded-md px-3.5 py-2 text-center text-sm/6 font-semibold shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400" style="background-color: {{$banner_medium->miniapp_banner->button_bg_color}}; color: {{$banner_medium->miniapp_banner->button_text_color}}">{{$banner_medium->miniapp_banner->button_text}}</a>
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
