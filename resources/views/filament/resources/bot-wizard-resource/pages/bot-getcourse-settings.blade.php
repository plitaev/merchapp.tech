<x-filament-panels::page>

    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "getcourse-settings", 'bot_id' => $this->bot_id])

    {{$this->table}}

</x-filament-panels::page>