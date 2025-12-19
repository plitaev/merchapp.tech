<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {
        $source = "out_summ=50.000000&OutSum=50.000000&inv_id=79&InvId=79&crc=0003046F45C02FE8749691DEDF154314&SignatureValue=0003046F45C02FE8749691DEDF154314&PaymentMethod=BankCard&IncSum=50.000000&IncCurrLabel=BankCardPSR&EMail=evgeniiplita@gmail.com&Fee=1.960000";

        $result = [];

        $A = explode('&', $source);
        foreach ($A as $value) {
            $AA = explode('=', $value);
            foreach ($AA as $k => $v) {
                $result[$k] = $v;
            }
        }

        $result = json_encode($result);

        return $result;

    }
}
