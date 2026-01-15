<?php
namespace App\Actions\Core\Robokassa;

use App\Actions\Core\BotUser\BotUserGetFullName;
use App\Actions\Core\BotUser\BotUserRepeatRecurrent;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Product;

class RobokassaMakeRecurrent
{
    public function handle($data) {
        $botUserPriceGet = new BotUserPriceGet();
        $botUserRepeatRecurrent = new BotUserRepeatRecurrent();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();
        $payMakeSuccessful = new PayMakeSuccessful();

        $prices = $botUserPriceGet->handle($data->bot_user, false);
        $product_for_recurrent = Product::select('recurrent_product_id')->find($data->prevous_pay->product_id);
        $product = Product::find($product_for_recurrent->recurrent_product_id);

        $additional_data = $payGetAdditionalData->handle($data->paysystem->id);
        $additional_data['recurrent'] = 1;
        $additional_data['price'] = $prices[$product->id];

        $pay = $payCreateIntoBot->handle($data->bot_user, $product, $additional_data);
        if (!$pay) return ["new_pay_id" => NULL, "pay_system_responce" => '{"error":"prevous_pay_not_found"}'];

        $receipt='{"sno":"'.$data->bot->robokassa_tax->code.'","items": [{"name": "'.$product->description.'","quantity": 1,"sum": '.$prices[$product->id].',"payment_method": "'.$data->bot->robokassa_payment_method->code.'","payment_object": "'.$data->bot->robokassa_payment_object->code.'","tax": "'.$data->bot->robokassa_vat->code.'"}]}';
        $receipt = urlencode($receipt);

        $hash = $data->bot->robokassa_merchant_login.":".$prices[$product->id].":".$pay->id.":".$receipt.":".$data->bot->robokassa_merchant_password;
        $hash=md5($hash);

        $curl = curl_init();

        $data = array(
            'MerchantLogin' => $data->bot->robokassa_merchant_login,
            'InvoiceID' => $pay->id,
            'PreviousInvoiceID' => $data->prevous_pay->pay_system_payment_method_id,
            'SignatureValue' => $hash
        );

        curl_setopt($curl, CURLOPT_URL, 'https://auth.robokassa.ru/Merchant/Recurring');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        curl_close($curl);

        $payment = '{"responce":"'.$result.'"}';

        return ['new_pay_id' => $pay->id, 'pay_system_responce' => json_encode($payment)];
    }
}
