<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))

    <style>
        .appframe{width: 360px; height: 700px}
        @media (max-width:1024px) {
            .appframe{width: 100%}
        }
    </style>

    <div class="fi-header fi-header-has-breadcrumbs">
        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                <a href="/admin/mini-app-pages/{{$this->mini_app_page_id}}/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    Назад
                </a>
            </div>
            <h1 class="fi-header-heading">Предпросмотр</h1>
        </div>
    </div>

    <iframe src="{{env("APP_URL")}}/{{$this->mini_app_page_url}}" class="appframe"></iframe>


    @endif
</x-filament::page>
