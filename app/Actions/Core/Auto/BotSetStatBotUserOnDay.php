<?php

namespace App\Actions\Core\Auto;

use Carbon\Carbon;

use App\Models\Core\StatBotUserOnDay;
use App\Models\Core\BotUser;
use App\Models\Core\Bot;

class BotSetStatBotUserOnDay
{
    public function handle() {

        $date = date('Y-m-d', time());
        $bots = Bot::where('id', 1)->get();

        foreach ($bots as $bot) {
            $bot_users = BotUser::where('date_end', '>=', $date)->where('bot_id', $bot->id)->count();

            StatBotUserOnDay::create(
                [
                    'bot_id' => $bot->id,
                    'bot_user_count' => $bot_users,
                    'stat_date' => Carbon::parse($date)->subDays(1)->format('Y-m-d')
                ]);
        }

    }
}
