<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @include('filament.resources.bot-wizard-resource.pages.navigation-bot-wizard', ['category' => "products", 'bot_id' => $this->bot_id])

    <div class="fi-ac gap-3 flex flex-wrap items-center justify-end">
        <a href="/admin/bot-wizards/{{$this->bot_id}}/0/product-admin" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
            <span class="fi-btn-label">Добавить тариф</span>
        </a>
    </div>

    {{$this->table}}
</x-filament-panels::page>
