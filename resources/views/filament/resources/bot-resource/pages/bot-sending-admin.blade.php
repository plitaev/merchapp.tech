<x-filament-panels::page>
    {{$this->form}}

    @if ($this->id > 0)
        <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Получатели рассылки</h1>
            <div class="flex shrink-0 items-center gap-3">
                <x-filament::modal id="add-page-modal">
                    <x-slot name="trigger">
                        <x-filament::button>Добавить одного пользователя</x-filament::button>
                    </x-slot>

                    <form wire:submit.prevent="submit">{{$this->form_bot_user}}</form>
                </x-filament::modal>
            </div>

            <div class="fi-header-actions-ctn">
                <div class="fi-ac fi-align-start">
                    <a href="/admin/bots/{{$this->bot_id}}/{{$this->id}}/sending-some" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                        Добавить несколько пользователей
                    </a>
                </div>
            </div>

            <div class="fi-header-actions-ctn">
                <div class="fi-ac fi-align-start">
                    <a href="/admin/bots/{{$this->bot_id}}/{{$this->id}}/sending-some" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                        Добавить по покупкам
                    </a>
                </div>
            </div>
        </div>

        {{$this->table}}
    @endif

</x-filament-panels::page>
