<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:MiniApp'))
        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                @if (auth()->user()->can('Create:MiniApp'))

                    <a href="/admin/mini-apps/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                        Создать
                    </a>

                @endif
            </div>
        </div>
    {{$this->table}}

    @endif
</x-filament-panels::page>