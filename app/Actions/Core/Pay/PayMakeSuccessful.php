<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetByID;
use App\Actions\Core\BotUser\BotUserSetBranch;
use App\Actions\Core\DateEnd\DateEnd;

use App\Models\Core\BotBranch;
use App\Models\Core\BotBranchLinkProduct;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;

class PayMakeSuccessful
{
    public function handle(string $source, int $order_number, string $pay_system_payment_id, $pay_system_payment_method_id, $pay_system_comission) {
        $botSendMessage = new BotSendMessage();
        $botUserGetByID = new BotUserGetByID();
        $botUserSetBranch = new BotUserSetBranch();
        $dateEnd = new DateEnd();
        $dateEnd = new DateEnd();

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

        //== Завершаем ветку по покупке продукта

        $branches = BotBranchLinkProduct::select('bot_branch_id')
            ->where('product_id', $pay->product_id)
            ->where('bot_branch_link_product_type_id', 1)
            ->groupBy('bot_branch_id')
            ->pluck('bot_branch_id')
            ->toArray();

        if (count($branches) > 0) {
            BotUser::where('id', $pay->bot_user_id)->whereIn('bot_branch_id', $branches)->update(['bot_branch_id' => 1]);
        }

        //==

        if (isset($pay_system_payment_method_id)) {
            BotUser::where('id', $pay->bot_user_id)->update(['recurrent' => 1]);
        }

        if ($pay->recurrent == 1) {
            Pay::where('id', $order_number)->update(['recurrent_status' => 1]);
        }

        $dateEnd->handle($bot_user, 'Y-m-d');
        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');

    }
}
