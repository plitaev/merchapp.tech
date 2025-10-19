<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\Telegram\TelegramDeleteWebhook;
use App\Actions\Core\Telegram\TelegramSetWebhook;
use App\Actions\Core\Telegram\TelegramWebhookInfo;
use App\Actions\Core\Telegram\TelegramWebhookMake;
use App\Http\Controllers\Controller;

class TelegramController extends Controller
{
    public function get_webhook_info(string $token, string $webhook) {
        $telegramWebhookInfo=new TelegramWebhookInfo();
        return $telegramWebhookInfo->handle($token, $webhook);
    }

    public function set_webhook(int $id, string $token, string $webhook) {
        $telegramSetWebhook=new TelegramSetWebhook();
        return $telegramSetWebhook->handle($id, $token, $webhook);
    }

    public function delete_webhook(string $token, string $webhook) {
        $telegramDeleteWebhook=new TelegramDeleteWebhook();
        return $telegramDeleteWebhook->handle($token, $webhook);
    }

}
