<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\HMACController;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\GetcourseWebhook;

use Revolution\Google\Sheets\Facades\Sheets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Carbon\Carbon;

class DevTestController extends Controller
{
    public function devtest() {

        $users = Pay::select('bot_user_id')->where('product_id', 27)->where('status', 1)->where('created_at', '>=', '2025-10-18 10:00:00')->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        $fulls = Pay::where('product_id', 1)->where('status', 1)->whereIn('bot_user_id', $users)->where('created_at', '>=', '2025-10-18 10:00:00')->count();

        /*
        $Aproducts = [];

        $products = [
            'name' => 'Акция',
            'price' => 150,
            'quantity' => '1',
            'tax' => [
                'paymentMethod' => 4,
                'paymentObject' => 4
            ]];

        $Aproducts[] = $products;

        $data = ['binding_id' => '0ea05b9efc8ff41556f0818f750c636e', 'client_id' => 7874, 'sys' => 'magiclife', 'order_sum' => 150];

        $HMACController = new HMACController();
        $data['signature'] = $HMACController->create($data, 'a46d78365afc4e146ed48c736d3ef106546dab3e516efd1d15c44ac2eaac15ac');

        $link = sprintf('%s?%s', 'https://formagiclife.payform.ru/rest/payment/do/', http_build_query($data));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($curl);
        curl_close($curl);

        return json_decode($responce, true);
        */
    }
}
