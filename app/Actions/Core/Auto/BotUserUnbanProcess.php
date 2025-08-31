<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;

use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramBanScheduleLogs;
use App\Models\Core\TelegramSupergroup;

class BotUserUnbanProcess
{
    public function handle() {
        $botSupergroupsAll = new BotSupergroupsAll();

        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        $supergroups = $botSupergroupsAll->handle();

        $unbans = BotUserUnBanSchedule::with('bot', 'bot_user')
            ->where('run_status', 0)
            ->where('ban_date', $date)
            ->where('ban_time', '<=', $time)
            ->get();

        foreach ($unbans as $unban) {
            /*
            BotUserUnbanSchedule::where('id', $ban->id)->update(['run_status' => 1]);
            $telegram = new Api($ban->bot->telegram_token);

            if (isset($supergroups[$ban->bot->id])) {
                foreach ($supergroups[$ban->bot->id] as $supergroup) {

                    try {
                        $status = $telegram->banChatMember(['chat_id' => $supergroup, 'user_id' => $ban->bot_user->telegram_chat_id]);
                        TelegramBanScheduleLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$ban->bot_user->telegram_chat_id, 'status' => $status]);
                    } catch (\Exception $exception) {
                        TelegramBanScheduleErrorLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$ban->bot_user->telegram_chat_id, 'text' => $exception]);
                    }

                }
            }
            */
        }
    }
}
