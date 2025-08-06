<x-filament-panels::page>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "getcourse-webhooks", 'bot_id' => $this->bot_id])

    {{$this->table}}

</x-filament-panels::page>
