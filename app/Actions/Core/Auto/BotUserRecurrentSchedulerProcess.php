<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentSchedulerProcess
{
    public function handle() {
        $res = BotUserRecurrentSchedule::with('prevous_pay')->where('run_status', 0)->get();
        return $res;
    }
}
