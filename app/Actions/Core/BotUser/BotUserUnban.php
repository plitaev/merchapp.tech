<?php

namespace App\Actions\Core\BotUser;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramChatMemberLog;
use App\Models\Core\TelegramChatMemberErrorLog;
use App\Models\Core\TelegramUnbanSchedule;
use App\Models\Core\TelegramUnbanScheduleErrorLog;
use App\Models\Core\TelegramUnbanScheduleLog;

class BotUserUnban
{
    public function handle($bot_user, array $supergroups, $telegram) {

        if (isset($supergroups[$bot_user->bot_id])) {
            foreach ($supergroups[$bot_user->bot_id] as $supergroup) {

                try {
                    $status = $telegram->unbanChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id, 'only_if_banned' => true]);

                    TelegramUnbanScheduleLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id, 'status' => $status]);

                    try {
                        $member = $telegram->getChatMember(['chat_id' => $supergroup, 'user_id' => $bot_user->telegram_chat_id]);
                        TelegramChatMemberLog::create(['bot_user_id' => $bot_user->id, 'user_id' => $bot_user->telegram_chat_id, 'chat_id' => $supergroup, 'status' => $member->status, 'text' => $member]);

                        if ($member->status != 'banned') {
                            BotUserUnbanSchedule::where('bot_user_id', $bot_user->id)->update(['run_status' => 1]);
                            BotUser::where('id', $bot_user->id)->update(['ban' => 0, 'unban' => 1]);
                        } else {
                            BotUserUnbanSchedule::where('bot_user_id', $bot_user->id)->update(['run_status' => 0]);
                        }

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
