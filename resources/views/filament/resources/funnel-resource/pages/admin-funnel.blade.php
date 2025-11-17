<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:Funnel'))

    {{$this->form}}

    @endif
    
</x-filament-panels::page>
