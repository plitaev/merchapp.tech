<?php
namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserUnban;

use App\Models\Core\BotUserUnbanSchedule;

class BotUserUnbanProcess
{
    public function handle() {
        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserUnban = new BotUserUnban();

        $datetime = date('Y-m-d H:i:s', time());

        $supergroups = $botSupergroupsAll->handle();

        $unbans = BotUserUnBanSchedule::with('bot', 'bot_user')
            ->where('run_status', 0)
            ->where('unban_datetime', '<=', $datetime)
            ->get();

        foreach ($unbans as $unban) {
            $telegram = new Api($unban->bot->telegram_token);
            $botUserUnban->handle($unban->bot_user, $supergroups, $telegram);
        }
    }
}
