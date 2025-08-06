<?php
namespace App\Actions\Core\Auto;
use App\Models\Core\TelegramScheduleDeleteMessage;
use Telegram\Bot\Api;

class TelegramScheduleDeleteMessageProcess
{
    public function handle() {
        $res = TelegramScheduleDeleteMessage::with('bot:telegram_token')
            ->select('id', 'bot_message_id', 'chat_id', 'telegram_message_id')
            ->where('status', 0)
            ->where('delete_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->orderBy('created_at')
            ->take(100)
            ->get();

        foreach ($res as $data) {
            TelegramScheduleDeleteMessage::where('id', $data->id)->update(['status' => 1]);

            $telegram = new Api($data->bot->telegram_token);

            try {
                $telegram->deleteMessage(['chat_id' => $data->chat_id, 'message_id' => $data->telegram_message_id]);
            } catch (\Exception $exception) {
                TelegramScheduleDeleteMessage::where('id', $data->id)->update(['error_text' => $exception]);
            }

        }

    }
}
