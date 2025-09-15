<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\TelegramChatMemberLog;
use App\Models\Core\TelegramChatMemberErrorLog;
use App\Models\Core\TelegramUnbanScheduleErrorLog;
use App\Models\Core\TelegramUnbanScheduleLog;

class BotUserUnban
{
    public function handle($bot_user, array $supergroups, $telegram) {

        if (isset($supergroups[$bot_user->bot_id])) {
            foreach ($supergroups[$bot_user->bot_id] as $supergroup) {

                try {
                    $status = $telegram->unbanChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id]);
                    TelegramUnbanScheduleLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id, 'status' => $status]);

                    try {
                        $telegram->getChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id]);
                        TelegramChatMemberLog::create(['bot_user_id' => $bot_user->id, 'user_id' => $bot_user->telegram_chat_id, 'chat_id' => $supergroup, 'status' => $status->status, 'text' => $status]);
                    } catch (\Exception $exception) {
                        TelegramChatMemberErrorLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                    }

                } catch (\Exception $exception) {
                    TelegramUnbanScheduleErrorLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                }

            }
        }

    }
}
