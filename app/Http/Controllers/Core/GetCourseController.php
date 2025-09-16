<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Actions\Core\GetCourseEventWebhook\GetCourseEventWebhookCreate;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;

class GetCourseController extends Controller
{
    public function getcourse_webhook(int $product_id, int $getcourse_user_id, string $getcourse_user_name, string $email, int $is_recurrent, int $recurrent_status) {
        $getCourseWebhookCreate = new GetCourseWebhookCreate();
        $getCourseWebhookCreate->handle($product_id, $getcourse_user_id, $getcourse_user_name, $email, $is_recurrent, $recurrent_status);
    }

    public function getcourse_event_webhooks(int $getcourse_id, string $name, string $email, string $event) {
        $getCourseEventWebhookCreate = new GetCourseEventWebhookCreate();
        $getCourseEventWebhookCreate->handle($getcourse_id, $name, $email, $event);
    }

}
