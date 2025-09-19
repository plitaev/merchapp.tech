<?php
namespace App\Http\Controllers\Core;

use YooKassa\Client;

use App\Actions\Core\BotUser\BotUserGetFullName;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Yookassa\YookassaMakeProductJSON;

use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\PaySystem;
use App\Models\Core\Product;

class PayController
{
    public function create(string $pay_system_alias, int $bot_user_id, int $product_id) {

        $botUserGetFullName = new BotUserGetFullName();
        $payCreateIntoBot = new PayCreateIntoBot();
        $yookassaMakeProductJSON = new YookassaMakeProductJSON();


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

        $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => 'RUB'),
            'confirmation' => array('type' => 'redirect', 'return_url' => env("APP_URL").'/thank-you/'.$bot_user->bot_id),
            'save_payment_method' => true,
            'receipt' => array('customer' => array('full_name' => $botUserGetFullName->handle($bot_user), 'email' => $bot_user->email), 'items' => $yookassaMakeProductJSON->handle($bot, $product, $product->price, true)),
            'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['order_number' => $pay->id]),uniqid('', true));

        $confirmationUrl=$payment->getConfirmation()->getConfirmationUrl();

        return redirect($confirmationUrl);

    }

    public function thank_you(int $bot_id) {
        $bot = Bot::select('alias')->find($bot_id);
        return view('core.pay.thank_you', ['bot' => $bot]);
    }

}
