<?php
namespace App\Actions\Core\Prodamus;

use App\Http\Controllers\HMACController;

use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;

class ProdamusMakeRecurrent
{
    public function handle($data) {

        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();

        $additional_data = $payGetAdditionalData->handle($data->paysystem->id);
        $additional_data['recurrent'] = 1;
        $additional_data['price'] = $data->prevous_pay->price;

        $pay = $payCreateIntoBot->handle($data->bot_user, $data->product, $additional_data);
        if (!$pay) return ["new_pay_id" => NULL, "pay_system_responce" => '{"error":"prevous_pay_not_found"}'];

        $products = [
            'name' => 'Предоставление доступа к Мэджик клубу - Тариф "1 месяц"',
            'price' => 100,
            'quantity' => '1',
            'tax' => [
                'paymentMethod' => $data->bot->prodamus_payment_method->code,
                'paymentObject' => $data->bot->prodamus_payment_object->code
            ]];

        $Aproducts[] = $products;

        $data = ['binding_id' => $data->prevous_pay->pay_system_payment_method_id, 'client_id' => $data->bot_user_id, 'sys' => $data->bot->prodamus_sys, 'order_sum' => 100];

        $HMACController = new HMACController();
        $data['signature'] = $HMACController->create($data, $data->bot->prodamus_key_recurrent);

        $link = sprintf('%s?%s', $data->bot->prodamus_url.'rest/payment/do/', http_build_query($data));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($curl);
        curl_close($curl);

        $responce = json_decode($responce, true);

        return $responce;

        if ($responce['success'] == true) {

        }

    }
}
