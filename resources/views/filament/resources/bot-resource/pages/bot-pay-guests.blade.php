<x-filament-panels::page>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "pay-guests", 'bot_id' => $this->bot_id])
    
    {{$this->table}}

</x-filament-panels::page>
