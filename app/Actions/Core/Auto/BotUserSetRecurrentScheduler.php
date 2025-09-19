<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\MySQL\InnoDBUpsertStopIncrementIncreasing;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;

class BotUserSetRecurrentScheduler
{
    public function handle() {
        $innoDBUpsertStopIncrementIncreasing = new InnoDBUpsertStopIncrementIncreasing();

        $bot_users = BotUser::where('recurrent', 1)->where('date_end', date('Y-m-d', time()))->get();
        foreach ($bot_users as $bot_user) {
            $innoDBUpsertStopIncrementIncreasing->handle(new BotUserRecurrentSchedule());

            BotUserRecurrentSchedule::upsert(
                [
                    'bot_user_id' => $new->bot_message_id,
                    'prevous_pay_id' => $new->telegram_message_id,
                    'recurrent_datetime' => $new->chat_id,
                    'run_status' => $delete_dt,
                    'status' => 0
                ],
                ['telegram_message_id', 'chat_id'],
                ['updated_at' => now()]
            );
        }
    }
}
