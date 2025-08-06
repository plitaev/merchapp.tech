<x-filament::page>
    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "edit", 'bot_id' => $this->record])
    {{$this->form}}
</x-filament::page>
