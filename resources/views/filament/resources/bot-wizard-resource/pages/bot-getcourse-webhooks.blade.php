<x-filament-panels::page>

    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "getcourse-webhooks", 'bot_id' => $this->bot_id])

    {{$this->table}}

</x-filament-panels::page>
