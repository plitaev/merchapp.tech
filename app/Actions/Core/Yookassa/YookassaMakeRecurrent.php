<?php
namespace App\Actions\Core\Yookassa;
use YooKassa\Client;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserGetFullName;
use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayGetAdditionalData;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;

class YookassaMakeRecurrent
{
    public function handle($data) {
        $botSendMessage = new BotSendMessage();
        $botUserGetFullName = new BotUserGetFullName();
        $payCreateIntoBot = new PayCreateIntoBot();
        $payGetAdditionalData = new PayGetAdditionalData();
        $payMakeSuccessful = new PayMakeSuccessful();
        $yookassaMakeProductJSON = new YookassaMakeProductJSON();

        $client = new Client();
        $client->setAuth($data->bot->yookassa_shop_id, $data->bot->yookassa_shop_secret);

        $additional_data = $payGetAdditionalData->handle($data->paysystem->id);
        $additional_data['recurrent'] = 1;
        $additional_data['price'] = $data->prevous_pay->price;

        $pay = $payCreateIntoBot->handle($data->bot_user, $data->product, $additional_data);
        if (!$pay) return ["new_pay_id" => NULL, "pay_system_responce" => '{"error":"prevous_pay_not_found"}'];

        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $data->prevous_pay->price,
                    'currency' => $data->bot->yookassa_currency,
                ),
                'capture' => true,
                'payment_method_id' => $data->prevous_pay->pay_system_payment_method_id,
                'receipt' => array('customer' => array('full_name' => $botUserGetFullName->handle($data->bot_user), 'email' => $data->bot_user->email), 'items' => $yookassaMakeProductJSON->handle($data->bot, $data->product, $data->prevous_pay->price, false)),
                'description' => $data->bot_user->telegram_chat_id,
                'metadata' => ['orderNumber' => $pay->id]
            ),
            uniqid('', true)
        );

        if ($payment->status == "succeeded") {

            if (isset($payment->amount->value) && isset($payment->income_amount->value)) {
                $comission = $payment->amount->value-$payment->income_amount->value;
            } else {
                $comission = NULL;
            }

            $payMakeSuccessful->handle(json_encode($payment), $pay->id, $payment->id, $payment->payment_method->id, $comission);

            BotUserBanSchedule::where('bot_user_id', $data->bot_user_id)->where('run_status', 0)->update(['run_status' => 3]);

        } else {

            if ($payment->paid == false) {
                $bot_user = BotUser::find($data->bot_user_id);
                $botSendMessage->handle($bot_user, 'BOT_PAYMENT_RECURRENT_FAIL');
            }

        }

        return ['new_pay_id' => $pay->id, 'pay_system_responce' => json_encode($payment)];
    }
}
