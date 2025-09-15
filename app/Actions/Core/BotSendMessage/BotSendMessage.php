<?php
namespace App\Actions\Core\BotSendMessage;

use Illuminate\Support\Facades\Schema;

use App\Actions\Core\BotUser\BotUserSetListener;
use App\Actions\Core\Telegram\TelegramSendMessage;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageListener;
use App\Models\Core\BotUser;


class BotSendMessage
{
    public function handle($bot_user, string $bot_message_appointment_name) {

        $botUserSetListener = new BotUserSetListener();
        $telegramSendMessage = new TelegramSendMessage();

        $bot_message_appointment = BotMessageAppointment::where('alias', $bot_message_appointment_name)->first();

        if ($bot_message_appointment) {

            $bot_message = BotMessage::select('id', 'pause_after_message')->where('bot_id', $bot_user->bot_id)->where('bot_message_appointment_id', $bot_message_appointment->id)->first();

            if ($bot_message) {

                //== Отправляем само сообщение
                $message = $telegramSendMessage->handle($bot_user, $bot_message->id, $bot_message_appointment_name);

                //== Проверяем статусы, и ставим, если есть
                $schema=Schema::getColumnListing(with (new BotUser())->getTable());
                $field_status = mb_strtolower($bot_message_appointment->alias).'_status';
                $field_timestamp = mb_strtolower($bot_message_appointment->alias).'_status_timestamp';

                if (in_array($field_status, $schema) && in_array($field_timestamp, $schema)) {
                    BotUser::where('id', $bot_user->id)->update([$field_status => 1, $field_timestamp => now()]);
                }

                //== Проверяем listener-ы, и ставим, если есть
                $listeners = BotMessageListener::with('listener:id,alias')->where('bot_message_id', $bot_message->id)->get();
                foreach ($listeners as $listener) $botUserSetListener->handle($listener->listener->alias, 1, $bot_user->id);

                //== Проверяем паузу, и отправляем, если есть
                if ($bot_message->pause_after_message > 0) sleep($bot_message->pause_after_message);

                //== Завершаем отправку
                return $message;

            } else {
                return 'BOT_MESSAGE_NOT_FOUND';
            }

        } else {
            return 'BOT_MESSAGE_APPOINTMENT_NOT_FOUND';
        }

    }
}
