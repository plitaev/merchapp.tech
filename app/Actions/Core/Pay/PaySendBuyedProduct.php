<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotBranch\BotBranchEndByProducts;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetByID;
use App\Actions\Core\BotUser\BotUserSetBranch;
use App\Actions\Core\BotUserPrice\BotUserPriceSet;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\ReferralProgram\ReferralBuySpecialProduct;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;


class PaySendBuyedProduct
{
    public function handle(string $email, int $product_id, int $pay_price_rub)
    {
        $bot_user_id = BotUser::where('email', $email)->first()->id;
        $products = Product::where('id',$product_id)->get();

        foreach ($products as $product) {
            if (isset($product->external_id) && isset($product->external_api_url)) {

               // $data = array('email' => $email, "bot_user_id" => $bot_user_id,'product_id' => $product->id, 'pay_price_rub' => "'.$pay_price_rub.'", 'app_url' => "https://loverse.me");
                $json = '[{"email":"'.$email.'","product_id":"'.$product->id.'","bot_user_id":"'.$bot_user_id.'","pay_price_rub":"'.$pay_price_rub.'","app_url":"https://loverse.me"}]';

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://loverse.me/shop/pay_product');
                curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl,CURLOPT_ENCODING, '');
                curl_setopt($curl,CURLOPT_MAXREDIRS, 10);
                curl_setopt($curl,CURLOPT_TIMEOUT, 0);
                curl_setopt($curl,CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($curl,CURLOPT_POSTFIELDS, $json);
                curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type: application/json', 'Accept: application/json']);
                $result = curl_exec($curl);

                curl_close($curl);



               
            }
        }
    }

}
