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

        $products = [];
        $products[]=["description" => $product->name, "quantity" => "1.00", "amount" => ["value" => $product->price, "currency" => "RUB", "vat_code" => "1", "payment_mode" => "full_payment", "payment_subject" => "service"]];

        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $product->price,
                    'currency' => 'RUB',
                ),
                'capture' => true,
                'receipt' => array('customer' => array('full_name' => (isset($bot_user->first_name)?$bot_user->first_name:'').' '.(isset($bot_user->last_name)?$bot_user->last_name:''), 'email' => $bot_user->email), 'items' => $products),
                'payment_method_id' => '30350d4f-000f-5000-b000-11da1ea9d023',
                'description' => 'Заказ №90',
            ),
            uniqid('', true)
        );

        return $payment;

    }
}
