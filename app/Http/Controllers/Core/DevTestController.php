<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {

        $A = [11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
        foreach ($A as $v) {
            $product_from = $v;
            $product_to = 26;

            Pay::where('product_id', $product_from)->update(['product_id' => $product_to]);
            BotUserPrice::where('product_id', $product_from)->delete();
            Product::destroy($product_from);
        }

    }
}
