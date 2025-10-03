<?php

namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Models\Core\BotMessage;
use App\Models\Core\StatBotUserOnDay;
use App\Models\Core\TelegramSupergroup;

class BotSendUserOnDay
{
    public function handle() {
        $stat = StatBotUserOnDay::orderByDesc('created_at')->first();

        $bot_message = BotMessage::query()
            ->whereHas('bot_message_appointment', function ($query) {
                $query->where('alias', 'SYS_STAT_PER_DAY');
            })
            ->first();

        $text = $bot_message->text;
        $text = urldecode($text);
        $text = str_replace('VAR_STAT_USER_ON_DAY_DATE', date('d.m.Y', strtotime($stat->date)), $text);
        $text = str_replace('VAR_STAT_USER_ON_DAY_COUNT', $stat->bot_user_count, $text);

        $supergroups = TelegramSupergroup::with('bot')->where('statistic_user_on_day', 1)->get();
        foreach ($supergroups as $supergroup) {
            $telegram = new Api($supergroup->bot->telegram_token);
            $telegram->sendMessage(['chat_id' => $supergroup->telegram_id, 'parse_mode' => 'HTML', 'text' => urldecode($text), 'protect_content' => true]);
        }

    }
}
