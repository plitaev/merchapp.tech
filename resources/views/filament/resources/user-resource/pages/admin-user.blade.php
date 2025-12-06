<x-filament-panels::page>
    <style>
        .fi-ta-search-field{min-width: 300px}
    </style>

    @if (auth()->user()->hasPermissionTo('View:User'))

        <form wire:submit.prevent="submit">{{$this->form}}</form>


        {{$this->table}}

        <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="/admin/users/{{$this->id}}/model-has-permission" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    <span class="fi-btn-label">Назначить индивидуальные права</span>
                </a>



    @endif
</x-filament-panels::page>
