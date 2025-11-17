<x-filament-panels::page>
    @if (Auth::user()->hasPermissionTo('View:User'))

    {{$this->form}}

    @endif
</x-filament-panels::page>
