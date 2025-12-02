<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "recurrents", 'bot_id' => $this->bot_id])

    <div class="fi-header fi-header-has-breadcrumbs">
        <div>
            <h1 class="fi-header-heading"></h1>
        </div>
    </div>

    {{$this->table}}
</x-filament-panels::page>
