<?php

namespace App\Actions\Core\BotUserSupergroupStatus;

use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\BotUserSupergroupStatus;
use App\Models\Core\TelegramSupergroup;

class BotUserSupergroupStatusGet
{
    public function handle($bot_user) {
        $telegramQuery = new TelegramQuery();

        $supergroups = TelegramSupergroup::where('bot_id', $bot_user->bot_id)->get();

        foreach ($supergroups as $supergroup) {
            $member = $telegramQuery->handle($bot_user->bot, 'getChatMember', ['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id]);
            $member = json_decode($member, true);
            BotUserSupergroupStatus::create(['bot_user_id' => $bot_user->id, 'telegram_supergroup_id' => $supergroup->id, 'status' => $member['result']['status']]);
        }
    }
}
