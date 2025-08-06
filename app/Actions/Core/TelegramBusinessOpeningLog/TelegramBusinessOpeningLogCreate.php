<?php

namespace App\Actions\Core\TelegramBusinessOpeningLog;

use App\Models\Core\TelegramBusinessOpeningLog;
use App\Models\Core\TelegramWebhook;

class TelegramBusinessOpeningLogCreate
{
    public function handle($bot_user, int $telegram_webhook_entrance_id, $telegram_webhook_responce_id, $diff_in_minutes, string $event_name, int $finish_status) {
        TelegramBusinessOpeningLog::create(
            [
                'telegram_webhook_entrance_id' => $telegram_webhook_entrance_id,
                'telegram_webhook_responce_id' => $telegram_webhook_responce_id,
                'event_name' => $event_name,
                'diff_in_minutes' => $diff_in_minutes
            ]
        );

        if ($finish_status == 1) {
            TelegramWebhook::where('id', $telegram_webhook_entrance_id)->update(['business_message_check_status' => 1]);
            TelegramWebhook::where('id', $telegram_webhook_responce_id)->update(['business_message_check_status' => 1]);

            $webhook = TelegramWebhook::select('business_message_chat_id')->find($telegram_webhook_entrance_id);
            if ($webhook) {
                TelegramWebhook::where('business_message_chat_id', $webhook->business_message_chat_id)->update(['business_message_check_status' => 1]);
            }

        }

        $webhooks = TelegramWebhook::select('id', 'created_at', 'business_message_chat_id', 'business_message_from_id', 'callback', 'message_id')
            ->where('bot_id', $bot_user->bot_id)
            ->where('business_message_check_status', 0)
            ->whereNot('business_message_from_id', $bot_user->telegram_chat_id)
            ->orderBy('created_at')
            ->get();

        return $webhooks;
    }
}
