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
            'price' => 1490,
            'quantity' => '1',
            'tax' => [
                'paymentMethod' => 4,
                'paymentObject' => 4
            ]];

        $Aproducts[] = $products;

        $data = ['binding_id' => $result->binding_id, 'client_id' => $result->id, 'sys' => 'lvrse', 'order_sum' => 790];

        $HMACController = new HMACController();
        $data['signature'] = $HMACController->create($data, 'e395a7b4827a9a480afd68fdbc817caa420420cfb3490d71b49a6c71839f5ad9');

        $link = sprintf('%s?%s', 'https://missalena.payform.ru/rest/payment/do/', http_build_query($data));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($curl);
        curl_close($curl);

    }
}
