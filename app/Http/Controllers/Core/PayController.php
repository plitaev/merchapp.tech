<?php
namespace App\Http\Controllers\Core;
use YooKassa\Client;

use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Http\Controllers\Core\HMACController;
use App\Models\Core\BotUserPrice;

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

        $product = Product::find($product_id);
        if ($product->enabled == 0) {
            return "Покупка продукта недоступна";
        }

        $botUserGetFullName = new BotUserGetFullName();
        $botUserPriceGet = new BotUserPriceGet();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();
        $yookassaMakeProductJSON = new YookassaMakeProductJSON();

        $bot_user = BotUser::find($bot_user_id);

        $prices = $botUserPriceGet->handle($bot_user, false);
        if (isset($prices[$product_id])) $product->price = $prices[$product_id];

        $pay_system_id = NULL;
        $pay_system = PaySystem::where('alias', $pay_system_alias)->first();
        if (isset($pay_system)) $pay_system_id = $pay_system->id;

        $pay = $payCreateIntoBot->handle($bot_user, $product, $payGetAdditionalData->handle($pay_system_id));

        if ($pay_system_alias == 'tbank') {

            $bot = Bot::query()
                ->with('tbank_taxation')
                ->with('tbank_tax')
                ->find($bot_user->bot_id);

            $price = $product->price;
            if (isset($bot_user->pay_count) && $bot_user->pay_count > 1) $price = $price * $bot_user->pay_count;

            $hash = $price.$product->description.$pay->id.$bot->tbank_terminal_password.'Y'.$bot->tbank_terminal_key;
            return $hash;
            $hash = hash('sha256', $hash);

            $json = '{"TerminalKey": "'.$bot->tbank_terminal_key.'","Amount": '.$price.'00,"OrderId": "'.$pay->id.'","Description": "'.$product->description.'","DATA": {"Email": "'.$bot_user->email.'"},"Receipt": {"Email": "'.$bot_user->email.'","Taxation": "'.$bot->tbank_taxation->code.'","Items": [{"Name": "'.$product->description.'","Price": '.$price.',"Quantity": 1,"Amount": "'.$price.'00","Tax": "'.$bot->tbank_tax->code.'"}]},"Token": "'.$hash.'","Recurrent":"Y"}';

            return $json;

            $curl=curl_init();
            curl_setopt($curl,CURLOPT_URL,"https://securepay.tinkoff.ru/v2/Init");
            curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$json);
            curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type: application/json']);
            $result = curl_exec($curl);
            curl_close($curl);

            return $result;

            $result = json_decode($result, true);
            return redirect($result['PaymentURL']);

        }

        if ($pay_system_alias == 'robokassa') {

            $bot = Bot::query()
                ->with('robokassa_tax')
                ->with('robokassa_payment_method')
                ->with('robokassa_payment_object')
                ->with('robokassa_vat')
                ->find($bot_user->bot_id);

            $price = $product->price;
            if (isset($bot_user->pay_count) && $bot_user->pay_count > 1) $price = $price * $bot_user->pay_count;

            $receipt='{"sno":"'.$bot->robokassa_tax->code.'","items": [{"name": "'.$product->description.'","quantity": 1,"sum": '.$price.',"payment_method": "'.$bot->robokassa_payment_method->code.'","payment_object": "'.$bot->robokassa_payment_object->code.'","tax": "'.$bot->robokassa_vat->code.'"}]}';
            $receipt = urlencode($receipt);

            $hash = $bot->robokassa_merchant_login.":".$price.":".$pay->id.":".$receipt.":".$bot->robokassa_merchant_password;

            $hash=md5($hash);

            return
                "<!DOCTYPE html>".
                "<html>".
                "<form action='https://auth.robokassa.ru/Merchant/Index.aspx' method=POST>".
                "<input type=hidden name=MerchantLogin value=".$bot->robokassa_merchant_login.">".
                "<input type=hidden name=OutSum value=".$price.">".
                "<input type=hidden name=InvId value=".$pay->id.">".
                "<input type=hidden name=Description value='".$product->description."'>".
                "<input type=hidden name=SignatureValue value='".$hash."'>".
                "<input type=hidden name=Email value=".$bot_user->email.">".
                "<input type=hidden name=ExpirationDate value=2025-12-31T23:59:59>".
                "<input type=hidden name=Receipt value='$receipt'>".
                "<input type=submit value='Оплатить' style='font-size: 36px'>".
                "</form></html>";

        }

        if ($pay_system_alias == 'prodamus') {

            $HMACController = new HMACController();

            $bot = Bot::query()
                ->with('prodamus_payment_method')
                ->with('prodamus_payment_object')
                ->with('prodamus_npd_income_type')
                ->with('prodamus_tax')
                ->find($bot_user->bot_id);

            $products = [
                'name' => $product->description,
                'price' => $product->price,
                'quantity' => '1',
                'tax' => [
                    'paymentMethod' => $bot->prodamus_payment_method->code,
                    'paymentObject' => $bot->prodamus_payment_object->code,
                    'tax_type' => $bot->prodamus_tax->code
                ]
            ];

            $Aproducts[] = $products;

            $data = ['order_id'=>$pay->id, 'customer_email' => $bot_user->email, 'products' => $Aproducts, 'do' => 'pay',
                'urlNotification' => env('APP_URL').'/prodamus/callback',
                'urlSuccess' => env('APP_URL').'/thank-you/'.$bot->id,
                'sys' => $bot->prodamus_sys,'discount_value' => 0.00,
                'npd_income_type' => $bot->prodamus_npd_income_type->code,
                'callbackType' => 'json',
                'available_payment_methods' => 'AC|ACUSDGTL|ACEURGTL|ACBYNGTL|ACkztjp|ACkz|ACUSDKB|ACEURKB',
                'type' => 'service'];

            $data['client_id'] = $bot_user->id;
            $data['return_all_methods'] = 1;

            $data['signature'] = $HMACController->create($data, $bot->prodamus_key);
            $link = sprintf('%s?%s', $bot->prodamus_url, http_build_query($data));

            return redirect($link);
        }

        if ($pay_system_alias == 'yookassa') {

            $bot = Bot::query()
                ->with('yookassa_tax_system_code')
                ->with('yookassa_vat_code')
                ->with('yookassa_payment_mode')
                ->with('yookassa_payment_subject')
                ->find($bot_user->bot_id);

            $client = new Client();
            $client->setAuth($bot->yookassa_shop_id, $bot->yookassa_shop_secret);

            $save_payment_method = ($bot->yookassa_recurrent == 1?true:false);

            $payment = $client->createPayment(array('amount' => array('value' => $product->price, 'currency' => $bot->yookassa_currency),
                'confirmation' => array('type' => 'redirect', 'return_url' => env("APP_URL").'/thank-you/'.$bot_user->bot_id),
                'save_payment_method' => $save_payment_method,
                'receipt' => array('customer' => array('full_name' => $botUserGetFullName->handle($bot_user), 'email' => $bot_user->email), 'items' => $yookassaMakeProductJSON->handle($bot, $product, $product->price, true)),
                'capture' => true,'description' => $bot_user->telegram_chat_id, 'metadata' => ['order_number' => $pay->id]),uniqid('', true));

            $confirmationUrl=$payment->getConfirmation()->getConfirmationUrl();

            return redirect($confirmationUrl);
        }
    }

    public function thank_you(int $bot_id) {
        $bot = Bot::select('alias')->find($bot_id);
        return view('core.pay.thank_you', ['bot' => $bot]);
    }

    public function tbank_test() {
        return view('core.tbank.test');
    }

}
