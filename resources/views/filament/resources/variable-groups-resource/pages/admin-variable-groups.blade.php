<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:VariableGroup'))

    {{$this->form}}

    @endif
</x-filament-panels::page>
