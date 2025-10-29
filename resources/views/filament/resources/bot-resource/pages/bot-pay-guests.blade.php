<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "pay-guests", 'bot_id' => $this->bot_id])

    {{$this->table}}

</x-filament-panels::page>
