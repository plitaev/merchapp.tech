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
    public function handle(string $email, int $product_id)
    {
        $products = Product::where('id',$product_id)->get();
        foreach ($products as $product) {
            if (isset($product->external_id) && isset($product->external_api_url)) {

                $data = array('email' => $email, 'product_id' => $product->id);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://loverse.me/shop/pay_product');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $responce = curl_exec($curl);
                
                curl_close($curl);



               
            }
        }
    }

}
