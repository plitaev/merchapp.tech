<?php

namespace App\Actions\Core\BotUser;

class BotUserCreateFromMax
{
    public function handle(int $chat_id, int $bot_id, $webhook) {

        if (!is_array($webhook)) $webhook = json_decode($webhook, true);

        $first_name = (isset($webhook['user']['first_name'])?$webhook['user']['first_name']:'none');
        $last_name = (isset($webhook['user']['last_name'])?$webhook['user']['last_name']:'none');
        $username = 'none';
        $language_code = (isset($webhook['user']['user_locale'])?$webhook['user']['user_locale']:'no');

        // Пишем их в БД
        if (isset($webhook['user'])) {
            \App\Models\Core\BotUser::updateOrCreate(
                ['max_user_id' => $chat_id, 'bot_id' => $bot_id],
                ['first_name' => $first_name, 'last_name' => $last_name, 'username' => $username, 'language_code' => $language_code]
            );
        }

    }
}
