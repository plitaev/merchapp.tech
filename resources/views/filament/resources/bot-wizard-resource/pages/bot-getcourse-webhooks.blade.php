<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "getcourse-webhooks", 'bot_id' => $this->bot_id])

    {{$this->table}}

</x-filament-panels::page>
