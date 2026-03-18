<?php
namespace App\Actions\Core\Max;

use App\Models\Core\Bot;
use App\Models\Core\MaxWebhook;

class MaxWebhookWrite
{
    public function handle($data, int $bot_id) {
        $json = json_decode($data, true);
        MaxWebhook::create(['bot_id' => $bot_id, 'callback' => $data]);
        return $json;
    }
}
