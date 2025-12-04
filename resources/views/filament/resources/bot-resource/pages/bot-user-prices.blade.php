<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>
    {{$this->form}}
{{--    <div class="fi-header fi-header-has-breadcrumbs">--}}
{{--        <div>--}}
{{--            <h1 class="fi-header-heading"></h1>--}}
{{--        </div>--}}
{{--        @if (auth()->user()->can('Create:TelegramSupergroup'))--}}
{{--            <div class="fi-header-actions-ctn">--}}
{{--                <div class="fi-ac fi-align-start">--}}
{{--                    <a href="/admin/bots/{{$this->bot_id}}/{{$this->bot_user_id}}/0/bot-users-prices" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">--}}
{{--                        Добавить индивидуальную цену--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}


    {{$this->table}}
    <div class="fi-header-actions-ctn">
        <div class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md fi-ac-btn-action">
            <a href=/admin/bots/{{$this->bot_id}}/chats>
                Вернуться назад
            </a>
        </div>
    </div>
</x-filament-panels::page>
