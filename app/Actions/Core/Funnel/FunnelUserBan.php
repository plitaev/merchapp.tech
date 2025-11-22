<?php
namespace App\Actions\Core\Funnel;
use Carbon\Carbon;

use App\Actions\Core\Funnel\FunnelGetDateTimeWithFixedTime;
use App\Actions\Core\TelegramSendMessageSchedule\GetUsersAlreadyInSendingToday;

use App\Models\Core\BotUser;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelUserBan
{
    public function handle($data) {

        $funnelGetDateTimeWithFixedTime = new FunnelGetDateTimeWithFixedTime();
        $getUsersAlreadyInSendingToday = new GetUsersAlreadyInSendingToday();

        if ($data->funnel_condition->alias == "user_ban") {
            $funnel_date_time = $funnelGetDateTimeWithFixedTime->handle($data, $data->bot->ban_time);

            $date = $funnel_date_time['date'];
            $time = $funnel_date_time['time'];
            $datetime = $funnel_date_time['datetime'];

            if (date('H:i:s') >= $time) {
                $schedules = $getUsersAlreadyInSendingToday->handle($data);
                $bot_users = BotUser::select('id')->where('bot_id', $data->bot->id)->where('date_end', $date)->whereNotIn('id', $schedules)->get();

                if (count($bot_users) > 0) {

                    $sending = Sending::create([
                        'bot_message_id' => $data->id,
                        'name' => 'Авторассылка по забаненным',
                        'user_ban' => 1,
                        'send_datetime' => date('Y-m-d', time())." ".$time
                    ]);

                    foreach ($bot_users as $bot_user) {
                        TelegramSendMessageSchedule::create([
                            'sending_id' => $sending->id,
                            'bot_user_id' => $bot_user->id
                        ]);
                    }
                }

            }
        }

    }
}
