<?php

namespace App\Actions\Core\Pay;

use App\Actions\Core\DateEnd\DateEnd;

class PayCreateIntoBot
{
    public function handle($bot_user, $product) {
        $dateEnd = new DateEnd();

        $new = \App\Models\Core\Pay::create([
            'product_id' => $product->id,
            'gift' => 0,
            'bot_user_id' => $bot_user->id,
            'price' => $product->price,
            'days' => $product->days,
            'status' => 0,
            'recurrent' => 0,
            'recurrent_status' => 0
        ]);

        $dateEnd->handle($bot_user, 'Y-m-d');

        return $new;
    }
}
