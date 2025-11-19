<x-filament::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    <form wire:submit.prevent="submit">{{$this->form}}</form>
    
</x-filament::page>
