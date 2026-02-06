<x-filament-panels::page>
    @if (Auth::user()->hasPermissionTo('View:MiniApp'))

        {{$this->form}}

    @endif
</x-filament-panels::page>
