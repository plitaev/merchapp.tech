<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetByEmail;
use App\Actions\Core\GetCourseEventWebhook\GetCourseEventWebhookCreate;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;

use App\Models\Core\BotMessage;

class GetCourseController extends Controller
{
    public function getcourse_webhook(int $product_id, int $getcourse_user_id, string $getcourse_user_name, string $email, int $is_recurrent, int $recurrent_status) {
        $getCourseWebhookCreate = new GetCourseWebhookCreate();
        $getCourseWebhookCreate->handle($product_id, $getcourse_user_id, $getcourse_user_name, $email, $is_recurrent, $recurrent_status);
    }

    public function getcourse_event_webhooks(int $getcourse_id, string $name, string $email, int $bot_id, string $event) {

        $botSendMessage = new BotSendMessage();
        $botUserGetByEmail = new BotUserGetByEmail();
        $getCourseEventWebhookCreate = new GetCourseEventWebhookCreate();

        $getCourseEventWebhookCreate->handle($getcourse_id, $name, $email, $bot_id, $event);
        $bot_user = $botUserGetByEmail->handle($bot_id, $email);

        if ($bot_user) {
            $bot_message = BotMessage::with('funnel_condition')->with('bot_message_appointment')
                ->whereHas('funnel_condition', function ($query) use ($event) {
                    $query->where('alias', $event);
                })
                ->first();

            if ($bot_message) {
                $botSendMessage->handle($bot_user, $bot_message->bot_message_appointmen->alias);
            }

        }

    }

}
