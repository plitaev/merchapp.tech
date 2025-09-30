<x-filament-panels::page>
    {{$this->form}}
    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex shrink-0 items-center gap-3">
            <x-filament::modal id="add-page-modal">
                <x-slot name="trigger">
                    <x-filament::button>Добавить сообщение</x-filament::button>
                </x-slot>

                <form wire:submit.prevent="submit">{{$this->form_user_link_message}}</form>
            </x-filament::modal>
        </div>
    </div>
</x-filament-panels::page>
