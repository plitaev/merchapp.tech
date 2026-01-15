<?php

namespace App\Actions\Core\Robokassa;

use Carbon\Carbon;

use App\Actions\Core\BotUser\BotUserRepeatRecurrent;

use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;

class RobokassaRecurrentFail
{
    public function handle() {
        $botUserRepeatRecurrent = new BotUserRepeatRecurrent();

        $time = Carbon::now()->subHours(1);

        $pays = Pay::select('id', 'bot_user_id')
            ->where('created_at', '>=', $time)
            ->where('pay_system_id', 3)
            ->where('status', 0)
            ->where('recurrent', 1)
            ->where('recurrent_status', 0)
            ->where('run_status', 0)
            ->get();

        foreach ($pays as $pay) {

            Pay::where('bot_user_id', $pay->id)->where('pay_system_id', 3)->update(['run_status' => 1]);

            $next_recurrent = Pay::where('bot_user_id', $pay->bot_user_id)
                ->where('id', '>', $pay->id)
                ->where('status', 1)
                ->first();

            if (!$next_recurrent) {

                $recurrent_schedule = BotUserRecurrentSchedule::select()->where('bot_user_id', $pay->bot_user_id)->orderByDesc('created_at')->first();

                if ($recurrent_schedule) {
                    $data = [];
                    $data['bot_user_id'] = $pay->bot_user_id;
                    $data['prevous_pay_id'] = $recurrent_schedule->prevous_pay_id;

                    $botUserRepeatRecurrent->handle($data);
                }

            }

        }

    }
}
