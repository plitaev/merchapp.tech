<?php
namespace App\Actions\Core\Auto;
use App\Models\Core\TelegramScheduleEditMessage;
use Telegram\Bot\Api;

class TelegramScheduleEditMessageProcess
{
    public function handle() {
        $res = TelegramScheduleEditMessage::with('bot')->with('bot_message:id,bot_message_type_id')
            ->select('id', 'bot_message_id', 'chat_id', 'telegram_message_id', 'text', 'entities', 'keyboard')
            ->where('status', 0)
            ->where('edit_datetime', '<=', date('Y-m-d H:i:s', time()))
            ->orderBy('created_at')
            ->take(100)
            ->get();

        foreach ($res as $data) {
            TelegramScheduleEditMessage::where('id', $data->id)->update(['status' => 1]);

            $telegram = new Api($data->bot->telegram_token);

            try {
                $A = [];
                $A['parse_mode'] = 'HTML';
                $A['chat_id'] = $data->chat_id;
                $A['message_id'] = $data->telegram_message_id;
                if ($data->entities) $A['entities'] = $data->entities;
                if ($data->keyboard) $A['reply_markup'] = $data->keyboard;

                if ($data->bot_message->bot_message_type_id == 1) {
                    $A['text'] = urldecode($data->text);
                    $edit = $telegram->editMessageText($A);
                    TelegramScheduleEditMessage::where('id', $data->id)->update(['success_text' => $edit]);
                } else {
                    $A['caption'] = urldecode($data->text);
                    $edit = $telegram->editMessageCaption($A);
                    TelegramScheduleEditMessage::where('id', $data->id)->update(['success_text' => $edit]);
                }

            } catch (\Exception $exception) {
                TelegramScheduleEditMessage::where('id', $data->id)->update(['error_text' => $exception]);
            }

        }
    }
}
