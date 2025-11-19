<x-filament-panels::page>

    <style>.fi-breadcrumbs:not(#merchapp-top-nav){display: none}
        .fi-ta-search-field{min-width: 300px}
    </style>

    <div class="fi-header fi-header-has-breadcrumbs">
        <div>
            <nav class="fi-breadcrumbs"  id="merchapp-top-nav">
                <ol class="fi-breadcrumbs-list">
                    <li class="fi-breadcrumbs-item">
                        <a href="/admin/bot-message-button-callbacks" class="fi-breadcrumbs-item-label">
                            Обработчики кнопок
                        </a>
                    </li>
                    <li class="fi-breadcrumbs-item">
                        <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-ltr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                        </svg>
                        <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-rtl" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                        </svg>

                        <span class="fi-breadcrumbs-item-label">Список</span>
                    </li>
                </ol>
            </nav>

            <h1 class="fi-header-heading">Обработчики кнопок</h1>
        </div>

        @if (auth()->user()->can('Update:BotMessageAppointment'))

        <div class="fi-header-actions-ctn">
            <div class="fi-ac fi-align-start">
                <a href="/admin/bot-message-button-callbacks/0/admin" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md  fi-ac-btn-action">
                    Создать
                </a>
            </div>
        </div>
        @endif
    </div>

    {{$this->table}}

</x-filament-panels::page>
