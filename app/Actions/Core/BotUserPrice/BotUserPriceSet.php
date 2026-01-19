<?php

namespace App\Actions\Core\BotUserPrice;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;

class BotUserPriceSet
{
    public function handle($bot_user, $external_prices = []) {
        $products = Product::where('bot_id', $bot_user->bot_id)->get();
        $prices = BotUserPrice::select('product_id')->where('bot_user_id', $bot_user->id)->groupBy('product_id')->pluck('product_id')->toArray();

        foreach ($products as $product) {

            if (!in_array($product->id, $prices)) {
                $price = (isset($external_prices[$product->id])?$external_prices[$product->id]:$product->price);
                BotUserPrice::create(['bot_user_id' => $bot_user->id, 'product_id' => $product->id, 'price' => $price]);
            }

        }

    }
}
