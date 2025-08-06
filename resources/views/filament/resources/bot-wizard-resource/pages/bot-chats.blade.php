<x-filament::page>
    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "chats", 'bot_id' => $this->bot_id])
    {{$this->table}}
</x-filament::page>
