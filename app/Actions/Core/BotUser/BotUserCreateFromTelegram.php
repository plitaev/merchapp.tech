<?php
namespace App\Actions\Core\BotUser;

class BotUserCreateFromTelegram
{
    public function handle(int $chat_id, int $bot_id, $webhook) {

        if (!is_array($webhook)) $webhook = json_decode($webhook, true);

        $first_name = (isset($webhook['message']['chat']['first_name'])?$webhook['message']['chat']['first_name']:'none');
        $last_name = (isset($webhook['message']['chat']['last_name'])?$webhook['message']['chat']['last_name']:'none');
        $username = (isset($webhook['message']['chat']['username'])?$webhook['message']['chat']['username']:'none');
        $language_code = (isset($webhook['message']['from']['language_code'])?$webhook['message']['from']['language_code']:'no');

        if ($first_name == "none") $first_name = (isset($webhook['business_message']['from']['first_name'])?$webhook['business_message']['from']['first_name']:'none');
        if ($last_name == "none") $last_name = (isset($webhook['business_message']['from']['last_name'])?$webhook['business_message']['from']['last_name']:'none');
        if ($username == "none") $username = (isset($webhook['business_message']['from']['username'])?$webhook['business_message']['from']['username']:'none');
        if ($language_code == "none") $language_code = (isset($webhook['business_message']['from']['language_code'])?$webhook['business_message']['from']['language_code']:'no');

        // Пишем их в БД
        if (isset($webhook['message']) || isset($webhook['business_message'])) {
            \App\Models\Core\BotUser::updateOrCreate(
                ['telegram_chat_id' => $chat_id, 'bot_id' => $bot_id],
                ['first_name' => $first_name, 'last_name' => $last_name, 'username' => $username, 'language_code' => $language_code]
            );
        }

    }
}
