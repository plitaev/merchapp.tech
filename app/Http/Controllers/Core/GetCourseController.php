<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Http\Controllers\Controller;

class GetCourseController extends Controller
{
    public function getcourse_webhook(int $product_id, int $getcourse_user_id, string $getcourse_user_name, string $email, int $is_recurrent, int $recurrent_status) {
        $getCourseWebhookCreate = new GetCourseWebhookCreate();
        $getCourseWebhookCreate->handle($product_id, $getcourse_user_id, $getcourse_user_name, $email, $is_recurrent, $recurrent_status);
    }
}
