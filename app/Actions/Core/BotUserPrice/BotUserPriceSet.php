<?php

namespace App\Actions\Core\BotUserPrice;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;

class BotUserPriceSet
{
    public function handle($bot_user) {
        $products = Product::all();

        foreach ($products as $product) {
            BotUserPrice::create(['bot_user_id' => $bot_user->id, 'product_id' => $product->id, 'price' => $product->price]);
        }

    }
}
