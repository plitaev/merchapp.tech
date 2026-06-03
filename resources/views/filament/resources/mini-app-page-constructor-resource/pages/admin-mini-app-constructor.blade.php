<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))


        <style>
            .fi-ta-search-field{min-width: 300px}
        </style>

        <form wire:submit.prevent="submit">{{$this->form}}</form>

        @if ($this->record > 0)
            <form wire:submit.prevent="submit">{{$this->form_type_block}}</form>
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                <h1 class="fi-header-heading">Баннеры страницы</h1>
            </div>
            {{$this->table}}
        @endif

    @endif
</x-filament::page>
