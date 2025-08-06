<x-filament::page>
    <form wire:submit.prevent="submit">{{$this->form}}</form>

    @if ($this->banner_id>0)
        <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Страницы, где отображается баннер</h1>
            <div class="flex shrink-0 items-center gap-3">
                <x-filament::modal id="add-page-modal">
                    <x-slot name="trigger">
                        <x-filament::button>Добавить страницу</x-filament::button>
                    </x-slot>

                    <form wire:submit.prevent="submit">{{$this->form_banner_link_page}}</form>
                </x-filament::modal>
            </div>
        </div>
        {{$this->table}}
    @endif

</x-filament::page>
