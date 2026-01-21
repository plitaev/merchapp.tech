<?php

namespace App\Actions\Core\Telegram;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramBanScheduleLogs;

class TelegramBanRun
{
    public function handle($telegram, $supergroup, $ban) {
        try {
            $status = $telegram->banChatMember(['chat_id' => $supergroup->telegram_id, 'user_id' => $ban->bot_user->telegram_chat_id]);
            TelegramBanScheduleLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$ban->bot_user->telegram_chat_id, 'status' => $status]);

            BotUser::where('id', $ban->bot_user_id)->update(['ban' => 1, 'ban_time' => now(), 'unban' => 0, 'date_start' => NULL]);
            BotUserUnbanSchedule::where('bot_user_id', $ban->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);

        } catch (\Exception $exception) {
            TelegramBanScheduleErrorLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$ban->bot_user->telegram_chat_id, 'text' => $exception]);
        }
    }
}
