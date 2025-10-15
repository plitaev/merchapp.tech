<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Core\HMACController;
use YooKassa\Client;

use App\Actions\Core\BotUser\BotUserGetFullName;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;
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
        $payGetAdditionalData = new PayGetAdditionalData();
        $yookassaMakeProductJSON = new YookassaMakeProductJSON();


        $bot_user = BotUser::find($bot_user_id);
        $bot = Bot::query()
            ->with('yookassa_tax_system_code')
            ->with('yookassa_vat_code')
            ->with('yookassa_payment_mode')
            ->with('yookassa_payment_subject')
            ->find($bot_user->bot_id);

        $product = Product::find($product_id);

        $client = new Client();
        $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

        $pay_system_id = NULL;
        $pay_system = PaySystem::where('alias', $pay_system_alias)->first();
        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        $pay = $payCreateIntoBot->handle($bot_user, $product, $payGetAdditionalData->handle($pay_system_id));

        $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => $bot->yookassa_currency),
            'confirmation' => array('type' => 'redirect', 'return_url' => env("APP_URL").'/thank-you/'.$bot_user->bot_id),
            'save_payment_method' => true,
            'receipt' => array('customer' => array('full_name' => $botUserGetFullName->handle($bot_user), 'email' => $bot_user->email), 'items' => $yookassaMakeProductJSON->handle($bot, $product, $product->price, true)),
            'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['order_number' => $pay->id]),uniqid('', true));

        $confirmationUrl=$payment->getConfirmation()->getConfirmationUrl();

        return redirect($confirmationUrl);
    }

    public function create_prodamus(string $pay_system_alias, int $bot_user_id, int $product_id) {
        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();

        $bot_user = BotUser::find($bot_user_id);

        $bot = Bot::query()
            ->with('prodamus_payment_method')
            ->with('prodamus_payment_object')
            ->with('prodamus_npd_income_type')
            ->find($bot_user->bot_id);

        $product = Product::find($product_id);

        $pay_system_id = NULL;
        $pay_system = PaySystem::where('alias', $pay_system_alias)->first();
        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        $pay = $payCreateIntoBot->handle($bot_user, $product, $payGetAdditionalData->handle($pay_system_id));

        $products = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => '1',
            'tax' => [
                'paymentMethod' => $bot->prodamus_payment_method->code,
                'paymentObject' => $bot->prodamus_payment_object->code
            ]];

        $Aproducts[] = $products;

        $data = ['order_id'=>$pay->id, 'customer_email' => $bot_user->email, 'products' => $Aproducts, 'do' => 'pay',
            'urlNotification' => env('APP_URL').'/prodamus/callback',
            'urlSuccess' => env('APP_URL').'/thank-you/'.$bot->id,
            'sys' => $bot->prodamus_sys,'discount_value' => 0.00,
            'npd_income_type' => $bot->prodamus_npd_income_type->code,
            'callbackType' => 'json',
            'type' => 'service'];

        $data['client_id'] = $bot_user->id;
        $data['return_all_methods'] = 1;

        $HMACController = new HMACController();
        $data['signature'] = $HMACController->create($data, $bot->prodamus_key);
        $link = sprintf('%s?%s', $bot->prodamus_url, http_build_query($data));

        return $link;
    }

    public function thank_you(int $bot_id) {
        $bot = Bot::select('alias')->find($bot_id);
        return view('core.pay.thank_you', ['bot' => $bot]);
    }

}
