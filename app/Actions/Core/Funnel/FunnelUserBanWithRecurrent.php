<?php
namespace App\Actions\Core\Funnel;
use Carbon\Carbon;

use App\Actions\Core\Funnel\FunnelGetDateTime;
use App\Actions\Core\TelegramSendMessageSchedule\GetUsersAlreadyInSendingToday;

use App\Models\Core\BotUser;
use App\Models\Core\Sending;
use App\Models\Core\TelegramSendMessageSchedule;

class FunnelUserBanWithRecurrent
{
    public function handle($data) {

        $funnelGetDateTime = new FunnelGetDateTime();
        $getUsersAlreadyInSendingToday = new GetUsersAlreadyInSendingToday();

        return 'ok';

        if ($data->funnel_condition->alias == "user_with_recurrent_ban") {
            $funnel_date_time = $funnelGetDateTime->handle($data, );

            $date = $funnel_date_time['date'];
            $time = $funnel_date_time['time'];
            $datetime = $funnel_date_time['datetime'];

            if ($time >= $data->bot->recurrent_time) {
                $schedules = $getUsersAlreadyInSendingToday->handle($data);
                $bot_users = BotUser::select('id')->where('bot_id', $data->bot->id)->where('date_end', $date)->where('recurrent', 1)->whereNotIn('id', $schedules)->get();

                if (count($bot_users) > 0) {

                    $sending = Sending::create([
                        'bot_message_id' => $data->id,
                        'name' => 'Авторассылка напоминаний с рекуррентом',
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
