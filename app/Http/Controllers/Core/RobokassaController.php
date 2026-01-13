<?php
namespace App\Http\Controllers\Core;

use App\Models\Core\Bot;
use App\Models\Core\Pay;
use App\Models\Core\Product;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class RobokassaController
{
    public function callback() {
        $payMakeSuccessful = new PayMakeSuccessful();
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');

        $result_k = [];
        $result_v = [];

        $A = explode('&', $source);
        foreach ($A as $value) {
            $AA = explode('=', $value);
            foreach ($AA as $k => $v) {
                if ($k == 0) $result_k[] = $v;
                if ($k == 1) $result_v[] = $v;
            }
        }

        $result = array_combine($result_k, $result_v);
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        $paySystemCallbackCreate->handle($result, 'robokassa');

        $requestBody = json_decode($result, true);

        $check_pay = Pay::where('id', $requestBody['inv_id'])->where('status', 1)->first();

        if (!$check_pay) {
            $payMakeSuccessful->handle($result, $requestBody['inv_id'], $requestBody['SignatureValue'], $requestBody['inv_id'], $requestBody['Fee']);
        }

    }

    public function callback_fail() {
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');

        $result_k = [];
        $result_v = [];

        $A = explode('&', $source);
        foreach ($A as $value) {
            $AA = explode('=', $value);
            foreach ($AA as $k => $v) {
                if ($k == 0) $result_k[] = $v;
                if ($k == 1) $result_v[] = $v;
            }
        }

        $result = array_combine($result_k, $result_v);
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        $paySystemCallbackCreate->handle($result, 'robokassa');
    }

    public function robokassa_recurrent() {

        $bot = Bot::query()
            ->with('robokassa_tax')
            ->with('robokassa_payment_method')
            ->with('robokassa_payment_object')
            ->with('robokassa_vat')
            ->find(2);

        $product = Product::find(6);
        $pay = Pay::find(28034);

        $price = 50;
        if (isset($bot_user->pay_count) && $bot_user->pay_count > 1) $price = $price * $bot_user->pay_count;

        $receipt='{"sno":"'.$bot->robokassa_tax->code.'","items": [{"name": "'.$product->description.'","quantity": 1,"sum": '.$price.',"payment_method": "'.$bot->robokassa_payment_method->code.'","payment_object": "'.$bot->robokassa_payment_object->code.'","tax": "'.$bot->robokassa_vat->code.'"}]}';
        $receipt = urlencode($receipt);

        $hash = $bot->robokassa_merchant_login.":".$price.":".$pay->id.":".$receipt.":".$bot->robokassa_merchant_password;
        $hash=md5($hash);

        return
            "<!DOCTYPE html>".
            "<html>".
            "<form action='https://auth.robokassa.ru/Merchant/Recurring' method='POST'>".
            "<input type='text' name='MerchantLogin' value='".$bot->robokassa_merchant_login."'>".
            "<input type='text' name='InvoiceID' value='".$pay->id."'>".
            "<input type='text' name='PreviousInvoiceID' value='28033'>".
            "<input type='text' name='SignatureValue' value='".$hash."'>".
            "<input type='text' name='OutSum' value='".$price."'>".
            "<input type='text' name='Receipt' value='".$receipt."'>".
            "<input type='text' name='Description' value='".$product->description."'>".
            "<input type='submit' value='Повторить платёж'>".
            "</form></html>";
    }

}
