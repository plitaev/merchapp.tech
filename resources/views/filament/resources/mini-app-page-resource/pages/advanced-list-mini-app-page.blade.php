<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))

    <div class="fi-header fi-header-has-breadcrumbs">
        <div>
            <h1 class="fi-header-heading">Баннеры</h1>
        </div>

        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                @if (auth()->user()->can('Create:MiniAppBanner'))

                    <a href="/admin/mini-app-banners/{{$record}}/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                        Создать баннер
                    </a>
                @endif
                <a href="/admin/mini-app-pages/{{$record}}/preview" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    Предварительный просмотр
                </a>
            </div>
        </div>
    </div>

    {{$this->table}}

        @endif
</x-filament-panels::page>
