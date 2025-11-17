<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:MiniApp'))

    {{$this->table}}

    @endif
</x-filament-panels::page>