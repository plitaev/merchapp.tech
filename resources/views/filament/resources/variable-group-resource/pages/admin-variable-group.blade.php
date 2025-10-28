<x-filament-panels::page>
    <form wire:submit.prevent="submit">{{$this->form}}</form>

    <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Переменные</h1>
        <div class="flex shrink-0 items-center gap-3">

            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                <a href="/admin/variables-systems/{{$this->id}}/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    <span class="fi-btn-label">Создать переменную</span>
                </a>
            </div>
        </div>
    </div>
    {{$this->table}}
</x-filament-panels::page>
