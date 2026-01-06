
<x-filament-panels::page>

    <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>

    <form wire:submit.prevent="submit" onsubmit="pay(this); return false;">{{$this->form}}</form>
    

</x-filament-panels::page>
