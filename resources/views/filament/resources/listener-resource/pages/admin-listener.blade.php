<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:Listener'))

    {{$this->form}}

    @endif
</x-filament-panels::page>