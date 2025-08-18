<?php
namespace App\Http\Controllers\Core;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\Product;
use App\Models\Core\TelegramWebhook;

use YooKassa\Client;
use Telegram\Bot\Api;

class DevTestController extends Controller
{
    public function devtest() {

        $bot_user = BotUser::find(1);
        $bot = Bot::find($bot_user->bot_id);
        $product = Product::find(1);

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => 100,
                    'currency' => 'RUB',
                ),
                'capture' => true,
                'payment_method_id' => '30350d4f-000f-5000-b000-11da1ea9d023',
                'description' => 'Заказ №90',
            ),
            uniqid('', true)
        );

        return $payment;

    }
}
