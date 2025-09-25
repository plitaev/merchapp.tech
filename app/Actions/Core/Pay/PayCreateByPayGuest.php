<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\DateEnd\DateEnd;

use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\Product;

class PayCreateByPayGuest
{
    public function handle($bot_user, string $email) {
        $botSendMessage = new BotSendMessage();
        $dateEnd = new DateEnd();

        $products = Product::select('id')->where('bot_id', $bot_user->bot_id)->pluck('id')->toArray();

        $res = PayGuest::whereIn('product_id', $products)->where('email', $email)->where('status', 0)->get();
        return implode(', ',$products);
        foreach ($res as $data) {
            Pay::insert(
                [
                    'product_id' => $data->product_id,
                    'gift' => $data->gift,
                    'bot_user_id' => $bot_user->id,
                    'price' => $data->price,
                    'days' => $data->days,
                    'status' => 1,
                    'recurrent' => $data->recurrent,
                    'recurrent_status' => $data->recurrent_status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ]
            );

            PayGuest::where('id', $data->id)->update(['status' => 1]);
        }

        if (count($res) > 0) {
            $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
        }

        $dateEnd->handle($bot_user, 'Y-m-d');

    }
}
