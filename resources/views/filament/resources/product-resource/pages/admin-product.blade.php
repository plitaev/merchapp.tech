<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:Product'))

    {{$this->form}}

    @endif
</x-filament-panels::page>
