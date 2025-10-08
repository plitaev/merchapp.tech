<x-filament::page>
    <style>
        .appframe{width: 360px; height: 700px}
        @media (max-width:1024px) {
            .appframe{width: 100%}
        }
    </style>

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex justify-between shrink-0 items-center gap-3">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                <a href="/admin/mini-app-pages/{{$this->mini_app_page_id}}/admin" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                    <span class="fi-btn-label">Назад</span>
                </a>
            </div>

            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Предпросмотр</h1>

        </div>
    </div>

    <div class="fi-header fi-header-has-breadcrumbs">

        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                <a href="/admin/mini-app-pages/{{$this->mini_app_page_id}}/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    Назад
                </a>
            </div>
        </div>

        <div>
            <h1 class="fi-header-heading">Предпросмотр</h1>
        </div>

    </div>

    <iframe src="{{env("APP_URL")}}/{{$this->mini_app_page_url}}" class="appframe"></iframe>
</x-filament::page>
