<?php
namespace App\Actions\Core\BotSendMessage;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use App\Actions\Core\BotUser\BotUserSetListener;
use App\Actions\Core\Max\MaxSendMessage;
use App\Actions\Core\Telegram\TelegramSendMessage;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageListener;
use App\Models\Core\BotUser;
use App\Models\Core\User;

class BotSendMessage
{
    public function handle($bot_user, string $bot_message_appointment_name, string $send_to = 'all') {

        $botUserSetListener = new BotUserSetListener();
        $maxSendMessage = new MaxSendMessage();
        $telegramSendMessage = new TelegramSendMessage();

        $bot_message_appointment = BotMessageAppointment::where('alias', $bot_message_appointment_name)->first();

        if ($bot_message_appointment) {

            if ($bot_message_appointment_name == 'SYS_SUCCESS_MESSAGE' && env("MERCHAPP_WEB_VERSION") == 1) {
                $check = User::where('email', $bot_user->email)->first();
                if (!$check) {

                    $plainPassword = Str::password(8, true, true, false, false);
                    $hashedPassword = Hash::make($plainPassword);

                    User::create([
                        'name' => $bot_user->first_name." ".$bot_user->last_name,
                        'email' => $bot_user->email,
                        'password' => $hashedPassword,
                        'open_password' => $plainPassword
                    ]);

                }
            }

            if (isset($bot_user->bot_branch_id)) {

                $bot_message = BotMessage::select('id', 'pause_after_message')
                    ->where('bot_id', $bot_user->bot_id)
                    ->where('bot_branch_id', $bot_user->bot_branch_id)
                    ->where('bot_message_appointment_id', $bot_message_appointment->id)
                    ->first();

                if (!$bot_message) {

                    $bot_message = BotMessage::select('id', 'pause_after_message')
                        ->where('bot_id', $bot_user->bot_id)
                        ->where('bot_message_appointment_id', $bot_message_appointment->id)
                        ->first();

                }

            } else {

                $bot_message = BotMessage::select('id', 'pause_after_message')
                    ->where('bot_id', $bot_user->bot_id)
                    ->where('bot_message_appointment_id', $bot_message_appointment->id)
                    ->first();

            }

            if ($bot_message) {

                //== Отправляем само сообщение
                if ($bot_user->max_user_id && ($send_to == 'all' || $send_to == 'max')) {

                    if ($bot_message_appointment_name == 'SYS_SUCCESS_MESSAGE') {
                        $bot_message_appointment_max = BotMessageAppointment::where('alias', 'SYS_SUCCESS_MESSAGE_MAX')->first();

                        $bot_message_max = BotMessage::select('id', 'pause_after_message')
                            ->where('bot_id', $bot_user->bot_id)
                            ->where('bot_message_appointment_id', $bot_message_appointment_max->id)
                            ->first();

                        $message = $maxSendMessage->handle($bot_user, $bot_message_max->id, $bot_message_appointment_max->alias);
                    } else {
                        $message = $maxSendMessage->handle($bot_user, $bot_message->id, $bot_message_appointment_name);
                    }

                }

                if ($bot_user->telegram_chat_id && ($send_to == 'all' || $send_to == 'telegram')) {
                    return $telegramSendMessage->handle($bot_user, $bot_message->id, $bot_message_appointment_name);
                }

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

                BotUser::where('id', $bot_user->id)->update(['listen_email_status' => 1, 'listen_time_status_timestamp' => date('Y-m-d H:i:s')]);

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
