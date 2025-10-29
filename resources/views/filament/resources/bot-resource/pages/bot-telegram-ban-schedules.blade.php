<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "telegram-ban-schedules", 'bot_id' => $this->bot_id])

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Забаненные пользователи</h1>
        <div class="flex shrink-0 items-center gap-3">
            <x-filament::modal id="add-page-modal">
                <x-slot name="trigger">
                    <x-filament::button>Забанить пользователя</x-filament::button>
                </x-slot>

                <form wire:submit.prevent="submit">{{$this->form_ban_user}}</form>
            </x-filament::modal>
        </div>
    </div>

    {{$this->table}}
</x-filament-panels::page>
