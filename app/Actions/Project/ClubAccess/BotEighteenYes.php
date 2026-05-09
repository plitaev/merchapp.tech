<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\BotUser;

class BotEighteenYes
{
    public function handle(string $messenger, $bot_user, $webhook) {
        $botMainMenuMessage = new BotMainMenuMessage();
        $telegramQuery = new TelegramQuery();

        BotUser::where('id', $bot_user->id)->update(['eighteen' => 1]);

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegramQuery->handle($bot_user->bot, 'answerCallbackQuery', ['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $botMainMenuMessage->handle($messenger, $webhook, $bot_user); //== Обрабатываем сообщение с главным меню
    }
}
