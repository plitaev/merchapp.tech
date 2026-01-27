<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:FunnelCondition'))

        {{$this->form}}

    @endif

</x-filament-panels::page>
