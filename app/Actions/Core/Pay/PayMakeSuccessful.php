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


class PayMakeSuccessful
{
    public function handle(string $source, int $order_number, $pay_system_payment_id, $pay_system_payment_method_id, $pay_system_comission)
    {
        $botBranchEndByProducts = new BotBranchEndByProducts();
        $botSendMessage = new BotSendMessage();
        $botUserGetByID = new BotUserGetByID();
        $botUserPriceSet = new BotUserPriceSet();
        $botUserSetBranch = new BotUserSetBranch();
        $dateEnd = new DateEnd();
        $referralBuySpecialProduct = new ReferralBuySpecialProduct();

        $Asource = json_decode($source, true);

        Pay::query()->with('bot_user')
            ->where('id', $order_number)
            ->update(
                [
                    'status' => 1,
                    'pay_system_callback' => $source,
                    'pay_system_payment_id' => $pay_system_payment_id,
                    'pay_system_payment_method_id' => $pay_system_payment_method_id,
                    'pay_system_comission' => $pay_system_comission,
                    'payed_at' => date('Y-m-d H:i:s', time())
                ]
            );

        $pay = Pay::find($order_number);
        $bot_user = $botUserGetByID->handle($pay->bot_user_id);

        BotUser::where('id', $bot_user->id)->whereNull('date_start')->update(['date_start' => date('Y-m-d', time())]);
        $dateEnd->handle($bot_user, 'Y-m-d');

        $botUserPriceSet->handle($bot_user);

        BotUser::where('id', $bot_user->id)->update(['recurrent_repeat' => 0]);
        BotUserRecurrentSchedule::where('bot_user_id', $bot_user->id)->where('run_status', 0)->update(['run_status' => 3]);

        //== Обрабатываем продукт в реферальной ветке
        $referralBuySpecialProduct->handle($pay);

        //== Завершаем ветку по покупке продукта
        $botBranchEndByProducts->handle($pay->product_id, $pay->bot_user_id);
        return '5';
        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
        //==

        if (isset($pay_system_payment_method_id)) {
            BotUser::where('id', $pay->bot_user_id)->update(['recurrent' => 1]);
        }

        if ($pay->recurrent == 1) {
            Pay::where('id', $order_number)->update(['recurrent_status' => 1]);
        }

        if (isset($Asource['maskedPan'])) {
            BotUser::where('id', $pay->bot_user_id)->update(['card_mask' => $Asource['maskedPan']]);
        }

        if (isset($Asource['Pan'])) {
            BotUser::where('id', $pay->bot_user_id)->update(['card_mask' => $Asource['Pan']]);
        }

        if (isset($Asource['IncCurrLabel'])) {
            BotUser::where('id', $pay->bot_user_id)->update(['card_mask' => $Asource['IncCurrLabel']]);
        }

        if (isset($Asource['object']['payment_method']['title'])) {
            $card_mask = '';

            if (isset($Asource['object']['payment_method']['card']['first6'])) {
                $card_mask = $Asource['object']['payment_method']['card']['first6'];
            }

            if (isset($Asource['object']['payment_method']['card']['last4'])) {
                $card_mask .= '******' . $Asource['object']['payment_method']['card']['last4'];
            }

            if ($card_mask == '') $card_mask = $Asource['object']['payment_method']['title'];

            BotUser::where('id', $pay->bot_user_id)->update(['card_mask' => $card_mask]);
        }

        $products = Product::where('id',$pay->product_id)->get();
        foreach ($products as $product) {
            if (isset($product->external_id) && isset($product->external_api_url)) {

                $data = array('email' => $pay->bot_user->email, 'product_id' => $product->id);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://loverse.me/shop/pay_product?email=' . $pay->bot_user->email.'&product_id=' . $pay->product_id   );
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $responce = curl_exec($curl);

                curl_close($curl);


            }
        }

    }

}
