<?php
namespace App\Actions\Core\Auto;

use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\TelegramScheduleDeleteMessage;

class TelegramScheduleDeleteMessageProcess
{
    public function handle() {
        $telegramQuery = new TelegramQuery();

        $res = TelegramScheduleDeleteMessage::with('bot')
            ->select('id', 'bot_message_id', 'chat_id', 'telegram_message_id')
            ->where('status', 0)
            ->where('delete_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->orderBy('created_at')
            ->take(100)
            ->get();

        foreach ($res as $data) {
            TelegramScheduleDeleteMessage::where('id', $data->id)->update(['status' => 1]);

            try {
                $telegramQuery->handle($data->bot, 'deleteMessage', ['chat_id' => $data->chat_id, 'message_id' => $data->telegram_message_id]);
            } catch (\Exception $exception) {
                TelegramScheduleDeleteMessage::where('id', $data->id)->update(['error_text' => $exception]);
            }

        }

    }
}
