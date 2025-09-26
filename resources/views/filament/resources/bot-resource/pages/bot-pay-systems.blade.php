@include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "pay-systems", 'bot_id' => $this->bot_id])

<x-filament-panels::page>
    {{$this->table}}
</x-filament-panels::page>
