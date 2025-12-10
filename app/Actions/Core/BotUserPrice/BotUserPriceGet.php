<?php

namespace App\Actions\Core\BotUserPrice;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;

class BotUserPriceGet
{
    public function handle($bot_user, bool $label): array
    {
        $individuals = [];
        $result = [];

        $bot_user_prices = BotUserPrice::select('price', 'product_id')->where('bot_user_id', $bot_user->id)->get();
        foreach ($bot_user_prices as $bot_user_price) {
            $individuals[] = $bot_user_price->product_id;
            $result[$bot_user_price->product_id] = $bot_user_price->price;
        }

        $products = Product::select('id', 'price')->whereNotIn('id', $individuals)->get();
        foreach ($products as $product) {
            $result[$product->id] = $bot_user_price->price.($label?' ₽':'');
        }

        return $result;
    }
}
