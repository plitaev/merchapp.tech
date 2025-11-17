<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:PayGuest'))

    <form wire:submit.prevent="submit">{{$this->form}}</form>

    @endif
</x-filament::page>
