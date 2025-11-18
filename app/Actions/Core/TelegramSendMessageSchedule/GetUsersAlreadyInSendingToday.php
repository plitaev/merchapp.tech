<?php

namespace App\Actions\Core\TelegramSendMessageSchedule;

use App\Models\Core\TelegramSendMessageSchedule;

class GetUsersAlreadyInSendingToday
{
    public function handle($data) {
        return TelegramSendMessageSchedule::whereHas('sending', function ($query) use ($data) {
            $query->where('bot_message_id', $data->id);
            $query->where('send_datetime', '>=', date('Y-m-d', time())." 00:00:00");
            $query->where('send_datetime', '<=', date('Y-m-d', time())." 23:59:59");
        })
            ->select('bot_user_id')
            ->groupBy('bot_user_id')
            ->pluck('bot_user_id')
            ->toArray();
    }
}
