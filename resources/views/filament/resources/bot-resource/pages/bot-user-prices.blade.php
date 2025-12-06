<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    {{$this->table}}

    @if (auth()->user()->can('Create:Pay'))
        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                <a href="/admin/bots//0/pay-admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    Добавить платеж
                </a>
            </div>
        </div>
    @endif

    <div class="fi-header-actions-ctn">
        <div class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md fi-ac-btn-action">
            <a href=/admin/bots/{{$this->bot_id}}/chats>
                Вернуться назад
            </a>
        </div>
    </div>
</x-filament-panels::page>
