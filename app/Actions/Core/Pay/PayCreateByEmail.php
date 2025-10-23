<?php
namespace App\Actions\Core\Pay;

use App\Actions\Core\BotBranch\BotBranchEndByProducts;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\DateEnd\DateEnd;

use App\Models\Core\BotUser;
use App\Models\Core\PayGuest;
use App\Models\Core\Product;

class PayCreateByEmail
{
    public function handle(string $email, int $product_id, int $recurrent, int $recurrent_status, int $days = 0, int $price = 0) {
        $botBranchEndByProducts = new BotBranchEndByProducts();
        $botSendMessage = new BotSendMessage();
        $dateEnd = new DateEnd();

        $product = Product::select('bot_id', 'price', 'days')->find($product_id);
        if (!$product) return "Продукт не найден";

        if ($days == 0) $days = $product->days;
        if ($price == 0) $price = $product->price;

        $bot_user = BotUser::where('email', $email)->where('bot_id', $product->bot_id)->first();

        if ($bot_user) {
            $new = \App\Models\Core\Pay::create([
                'product_id' => $product_id,
                'gift' => 0,
                'bot_user_id' => $bot_user->id,
                'price' => $price,
                'days' => $days,
                'status' => 1,
                'recurrent' => $recurrent,
                'recurrent_status' => $recurrent_status
            ]);

            $dateEnd->handle($bot_user, 'Y-m-d');

            //== Завершаем ветку по покупке продукта
            $botBranchEndByProducts->handle($product_id, $bot_user->id);

            $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');

            return $new;

        } else {
            $new = PayGuest::create([
                'product_id' => $product_id,
                'email' => $email,
                'price' => $price,
                'days' => $days,
                'gift' => 0,
                'status' => 0,
                'recurrent' => $recurrent,
                'recurrent_status' => $recurrent_status
            ]);

            return $new;

        }

    }

}
