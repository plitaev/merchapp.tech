<x-filament-panels::page>
    @if (Auth::user()->hasPermissionTo('View:Role'))

    {{$this->form}}

    @endif
</x-filament-panels::page>
