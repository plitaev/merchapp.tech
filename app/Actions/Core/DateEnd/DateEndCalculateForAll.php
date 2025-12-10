<?php

namespace App\Actions\Core\DateEnd;

use App\Models\Core\BotUser;

use App\Actions\Core\DateEnd\DateEnd;

class DateEndCalculateForAll
{
    public function handle() {
        $dateEnd = new DateEnd();

        $bot_users = BotUser::where('run_status', 0)->skip(0)->take(500)->get();
        foreach ($bot_users as $bot_user) {
            $dateEnd->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }

    }
}
