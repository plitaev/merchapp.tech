<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\BotMessageButton;
use App\Models\Core\BotMessageButtonClick;

class BotMessageButtonController extends Controller
{
    public function go(string $bot_message_button_id, string $chat_id) {
        (int) $bot_message_button_id = base64_decode($bot_message_button_id);
        (int) $chat_id = base64_decode($chat_id);

        BotMessageButtonClick::create(['chat_id' => $chat_id, 'bot_message_button_id' => $bot_message_button_id]);

        $bot_message_button = BotMessageButton::select('url')->find($bot_message_button_id);
        return redirect($bot_message_button->url);
    }
}
