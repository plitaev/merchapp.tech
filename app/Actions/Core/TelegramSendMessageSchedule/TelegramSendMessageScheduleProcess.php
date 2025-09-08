<?php

namespace App\Actions\Core\TelegramSendMessageSchedule;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        return $time;

    }
}
