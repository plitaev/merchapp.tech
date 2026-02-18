<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))


        <style>
            .fi-ta-search-field{min-width: 300px}
        </style>

        <form wire:submit.prevent="submit">{{$this->form}}</form>

    @if ($this->record > 0)
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

                            <a href="/admin/mini-app-pages/{{$record}}/preview" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                                Предварительный просмотр
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{$this->table}}
        @endif
        @if (auth()->user()->hasPermissionTo('View:MiniAppPage'))
            <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="/admin/mini-app-pages/{{$record}}/mini-app-page-blocks" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-color fi-color-success  fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    <span class="fi-btn-label">Назначить блок страницы</span>
                </a>
            </div>

        @endif
    @endif
</x-filament::page>
