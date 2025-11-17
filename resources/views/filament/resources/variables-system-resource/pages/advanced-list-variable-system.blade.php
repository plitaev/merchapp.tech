<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:VariableSystem'))

    <form wire:submit.prevent="submit">{{$this->form}}</form>
        
    @endif
</x-filament-panels::page>
