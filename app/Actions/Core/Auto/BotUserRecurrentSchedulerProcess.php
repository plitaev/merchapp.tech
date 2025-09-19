<?php

namespace App\Actions\Core\Auto;

use YooKassa\Client;

use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {
        $res = BotUserRecurrentSchedule::with('prevous_pay')->with('bot')->where('recurrent_datetime', '<=', date('Y-m-d H:i:s', time()))->where('run_status', 0)->get();
        return $res;
        foreach ($res as $data) {

        }
    }
}
