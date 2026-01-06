<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\MySQL\InnoDBUpsertStopIncrementIncreasing;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;

class BotUserSetRecurrentScheduler
{
    public function handle() {
        $innoDBUpsertStopIncrementIncreasing = new InnoDBUpsertStopIncrementIncreasing();

        $bot_users = BotUser::with('bot:id,recurrent_time')->where('recurrent', 1)->where('recurrent_repeat', 0)->where('date_end', date('Y-m-d', time()))->get();

        foreach ($bot_users as $bot_user) {
            $innoDBUpsertStopIncrementIncreasing->handle(new BotUserRecurrentSchedule());

            $pay = Pay::select('id')->where('bot_user_id', $bot_user->id)->where('status', 1)->whereNotNull('pay_system_payment_method_id')->orderByDesc('created_at')->first();

            if ($pay) {
                BotUserRecurrentSchedule::upsert(
                    [
                        'bot_user_id' => $bot_user->id,
                        'prevous_pay_id' => $pay->id,
                        'recurrent_datetime' => date('Y-m-d', time())." ".$bot_user->bot->recurrent_time,
                        'run_status' => 0
                    ],
                    ['bot_user_id', 'recurrent_datetime'],
                    ['updated_at' => now()]
                );
            }

        }
    }
}
