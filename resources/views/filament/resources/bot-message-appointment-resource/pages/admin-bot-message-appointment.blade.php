<x-filament-panels::page>
    @if (Auth::user()->hasPermissionTo('Update:BotMessageAppointment'))

    {{$this->form}}
        
    @endif
</x-filament-panels::page>
