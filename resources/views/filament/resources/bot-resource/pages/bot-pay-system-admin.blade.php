<x-filament-panels::page>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "pay-system-admin", 'bot_id' => $this->record])
    {{$this->form}}

</x-filament-panels::page>
