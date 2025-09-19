<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\Yookassa\YookassaMakeRecurrent;

use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {

        $yookassaMakeRecurrent = new YookassaMakeRecurrent();

        $res = BotUserRecurrentSchedule::with('prevous_pay:id,pay_system_payment_method_id,price')
            ->with('bot')
            ->with('bot_user:id,telegram_chat_id,first_name,last_name,email')
            ->with('paysystem')
            ->with('product')
            ->select('bot_user_recurrent_schedules.id', 'prevous_pay_id', 'bot_user_id')
            ->where('recurrent_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->where('run_status', 0)
            ->get();

        foreach ($res as $data) {

            if ($data->paysystem->alias == "yookassa") {
                return $yookassaMakeRecurrent->handle($data);
            }

        }

    }
}
