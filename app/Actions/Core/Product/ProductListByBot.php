<?php

namespace App\Actions\Core\Product;

use App\Models\Core\Product;

class ProductListByBot
{
    public function handle(int $bot_id) {
        return Product::select('id', 'name', 'description', 'days', 'price')->where('bot_id', $bot_id)->get();
    }
}
