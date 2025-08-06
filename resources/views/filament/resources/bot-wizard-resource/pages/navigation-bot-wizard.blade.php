@php
    $categories = ['edit' => 'Основные', 'messages' => 'Сообщения', 'chats' => 'Подписчики', 'pays' => 'Платежи', 'pay-guests' => 'В ожидании', 'supergroups' => 'Супергруппы', 'products' => 'Тарифы', 'getcourse-settings' => 'Настройки GetCourse','getcourse-webhooks' => 'Вебхуки GetCourse','telegram-ban-schedules' => 'Баны','telegram-unban-schedules' => 'Разбаны','funnels' => 'Воронки'];
@endphp

<nav class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" role="tablist">

    @foreach ($categories as $key => $value)
        @if ($key == $category)
            <a href="javascript:void(0);" class="fi-tabs-item group flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5" aria-selected="aria-selected" role="tab">
                <span class="fi-tabs-item-label transition duration-75 text-primary-600 dark:text-primary-400">{{$value}}</span>
            </a>
        @else
            <a href="/admin/bot-wizards/{{$bot_id}}/{{$key}}" class="fi-tabs-item group flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5" aria-selected="aria-selected" role="tab">
                <span class="fi-tabs-item-label transition duration-75 text-gray-500 group-hover:text-gray-700 group-focus-visible:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200 dark:group-focus-visible:text-gray-200">{{$value}}</span>
            </a>
        @endif
    @endforeach
</nav>

