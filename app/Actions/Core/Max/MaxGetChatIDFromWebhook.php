<?php
namespace App\Actions\Core\Max;

class MaxGetChatIDFromWebhook
{
    public function handle($webhook) {

        if (isset($webhook['message'])) {
            $chat_id=$webhook['message']['chat']['id'];
        } else {
            if (isset($webhook['callback_query'])) {
                $chat_id = $webhook['callback_query']['message']['chat']['id'];
            }
        }

        if (!isset($chat_id) && !isset($webhook['channel_post'])) return 0;
        if (!isset($chat_id)) return 0;

        return $chat_id;

    }
}
