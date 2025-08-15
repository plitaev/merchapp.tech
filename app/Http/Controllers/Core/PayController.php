<?php
namespace App\Http\Controllers\Core;
use YooKassa\Client;

use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\Pay\PayCreateIntoBot;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\PaySystem;
use App\Models\Core\Product;

class PayController
{
    public function create(string $pay_system_alias, int $bot_user_id, int $product_id) {
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $payCreateIntoBot = new PayCreateIntoBot();

        $bot_user = BotUser::find($bot_user_id);
        $bot = Bot::find($bot_user->bot_id);
        $product = Product::find($product_id);

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        $pay_system_id = NULL;
        $pay_system = PaySystem::where('alias', $pay_system_alias)->first();
        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        $additional_data = [];
        $additional_data['pay_system_id'] = $pay_system_id;

        $pay = $payCreateIntoBot->handle($bot_user, $product, $additional_data);

        $products = [];
        $products[]=["description" => $product->name, "quantity" => "1.00", "amount" => ["value" => $product->price, "currency" => "RUB", "vat_code" => "1", "payment_mode" => "full_payment", "payment_subject" => "service"]];

        $products = [
            [
                'description' => $product->name,
                'quantity' => '1.00',
                'amount' => [
                    'value' => $product->price,
                    'currency' => 'RUB'
                ],
                'vat_code' => '1',
                'payment_mode' => 'full_payment',
                'payment_subject' => 'commodity'
                ]
        ];

        $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => 'RUB'),
            'confirmation' => array('type' => 'redirect', 'return_url' => 'https://google.com'),
            'receipt' => array('customer' => array('full_name' => (isset($bot_user->first_name)?$bot_user->first_name:'').' '.(isset($bot_user->last_name)?$bot_user->last_name:''), 'email' => $bot_user->email), 'items' => $products),
            'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['orderNumber' => $pay->id]),uniqid('', true));

        $confirmationUrl=$payment->getConfirmation()->getConfirmationUrl();

        return redirect($confirmationUrl);

    }
}
