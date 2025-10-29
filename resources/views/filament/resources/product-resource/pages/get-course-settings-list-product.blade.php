<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex justify-between shrink-0 items-center gap-3 w-full">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Вебхуки для GetCourse</h1>
        </div>
    </div>
    {{$this->table}}

</x-filament-panels::page>
