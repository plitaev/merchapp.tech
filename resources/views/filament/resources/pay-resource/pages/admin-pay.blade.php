<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:Pay'))

    {{$this->form}}

    @endif
</x-filament-panels::page>
