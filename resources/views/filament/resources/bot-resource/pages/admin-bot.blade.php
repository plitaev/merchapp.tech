<x-filament::page>
    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "edit", 'bot_id' => $this->record])

    {{$this->form}}

</x-filament::page>
