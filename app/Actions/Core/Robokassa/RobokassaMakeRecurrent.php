<?php
namespace App\Actions\Core\Robokassa;

use App\Actions\Core\BotUser\BotUserGetFullName;
use App\Actions\Core\BotUser\BotUserRepeatRecurrent;
use App\Actions\Core\BotUserPrice\BotUserPriceGet;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Product;

class RobokassaMakeRecurrent
{
    public function handle($data) {
        $botUserPriceGet = new BotUserPriceGet();
        $botUserRepeatRecurrent = new BotUserRepeatRecurrent();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();
        $payMakeSuccessful = new PayMakeSuccessful();

        $prices = $botUserPriceGet->handle($data->bot_user, false);
        $product_for_recurrent = Product::select('recurrent_product_id')->find($data->prevous_pay->product_id);
        $product = Product::find($product_for_recurrent->recurrent_product_id);

        $additional_data = $payGetAdditionalData->handle($data->paysystem->id);
        $additional_data['recurrent'] = 1;
        $additional_data['price'] = $prices[$product->id];

        $pay = $payCreateIntoBot->handle($data->bot_user, $product, $additional_data);
        if (!$pay) return ["new_pay_id" => NULL, "pay_system_responce" => '{"error":"prevous_pay_not_found"}'];

        if ($payment->status == "succeeded") {

            if (isset($payment->amount->value) && isset($payment->income_amount->value)) {
                $comission = $payment->amount->value-$payment->income_amount->value;
            } else {
                $comission = NULL;
            }

            $payMakeSuccessful->handle(json_encode($payment), $pay->id, $payment->id, $payment->payment_method->id, $comission);

            BotUserBanSchedule::where('bot_user_id', $data->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);
            BotUserRecurrentSchedule::where('bot_user_id', $data->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);

        } else {

            if ($payment->paid == false) {
                $botUserRepeatRecurrent->handle($data);
            }

        }

        return ['new_pay_id' => $pay->id, 'pay_system_responce' => json_encode($payment)];
    }
}
