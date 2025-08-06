<x-filament-panels::page>

    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "pay-guests", 'bot_id' => $this->bot_id])
    
    {{$this->table}}

</x-filament-panels::page>
