<?php

namespace App\Actions\Core\TelegramBusinessOpeningLog;

use App\Models\Core\TelegramBusinessOpeningLog;
use App\Models\Core\TelegramWebhook;

class TelegramBusinessOpeningLogGetLastEvent
{
    public function handle($bot_user, string $event_name, int $minutes) {
        $log = TelegramBusinessOpeningLog::with('telegram_webhook_entrance:id,bot_id,business_message_chat_id')
            ->select('id', 'telegram_webhook_entrance_id', 'created_at')
            ->whereHas('telegram_webhook_entrance', function ($query) use ($bot_user) {
                $query->where('bot_id', $bot_user->bot_id);
            })
            ->where('event_name', $event_name)
            ->orderByDesc('created_at')
            ->first();

        if ($log) {
            $lbr_created_at = $log->created_at;
            $lbr_created_at->addMinutes($minutes);
            $lbr_created_at = $lbr_created_at->format('Y-m-d H:i:s');

            TelegramWebhook::where('business_message_chat_id', $log->telegram_webhook_entrance->business_message_chat_id)
                ->where('created_at', '>=', $log->created_at->format('Y-m-d H:i:s'))
                ->where('created_at', '<=', $lbr_created_at)
                ->update(['business_message_check_status' => 1]);
        }

    }
}
