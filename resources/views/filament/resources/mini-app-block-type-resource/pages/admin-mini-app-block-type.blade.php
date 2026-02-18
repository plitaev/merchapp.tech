<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:View:MiniAppBlockType'))

    {{$this->form}}

    @endif

</x-filament-panels::page>
