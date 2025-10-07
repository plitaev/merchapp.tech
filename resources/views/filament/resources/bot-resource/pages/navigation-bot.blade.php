@php
    $categories = ['edit' => 'Основные', 'pay-system-admin' => 'Платежные системы', 'messages' => 'Сообщения', 'chats' => 'Подписчики', 'pays' => 'Платежи', 'pay-guests' => 'В ожидании', 'supergroups' => 'Супергруппы', 'products' => 'Тарифы', 'getcourse-settings' => 'Настройки GetCourse','getcourse-webhooks' => 'Вебхуки GetCourse','telegram-ban-schedules' => 'Баны','telegram-unban-schedules' => 'Разбаны','funnels' => 'Воронки','sendings' => 'Рассылки'];
@endphp

<nav class="fi-tabs fi-contained" aria-label="Tabs" role="tablist">

    @foreach ($categories as $key => $value)
        @if ($key == $category)
            <a href="javascript:void(0);" class="fi-tabs-item fi-active" role="tab">
                <span class="fi-tabs-item-label">{{$value}}</span>
            </a>
        @else
            <a href="/admin/bots/{{$bot_id}}/{{$key}}" class="fi-tabs-item" role="tab">
                <span class="fi-tabs-item-label">{{$value}}</span>
            </a>
        @endif
    @endforeach
</nav>

