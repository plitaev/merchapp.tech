<?php
namespace App\Actions\Core\Auto;

use App\Actions\Core\MySQL\InnoDBUpsertStopIncrementIncreasing;
use App\Models\Core\BotMessage;
use App\Models\Core\TelegramScheduleDeleteMessage;
use App\Models\Core\TelegramScheduleEditMessage;
use App\Models\Core\TelegramSendMessageLog;

class TelegramScheduleEditMessageFill
{
    public function handle() {
        $innoDBUpsertStopIncrementIncreasing = new InnoDBUpsertStopIncrementIncreasing();

        $bot_messages = BotMessage::select('id')
            ->where('delete_keyboard_through', 1)
            ->whereNotNull('delete_keyboard_through_days')
            ->whereNotNull('delete_keyboard_through_hours')
            ->whereNotNull('delete_keyboard_through_minutes')
            ->pluck('id')
            ->toArray();

        $edited =TelegramScheduleEditMessage::select('telegram_message_id')
            ->whereIn('bot_message_id', $bot_messages)
            ->where('status', 1)
            ->pluck('telegram_message_id')
            ->toArray();

        $news = TelegramSendMessageLog::with('bot_message:id,delete_keyboard_through_days,delete_keyboard_through_hours,delete_keyboard_through_minutes')
            ->select('chat_id', 'bot_message_id', 'telegram_message_id', 'text', 'telegram_entities', 'created_at')
            ->whereIn('bot_message_id', $bot_messages)
            ->whereNotIn('telegram_message_id', $edited)
            ->get();

        foreach ($news as $new) {
            $edit_dt = $new->created_at->addDays($new->bot_message->delete_keyboard_through_days)->addHours($new->delete_keyboard_through_hours)->addMinutes($new->bot_message->delete_keyboard_through_minutes)->format('Y-m-d H:i:s');

            $innoDBUpsertStopIncrementIncreasing->handle(new TelegramScheduleDeleteMessage());

            TelegramScheduleEditMessage::upsert(
                [
                    'bot_message_id' => $new->bot_message_id,
                    'telegram_message_id' => $new->telegram_message_id,
                    'chat_id' => $new->chat_id,
                    'edit_datetime' => $edit_dt,
                    'text' => $new->text,
                    'entities' => $new->telegram_entities,
                    'status' => 0
                ],
                ['telegram_message_id', 'chat_id'],
                ['updated_at' => now()]
            );
        }

        return 'end';
    }
}
