<?php
namespace App\Http\Controllers\Core;
use App\Http\Controllers\Controller;
use App\Models\Core\TelegramWebhook;

use Telegram\Bot\Api;

class DevTestController extends Controller
{
    public function devtest() {
        $telegram = new Api("7248655346:AAGLhHiGz4za7SsZKQ-Q47C5Nfvn5QFD4Is");
        return $telegram->sendInvoice([
            'provider_token' => '390540012:LIVE:75160',
            'chat_id' => 247632034,
            'title' => 'Тестовый продукт',
            'description' => 'Описание продукта',
            'payload' => 'payload_text',
            'currency' => 'RUB',
            'prices' => 3000
        ]);
    }
}
