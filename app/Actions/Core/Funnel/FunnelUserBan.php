<?php
namespace App\Actions\Core\Funnel;

use Carbon\Carbon;

use App\Actions\Core\Funnel\FunnelGetDateTime;

use App\Models\Core\BotUser;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelUserBan
{
    public function handle($data) {

        if ($data->funnel_condition->alias == "user_ban") {

            $funnelGetDateTimeNow = new FunnelGetDateTime();

            $funnel_date_time = $funnelGetDateTimeNow->handle($data);

            $date = $funnel_date_time['date'];
            $time = $funnel_date_time['time'];
            $datetime = $funnel_date_time['datetime'];

            if (date('H:i:s') >= $time) {

                $schedules = TelegramSendMessageSchedule::whereHas('sending', function ($query) use ($data) {
                    $query->where('bot_message_id', $data->id);
                    $query->where('send_datetime', '>=', date('Y-m-d', time())." 00:00:00");
                    $query->where('send_datetime', '<=', date('Y-m-d', time())." 23:59:59");
                })
                    ->select('bot_user_id')
                    ->groupBy('bot_user_id')
                    ->pluck('bot_user_id')
                    ->toArray();

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
