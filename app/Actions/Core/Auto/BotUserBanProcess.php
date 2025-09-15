<?php
namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\TelegramBanScheduleLogs;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramSupergroup;

class BotUserBanProcess
{
    public function handle() {

        $botSupergroupsAll = new BotSupergroupsAll();

        $datetime = date('Y-m-d', time());

        $supergroups = $botSupergroupsAll->handle();

        $bans = BotUserBanSchedule::with('bot', 'bot_user')
            ->where('run_status', 0)
            ->where('ban_datetime', '<=', $datetime)
            ->get();

        foreach ($bans as $ban) {
            BotUserBanSchedule::where('id', $ban->id)->update(['run_status' => 1]);
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
        }

    }
}
