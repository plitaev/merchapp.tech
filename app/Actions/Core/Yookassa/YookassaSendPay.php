<?php
namespace App\Actions\Core\Yookassa;

use YooKassa\Client;

use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Models\Core\PaySystem;

class YookassaSendPay
{
    public function handle($telegram, $bot, $bot_user, $product, $webhook) {

        $payCreateIntoBot = new PayCreateIntoBot();

        if (isset($webhook['callback_query']['message']['message_id'])) {
            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
        }

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        $pay_system_id = NULL;
        if (isset($bot->yookassa_shop_id) && isset($bot->yookassa_shop_secret)) $pay_system = PaySystem::where('alias', 'yookassa')->first();
        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        $additional_data = [];
        $additional_data['pay_system_id'] = $pay_system_id;

        $pay = $payCreateIntoBot->handle($bot_user, $product, $additional_data);

        $products = [];
        $products[]=["description" => $product->product_name, "quantity" => "1.00", "amount" => ["value" => $product->price, "currency" => "RUB", "vat_code" => 1, "payment_mode" => "full_payment", "payment_subject" => "service"]];

        $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => 'RUB'),
            'confirmation' => array('type' => 'redirect', 'return_url' => env("APP_URL")),
            'receipt' => array('customer' => array('full_name' => (isset($bot_user->first_name)?$bot_user->first_name:'').' '.(isset($bot_user->last_name)?$bot_user->last_name:''), 'email' => $bot_user->email), 'items' => $products),
            'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['orderNumber' => $pay->id]),uniqid('', true));

        $confirmationUrl=$payment->getConfirmation()->getConfirmationUrl();


    }
}
