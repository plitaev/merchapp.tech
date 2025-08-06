<?php
namespace App\Actions\Core\Auto;

use App\Models\Core\TelegramBusinessOpeningLog;
use Carbon\Carbon;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserCreateFromTelegram;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\TelegramBusinessOpeningLog\TelegramBusinessOpeningLogCreate;
use App\Actions\Core\TelegramBusinessOpeningLog\TelegramBusinessOpeningLogGetLastEvent;
use App\Actions\Core\Telegram\TelegramDirectQuery;

use App\Models\Core\BotUser;
use App\Models\Core\TelegramBusinessOpening;
use App\Models\Core\TelegramWebhook;

class TelegramBusinessResponceInOpeningHours
{
    public function handle() {
        $botSendMessage = new BotSendMessage();
        $botUserCreateFromTelegram = new BotUserCreateFromTelegram();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $telegramBusinessOpeningLogCreate = new TelegramBusinessOpeningLogCreate();
        $telegramBusinessOpeningLogGetLastEvent = new TelegramBusinessOpeningLogGetLastEvent();
        $telegramDirectQuery = new TelegramDirectQuery();

        $bot_users = BotUser::with('bot')
            ->select('id', 'telegram_chat_id', 'bot_id', 'time_zone_name')
            ->where('business_bot_account', 1)
            ->get();

        foreach ($bot_users as $bot_user) {

            //=== Ищем отправку ботом и оператором
            if ($bot_user->bot->business_bot_delay_after_bot_sent_message_in_minutes) {
                $telegramBusinessOpeningLogGetLastEvent->handle($bot_user, 'BOT_SENT_MESSAGE', $bot_user->bot->business_bot_delay_after_bot_sent_message_in_minutes);
            }

            if ($bot_user->bot->business_bot_delay_after_operator_sent_message_in_minutes) {
                $telegramBusinessOpeningLogGetLastEvent->handle($bot_user, 'RESPONCE_SET_IN_CORRECT_TIME', $bot_user->bot->business_bot_delay_after_operator_sent_message_in_minutes);
            }
            //===

            $time_zone = (isset($bot_user->time_zone_name) ? $bot_user->time_zone_name : '');

            $webhooks = TelegramWebhook::select('id', 'created_at', 'business_message_chat_id', 'business_message_from_id', 'callback', 'message_id')
                ->where('bot_id', $bot_user->bot_id)
                ->where('business_message_check_status', 0)
                ->whereNot('business_message_from_id', $bot_user->telegram_chat_id)
                ->orderBy('created_at')
                ->get();

            foreach ($webhooks as $webhook) {
                $created_at = $webhook->created_at->format('Y-m-d H:i:s');
                $week_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $created_at, $time_zone)->startOfWeek();
                $minute = $week_start_date->DiffInMinutes(Carbon::now($time_zone));
                $minute = round($minute, 0);

                $business_hours = TelegramBusinessOpening::where('bot_user_id', $bot_user->id)
                    ->where('opening_minute', '<=', $minute)
                    ->where('closing_minute', '>=', $minute)
                    ->count();

                if ($business_hours > 0) {

                    $responce = TelegramWebhook::select('id', 'created_at')
                        ->where('bot_id', $bot_user->bot_id)
                        ->where('business_message_check_status', 0)
                        ->where('business_message_chat_id', $webhook->business_message_chat_id)
                        ->where('business_message_from_id', $bot_user->telegram_chat_id)
                        ->where('created_at', '>=', $webhook->created_at)
                        ->first();

                    if ($responce) {

                        $diff = $webhook->created_at->diffInMinutes($responce->created_at);
                        $diff = round($diff, 0);

                        if (isset($bot_user->bot->message_worktime_after_minutes)) {

                            if ($diff <= $bot_user->bot->message_worktime_after_minutes) {
                                $webhooks = $telegramBusinessOpeningLogCreate->handle($bot_user, $webhook->id, $responce->id, $diff, 'RESPONCE_SET_IN_CORRECT_TIME', 1);
                            } else {
                                $webhooks = $telegramBusinessOpeningLogCreate->handle($bot_user, $webhook->id, $responce->id, $diff, 'RESPONCE_SET_IN_BIGGER_TIME', 1);
                            }

                        } else {
                            $webhooks = $telegramBusinessOpeningLogCreate->handle($bot_user, $webhook->id, $responce->id, $diff, 'INTERVAL_IN_MINUTES_NOT_SET', 0);
                        }

                    } else {
                        $deadline = Carbon::parse($created_at)->addMinutes($bot_user->bot->message_worktime_after_minutes)->format('Y-m-d H:i:s');

                        if ($deadline < date('Y-m-d H:i:s', time())) {

                            $methods = [
                                "chat_id" => $webhook->business_message_chat_id,
                                "business_connection_id" => $bot_user->bot->business_connection_id,
                                "message_id" => $webhook->message_id
                            ];
                            $telegramDirectQuery->handle($bot_user->bot_id, "readBusinessMessage", $methods);

                            $botUserCreateFromTelegram->handle($webhook->business_message_chat_id, $bot_user->bot_id, $webhook->callback);
                            $bot_user_for_send = $botUserGetFromTelegram->handle($bot_user->bot_id, $webhook->business_message_chat_id);
                            $botSendMessage->handle($bot_user_for_send, 'SYS_SUPPORT_RESPONCE_TO_UNREAD_MESSAGE_IN_WORK_TIME');
                            $webhooks = $telegramBusinessOpeningLogCreate->handle($bot_user, $webhook->id, NULL, $bot_user->bot->message_worktime_after_minutes, 'BOT_SENT_MESSAGE', 1);
                            TelegramWebhook::where('id', $webhook->id)->update(['business_message_check_status' => 1]);
                        }
                    }

                } else {
                    $webhooks = $telegramBusinessOpeningLogCreate->handle($bot_user, $webhook->id, NULL, 0, 'NON_BUSINESS_HOURS', 1);
                }

            }
        }

        return 'end';
    }
}
