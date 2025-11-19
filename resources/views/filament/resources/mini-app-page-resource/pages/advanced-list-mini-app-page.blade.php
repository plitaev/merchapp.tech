<x-filament-panels::page>

    @if (Auth::user()->hasPermissionTo('View:MiniAppPage'))

        <div class="fi-header fi-header-has-breadcrumbs">
            <div>
                <h1 class="fi-header-heading">Баннеры</h1>
            </div>

        </div>

        {{$this->table}}

    @endif
</x-filament-panels::page>
