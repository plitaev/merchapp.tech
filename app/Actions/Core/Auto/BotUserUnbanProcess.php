<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;

use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramUnbanScheduleErrorLogs;
use App\Models\Core\TelegramUnbanScheduleLogs;
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
            $telegram = new Api($unban->bot->telegram_token);

            if (isset($supergroups[$unban->bot->id])) {
                foreach ($supergroups[$unban->bot->id] as $supergroup) {

                    try {
                        $status = $telegram->unbanChatMember(['chat_id' => $supergroup, 'user_id' => $unban->bot_user->telegram_chat_id]);
                        TelegramUnbanScheduleLogs::create(['bot_user_id' => $unban->bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$unban->bot_user->telegram_chat_id, 'status' => $status]);
                    } catch (\Exception $exception) {
                        TelegramUnbanScheduleErrorLogs::create(['bot_user_id' => $unban->bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$unban->bot_user->telegram_chat_id, 'text' => $exception]);
                    }

                }
            }

            /*
            BotUserUnbanSchedule::where('id', $ban->id)->update(['run_status' => 1]);

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
