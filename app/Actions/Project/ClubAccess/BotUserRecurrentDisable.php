<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;

class BotUserRecurrentDisable
{
    public function handle(string $messenger, $bot_user, $webhook) {
        $botSendMessage = new BotSendMessage();
        $telegramQuery = new TelegramQuery();

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

        BotUser::where('id', $bot_user->id)->update(['recurrent' => 0]);
        BotUserRecurrentSchedule::where('bot_user_id', $bot_user->id)->where('run_status', 0)->update(['run_status' => 3]);

        $botSendMessage->handle($bot_user, 'SYS_CABINET_AFTER_RECURRENT_DISABLED');
    }
}
