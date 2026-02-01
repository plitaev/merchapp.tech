<?php
use App\Models\Core\Bot;
$bot = Bot::select('name')->find($this->bot_id);

if($this->bot_id != 0) {
    $this->bot_name = $bot->name;
}else{
    $this->bot_name = '';
}
?>
<x-filament-panels::page>

    <style>
        .fi-ta-search-field{min-width: 300px}
        .fi-breadcrumbs:not(#merchapp-top-nav){display: none} .fi-ta-search-field{min-width: 300px}
    </style>

    <style>.fi-breadcrumbs:not(#merchapp-top-nav){display: none} .fi-ta-search-field{min-width: 300px}</style>

    <div class="fi-header fi-header-has-breadcrumbs">
        <div>
            <nav class="fi-breadcrumbs" id="merchapp-top-nav">
            <ol class="fi-breadcrumbs-list">
                <li class="fi-breadcrumbs-item">
                    <a href="/admin/bots" class="fi-breadcrumbs-item-label">
                        Боты
                    </a>
                </li>
                <li class="fi-breadcrumbs-item">
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-ltr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-rtl" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                    </svg>

                    <a href="/admin/bots/{{$this->bot_id}}/edit" class="fi-breadcrumbs-item-label">
                        {{$this->bot_name}}
                    </a>
                </li>
                <li class="fi-breadcrumbs-item">
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-ltr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-rtl" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                    </svg>

                    <a href="/admin/bots/{{$this->bot_id}}/messages" class="fi-breadcrumbs-item-label">Сообщения</a>
                </li>
                <li class="fi-breadcrumbs-item">
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-ltr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="fi-icon fi-size-md fi-breadcrumbs-item-separator fi-rtl" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                    </svg>

                    <a href="/admin/bots/{{$this->bot_id}}/{{$this->id}}/message-admin" class="fi-breadcrumbs-item-label">
                        @if($this->id == 0 ) Новое сообщение
                        @else Редактировать сообщение
                        @endif
                    </a>
                </li>
            </ol>
        </nav>

    <h1 class="fi-header-heading">
        @if($this->id == 0 ) Новое сообщение
        @else Редактировать сообщение
        @endif</h1>
        </div>
    </div>
    {{$this->form}}

    @if ($this->id > 0)
        <div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Кнопки</h1>
            <div class="flex shrink-0 items-center gap-3">
                @if (auth()->user()->can('Update:BotMessageButtonCallback'))
                <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                    <a href="/admin/bots/{{$this->bot_id}}/0/button-admin" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                        <span class="fi-btn-label">Создать кнопку</span>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{$this->table}}

    @endif
</x-filament-panels::page>
