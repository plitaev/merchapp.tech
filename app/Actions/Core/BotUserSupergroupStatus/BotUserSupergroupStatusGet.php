<?php

namespace App\Actions\Core\BotUserSupergroupStatus;

use Telegram\Bot\Api;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserSupergroupStatus;
use App\Models\Core\TelegramSupergroup;

class BotUserSupergroupStatusGet
{
    public function handle($bot_user) {
        $telegram = new Api($bot_user->bot->telegram_token);

        $supergroups = TelegramSupergroup::where('bot_id', $bot_user->bot_id)->get();

        foreach ($supergroups as $supergroup) {
            $member = $telegram->getChatMember(['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id]);
            BotUserSupergroupStatus::create(['bot_user_id' => $bot_user->id, 'telegram_supergroup_id' => $supergroup->id, 'status' => $member->status]);
        }
    }
}
