<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))


        <style>
            .fi-ta-search-field{min-width: 300px}
        </style>

        <form wire:submit.prevent="submit">{{$this->form}}</form>

        @if ($this->record > 0)
            <div class="fi-header fi-header-has-breadcrumbs">
                <div>
                    <h1 class="fi-header-heading">

                        @if ($this->mini_app_class_id == 1)
                            Баннеры
                        @endif

                        @if ($this->mini_app_class_id == 2)
                            Видео
                        @endif

                    </h1>
                </div>

                <div class="fi-header-actions-ctn">
                    <div class="fi-ac fi-align-start">
                        @if (auth()->user()->can('Create:MiniAppBanner'))

                            @if ($this->mini_app_class_id == 1)
                                <a href="/admin/mini-app-banners/{{$record}}/0/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                                    Создать баннер
                                </a>
                            @endif

                            @if ($this->mini_app_class_id == 2)
                                <a href="/admin/mini-app-videos/{{$record}}/0/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                                    Добавить видео
                                </a>
                            @endif

                            <a href="/admin/mini-app-pages/{{$record}}/preview" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                                Предварительный просмотр
                            </a>

                        @endif
                    </div>
                </div>
            </div>

            {{$this->table}}

        @endif

    @endif
</x-filament::page>
