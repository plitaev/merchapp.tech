<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\TelegramUnbanScheduleErrorLogs;
use App\Models\Core\TelegramUnbanScheduleLogs;

class BotUserUnban
{
    public function handle($bot_user, array $supergroups, $telegram) {

        if (isset($supergroups[$bot_user->bot_id])) {
            foreach ($supergroups[$bot_user->bot_id] as $supergroup) {

                try {
                    $status = $telegram->unbanChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id]);
                    TelegramUnbanScheduleLogs::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id, 'status' => $status]);

                    try {
                        $telegram->getChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id]);
                    } catch (\Exception $exception) {
                        TelegramUnbanScheduleErrorLogs::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                    }

                } catch (\Exception $exception) {
                    TelegramUnbanScheduleErrorLogs::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                }

            }
        }

    }
}
