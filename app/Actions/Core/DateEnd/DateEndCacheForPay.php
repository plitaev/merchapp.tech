<?php

namespace App\Actions\Core\DateEnd;

use App\Models\Core\BotUser;

class DateEndCacheForPay
{
    public function handle(int $id) {
        $dateEnd = new DateEnd();
        $bot_user = BotUser::select('id', 'bot_id')->find($id);
        return $dateEnd->handle($bot_user, 'd.m.Y');
    }
}
