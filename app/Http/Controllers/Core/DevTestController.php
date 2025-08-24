<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Http\Controllers\Controller;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramWebhook;

use YooKassa\Client;
use Telegram\Bot\Api;

class DevTestController extends Controller
{
    public function devtest() {
        /*
        $bot_user = BotUser::find(1);
        $bot = Bot::find($bot_user->bot_id);
        $product = Product::find(7);

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        return $client->getPaymentInfo("3035311b-000f-5001-9000-1b771a469f1d");
        */
        /*
        $bot_user = BotUser::find(1);
        $bot = Bot::find($bot_user->bot_id);
        $product = Product::find(7);

        $dateEnd = new DateEnd();

        $new = \App\Models\Core\Pay::create([
            'product_id' => $product->id,
            'gift' => 0,
            'bot_user_id' => $bot_user->id,
            'price' => $product->price,
            'days' => $product->days,
            'status' => 0,
            'recurrent' => 0,
            'recurrent_status' => 0,
            'pay_system_id' => 1
        ]);

        $dateEnd->handle($bot_user, 'Y-m-d');

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        $products = [
            [
                'description' => $product->name,
                'quantity' => '1.00',
                'amount' => [
                    'value' => $product->price,
                    'currency' => 'RUB'
                ],
                'tax_system_code' => 6,
                'vat_code' => '1',
                'payment_mode' => 'full_payment',
                'payment_subject' => 'service',
                'save_payment_method' => true
            ]
        ];

        $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => 'RUB'),
            'confirmation' => array('type' => 'redirect', 'return_url' => env("APP_URL").'/thank-you/'.$bot_user->bot_id),
            'payment_method_id' => '30352fc9-000f-5000-b000-1bf08ebe361e',
            'receipt' => array('customer' => array('full_name' => (isset($bot_user->first_name)?$bot_user->first_name:'').' '.(isset($bot_user->last_name)?$bot_user->last_name:''), 'email' => $bot_user->email), 'items' => $products),
            'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['order_number' => $new->id]),uniqid('', true));

        return $payment;
        */
    }
}
