<?php

namespace App\Actions\Core\Telegram;

use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramBanScheduleLogs;

class TelegramBanRun
{
    public function handle($supergroup, $ban) {

        $telegramQuery = new TelegramQuery();

        try {
            $status = $telegramQuery->handle($ban->bot,'banChatMember', ['chat_id' => $supergroup->telegram_id, 'user_id' => $ban->bot_user->telegram_user_id]);
            $status = ($status['ok'] == true?1:0);

            TelegramBanScheduleLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$ban->bot_user->telegram_chat_id, 'status' => $status]);

            BotUser::where('id', $ban->bot_user_id)->update(['ban' => 1, 'ban_time' => now(), 'unban' => 0, 'date_start' => NULL, 'access_bonus' => NULL, 'recurrent' => 0]);
            BotUserUnbanSchedule::where('bot_user_id', $ban->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);

        } catch (\Exception $exception) {
            TelegramBanScheduleErrorLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$ban->bot_user->telegram_chat_id, 'text' => $exception]);
        }
    }
}
