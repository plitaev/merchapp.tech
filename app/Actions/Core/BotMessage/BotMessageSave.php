<?php

namespace App\Actions\Core\BotMessage;

use App\Models\Core\TelegramScheduleDeleteMessage;
use App\Models\Core\TelegramScheduleEditMessage;

class BotMessageSave
{
    public function handle(array $data, int $bot_message_id) {

        if ((isset($data['delete_through']) && $data['delete_through'] == 0) || !isset($data['delete_through'])) {
            TelegramScheduleDeleteMessage::where('bot_message_id', $bot_message_id)->where('status', 0)->delete();
        }

        if ((isset($data['delete_through_keyboard']) && $data['delete_through_keyboard'] == 0) || !isset($data['delete_through_keyboard'])) {
            TelegramScheduleEditMessage::where('bot_message_id', $bot_message_id)->where('status', 0)->delete();
        }

    }
}
