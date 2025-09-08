<?php
namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserUnban;

use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\TelegramUnbanScheduleErrorLogs;
use App\Models\Core\TelegramUnbanScheduleLogs;

class BotUserUnbanProcess
{
    public function handle() {
        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserUnban = new BotUserUnban();

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
            $botUserUnban->handle($unban->bot_user, $supergroups, $telegram);
        }
    }
}
