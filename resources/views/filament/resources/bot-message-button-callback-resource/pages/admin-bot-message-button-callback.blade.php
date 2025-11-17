<x-filament-panels::page>
    @if (Auth::user()->hasPermissionTo('View:BotMessageButtonCallback'))

    {{$this->form}}
        
    @endif
</x-filament-panels::page>
