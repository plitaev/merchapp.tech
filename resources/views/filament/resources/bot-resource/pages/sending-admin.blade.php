<x-filament-panels::page>
    {{$this->form}}

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
    </div>

    {{$this->table}}
</x-filament-panels::page>
