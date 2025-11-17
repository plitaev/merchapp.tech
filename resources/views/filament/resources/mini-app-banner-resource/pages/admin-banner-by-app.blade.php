<x-filament::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppBanner'))

    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">{{$this->mini_app_name}}</h1>
    {{$this->table}}

    @endif
</x-filament::page>
