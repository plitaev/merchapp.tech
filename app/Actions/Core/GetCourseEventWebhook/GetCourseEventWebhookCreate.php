<?php

namespace App\Actions\Core\GetCourseEventWebhook;

use App\Models\Core\GetcourseEventWebhook;

class GetCourseEventWebhookCreate
{
    public function handle(int $getcourse_id, string $name, string $email, string $event) {
        GetcourseEventWebhook::create(
            [
                'getcourse_id' => $getcourse_id,
                'name' => $name,
                'email' => $email,
                'event' => $event
            ]
        );
    }
}
