<?php

namespace App\Actions\Core\Pay;

class PayCreateIntoBot
{
    public function handle($bot_user, $product, $additional_data) {

        $price = (isset($additional_data['price'])?$additional_data['price']:$product->price);
        $recurrent = (isset($additional_data['recurrent'])?$additional_data['recurrent']:0);

        $new = \App\Models\Core\Pay::create([
            'product_id' => $product->id,
            'gift' => 0,
            'bot_user_id' => $bot_user->id,
            'price' => $price,
            'days' => $product->days,
            'status' => 0,
            'recurrent' => $recurrent,
            'recurrent_status' => 0,
            'pay_system_id' => $additional_data['pay_system_id'],
            'payed_at' => date('Y-m-d H:i:s', time())
        ]);

        return $new;
    }

}
