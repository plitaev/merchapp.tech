<?php

namespace App\Actions\Core\GetCourseEventWebhook;

use App\Models\Core\GetCourseEventWebhook;

class GetCourseEventWebhookCreate
{
    public function handle(int $getcourse_id, string $name, string $email, string $event) {
        GetCourseEventWebhook::create(
            [
                'getcourse_id' => $getcourse_id,
                'name' => $name,
                'email' => $email,
                'event' => $event
            ]
        );
    }
}
