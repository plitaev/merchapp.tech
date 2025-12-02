<?php

namespace App\Actions\Core\BotUserPrice;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;

class BotUserPriceSet
{
    public function handle($bot_user) {
        $products = Product::all();
        $prices = BotUserPrice::select('product_id')->where('bot_user_id', $bot_user->id)->groupBy('product_id')->pluck('product_id')->toArray();

        foreach ($products as $product) {

            if (!in_array($product->id, $prices)) {
                BotUserPrice::create(['bot_user_id' => $bot_user->id, 'product_id' => $product->id, 'price' => $product->price]);
            }

        }

    }
}
