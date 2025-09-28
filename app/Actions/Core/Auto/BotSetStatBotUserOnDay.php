<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\StatBotUserOnDay;
use App\Models\Core\BotUser;
use App\Models\Core\Bot;

class BotSetStatBotUserOnDay
{
    public function handle() {

        $datetime = date('Y-m-d H:i:s', time());
        $bots = Bot::get();

        foreach ($bots as $bot) {
            $botUsers = BotUser::where('date_end', '>=', $datetime)->where('bot_id', $bot->id)->count();

            StatBotUserOnDay::insert(
                [
                    'bot_id' => $bot->id,
                    'bot_user_count' => $botUsers,
                    'stat_date' => $datetime,
                ]);
        }

    }
}
