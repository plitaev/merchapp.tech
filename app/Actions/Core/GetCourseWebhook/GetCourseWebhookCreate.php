<?php
namespace App\Actions\Core\GetCourseWebhook;

use App\Actions\Core\Pay\PayCreateByEmail;
use App\Models\Core\GetcourseWebhook;

class GetCourseWebhookCreate
{
    public function handle(int $product_id, int $getcourse_user_id, string $getcourse_user_name, string $email, int $recurrent, int $recurrent_status) {
        $payCreateByEmail = new PayCreateByEmail();

        GetcourseWebhook::create(
            [
                'product_id' => $product_id,
                'getcourse_id' => $getcourse_user_id,
                'name' => $getcourse_user_name,
                'email' => $email,
                'recurrent' => $recurrent,
                'recurrent_status' => $recurrent_status
            ]
        );

        return $payCreateByEmail->handle($email, $product_id, $recurrent, $recurrent_status);

    }
}
