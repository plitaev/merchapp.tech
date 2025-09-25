<?php
namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;
use Carbon\Carbon;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;
use App\Actions\Core\Telegram\TelegramBanRun;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;

class BotUserBanProcess
{
    public function handle() {

        $botSupergroupsAll = new BotSupergroupsAll();
        $botUserSetBanSchedulerCreate = new BotUserSetBanSchedulerCreate();
        $telegramBanRun = new TelegramBanRun();

        $datetime = date('Y-m-d H:i:s', time());

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

                    //== бан в момент окончания
                    if ($supergroup->supergroup_delete_parameter_id == 1) {
                        $telegramBanRun->handle($telegram, $supergroup, $ban);
                    }

                    //== бан после окончания через ххх дней
                    if ($supergroup->supergroup_delete_parameter_id == 3) {
                        $next_ban_date = Carbon::parse($ban->bot_user->date_end)->addDays(5)->format('Y-m-d');
                        $next_ban_date = $next_ban_date." 23:30:00";

                        if ($next_ban_date <= date('Y-m-d H:i:s', time())) {
                            $telegramBanRun->handle($telegram, $supergroup, $ban);
                        } else {
                            $bot_users = BotUser::find($ban->bot_user->id);
                            $botUserSetBanSchedulerCreate->handle($bot_users, $next_ban_date);
                        }
                    }

                }
            }

            return 'ok';
        }


    }
}
