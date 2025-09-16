<?php

namespace App\Actions\Core\GetCourseEventWebhook;

use App\Models\Core\GetcourseEventWebhook;

class GetCourseEventWebhookCreate
{
    public function handle(int $getcourse_id, string $name, string $email, int $bot_id, string $event) {
        GetcourseEventWebhook::create(
            [
                'getcourse_id' => $getcourse_id,
                'name' => $name,
                'email' => $email,
                'bot_id' => $bot_id,
                'event' => $event
            ]
        );
    }
}
