<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotBranch\BotBranchEndByProducts;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetByID;
use App\Actions\Core\BotUser\BotUserSetBranch;
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
        $botUserSetBranch = new BotUserSetBranch();
        $dateEnd = new DateEnd();
        $referralBuySpecialProduct = new ReferralBuySpecialProduct();

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

        return $pay;

        //== Обрабатываем продукт в реферальной ветке
        $referralBuySpecialProduct->handle($pay);

        return '123';

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

    }
}
