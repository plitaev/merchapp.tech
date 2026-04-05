<?php

namespace App\Actions\Core\Max;

class MaxGetChatIDFromWebhook
{
    public function handle($webhook) {

        if (isset($webhook['user']['user_id'])) $user_id=$webhook['user']['user_id'];
        if (!isset($user_id) && isset($webhook['message']['sender']['user_id'])) $user_id=$webhook['message']['sender']['user_id'];

        if (!isset($user_id)) return 0;

        return $user_id;

    }
}
