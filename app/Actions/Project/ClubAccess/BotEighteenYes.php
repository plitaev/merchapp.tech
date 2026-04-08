<?php
namespace App\Actions\Project\ClubAccess;

use App\Models\Core\BotUser;

class BotEighteenYes
{
    public function handle(string $messenger, $bot_user, $telegram, $webhook) {
        $botMainMenuMessage = new BotMainMenuMessage();

        BotUser::where('id', $bot_user->id)->update(['eighteen' => 1]);

        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $botMainMenuMessage->handle($bot_user); //== Обрабатываем сообщение с главным меню
    }
}
