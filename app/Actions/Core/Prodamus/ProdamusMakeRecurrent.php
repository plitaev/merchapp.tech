<?php
namespace App\Actions\Core\Prodamus;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Http\Controllers\Core\HMACController;

use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\Product;

class ProdamusMakeRecurrent
{
    public function handle($data) {

        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();
        $payMakeSuccessful = new PayMakeSuccessful();

        $additional_data = $payGetAdditionalData->handle($data->paysystem->id);
        $additional_data['recurrent'] = 1;
        $additional_data['price'] = 100;

        $product = Product::find(1);

        $pay = $payCreateIntoBot->handle($data->bot_user, $product, $additional_data);
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

        $prodamus_data = [
            'binding_id' => $data->prevous_pay->pay_system_payment_method_id,
            'client_id' => $data->bot_user_id,
            'sys' => $data->bot->prodamus_sys,
            'order_sum' => 100,
            'order_id' => $pay->id
        ];

        $HMACController = new HMACController();
        $prodamus_data['signature'] = $HMACController->create($prodamus_data, $data->bot->prodamus_key_recurrent);

        $link = sprintf('%s?%s', $data->bot->prodamus_url.'rest/payment/do/', http_build_query($prodamus_data));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($curl);
        curl_close($curl);

        $responce_array = json_decode($responce, true);

        if ($responce_array['success'] == true) {
            $payMakeSuccessful->handle(json_encode($responce), $pay->id, NULL, $data->prevous_pay->pay_system_payment_method_id, NULL);
            BotUserBanSchedule::where('bot_user_id', $data->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);
        }

        return ['new_pay_id' => $pay->id, 'pay_system_responce' => json_encode($responce_array)];

    }
}
