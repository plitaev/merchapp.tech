@php
    $categories = [];
    if (Auth::user()->hasPermissionTo('View:Bot')) $categories['edit'] = 'Основные';
    if (Auth::user()->hasPermissionTo('View:PaySystem')) $categories['pay-system-admin'] = 'Платежные системы';
    if (Auth::user()->hasPermissionTo('View:BotMessage')) $categories['messages'] = 'Сообщения';
    if (Auth::user()->hasPermissionTo('View:BotUser')) $categories['chats'] = 'Подписчики';
    if (Auth::user()->hasPermissionTo('View:Pay')) $categories['pays'] = 'Платежи';
    if (Auth::user()->hasPermissionTo('View:PayGuest')) $categories['pay-guests'] = 'В ожидании';
    if (Auth::user()->hasPermissionTo('View:Pay')) $categories['recurrents'] = 'График автосписаний';
    if (Auth::user()->hasPermissionTo('View:TelegramSupergroup')) $categories['supergroups'] = 'Супергруппы';
    if (Auth::user()->hasPermissionTo('View:Product')) $categories['products'] = 'Тарифы';
    if (Auth::user()->hasPermissionTo('View:Bot')) $categories['getcourse-settings'] = 'Настройки GetCourse';
    if (Auth::user()->hasPermissionTo('View:Bot')) $categories['getcourse-webhooks'] = 'Вебхуки GetCourse';
    if (Auth::user()->hasPermissionTo('View:BotUserBanSchedule')) $categories['telegram-ban-schedules'] = 'Баны';
    if (Auth::user()->hasPermissionTo('View:TelegramUnbanSchedule')) $categories['telegram-unban-schedules'] = 'Разбаны';
    if (Auth::user()->hasPermissionTo('View:Funnel')) $categories['funnels'] = 'Воронки';
    if (Auth::user()->hasPermissionTo('View:Sending')) $categories['sendings'] = 'Рассылки';
    if (Auth::user()->hasPermissionTo('View:BotBranch')) $categories['branches'] = 'Акции';


@endphp

<div class="fi-sc-tabs fi-contained">
    <nav class="fi-tabs fi-contained " aria-label="Tabs" role="tablist">
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
</div>
