<?php

namespace App\Actions\Core\Auto;

use App\Actions\Core\Yookassa\YookassaMakeRecurrent;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {

        $yookassaMakeRecurrent = new YookassaMakeRecurrent();

        $res = BotUserRecurrentSchedule::with('prevous_pay:id,pay_system_payment_method_id,price')
            ->with('bot.yookassa_tax_system_code,yookassa_vat_code')
            ->with('bot_user:id,telegram_chat_id,first_name,last_name,email')
            ->with('paysystem')
            ->with('product')
            ->select('id', 'bot_user_recurrent_schedules.id', 'prevous_pay_id', 'bot_user_id')
            ->where('recurrent_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->where('run_status', 0)
            ->take(1)
            ->get();
        return $res;

        foreach ($res as $data) {
            BotUserRecurrentSchedule::where('id', $data->id)->update(['run_status' => 1]);

            if ($data->paysystem->alias == "yookassa") {
                $result = $yookassaMakeRecurrent->handle($data);
            }

            BotUserRecurrentSchedule::where('id', $data->id)->update(['new_pay_id' => $result['new_pay_id'], 'pay_system_responce' => $result['pay_system_responce']]);
        }

    }
}
