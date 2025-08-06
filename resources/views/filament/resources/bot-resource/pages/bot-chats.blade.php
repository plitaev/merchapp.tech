<x-filament::page>
    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "chats", 'bot_id' => $this->bot_id])
    {{$this->table}}
</x-filament::page>
