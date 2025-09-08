<?php

namespace App\Actions\Core\Auto;

class TelegramSendMessageScheduleProcess
{
    public function handle() {
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());

        return $time;

    }
}
