<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotBranch\BotBranchEndByProducts;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetByID;
use App\Actions\Core\BotUser\BotUserSetBranch;
use App\Actions\Core\BotUserPrice\BotUserPriceSet;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\ReferralProgram\ReferralBuySpecialProduct;

use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;

class PayMakeSuccessful
{
    public function handle(string $source, int $order_number, $pay_system_payment_id, $pay_system_payment_method_id, $pay_system_comission) {
        $botBranchEndByProducts = new BotBranchEndByProducts();
        $botSendMessage = new BotSendMessage();
        $botUserGetByID = new BotUserGetByID();
        $botUserPriceSet = new BotUserPriceSet();
        $botUserSetBranch = new BotUserSetBranch();
        $dateEnd = new DateEnd();
        $referralBuySpecialProduct = new ReferralBuySpecialProduct();

        $Asource = json_decode($source, true);

        Pay::query()
            ->where('id', $order_number)
            ->update(
                [
                    'status' => 1,
                    'pay_system_callback' => $source,
                    'pay_system_payment_id' => $pay_system_payment_id,
                    'pay_system_payment_method_id' => $pay_system_payment_method_id,
                    'pay_system_comission' => $pay_system_comission
                ]
            );

        $pay = Pay::find($order_number);
        $bot_user = $botUserGetByID->handle($pay->bot_user_id);

        $dateEnd->handle($bot_user, 'Y-m-d');

        $botUserPriceSet->handle($bot_user);

        //== Обрабатываем продукт в реферальной ветке
        $referralBuySpecialProduct->handle($pay);

        //== Завершаем ветку по покупке продукта
        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
        $botBranchEndByProducts->handle($pay->product_id, $pay->bot_user_id);
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

        if (isset($Asource['object']['payment_method']['title'])) {
            $card_mask = '';

            if (isset($Asource['object']['payment_method']['card']['first6'])) {
                $card_mask = $Asource['object']['payment_method']['card']['first6'];
            }

            if (isset($Asource['object']['payment_method']['card']['last4'])) {
                $card_mask = '******'.$Asource['payment_method']['card']['last4'];
            }

            if ($card_mask == '') $card_mask = $Asource['object']['payment_method']['title'];

            BotUser::where('id', $pay->bot_user_id)->update(['card_mask' => $card_mask]);
        }

    }
}
