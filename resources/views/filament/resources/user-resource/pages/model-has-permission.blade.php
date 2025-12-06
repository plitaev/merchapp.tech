<x-filament::page>
    <form wire:submit.prevent="submit">{{$this->form}}</form>
    <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
        <h1 class="fi-header-heading">Права</h1>
    </div>
    {{$this->table}}
</x-filament::page>
