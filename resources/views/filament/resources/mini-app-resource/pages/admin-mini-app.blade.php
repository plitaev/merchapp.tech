<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniApp'))

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    <form wire:submit.prevent="submit">{{$this->form}}</form>

    @endif
</x-filament::page>
