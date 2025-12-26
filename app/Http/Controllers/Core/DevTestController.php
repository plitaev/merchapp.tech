<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;

class DevTestController extends Controller
{
    public function devtest() {
        $pays = Pay::with('bot_user')
            ->where('status', 1)
            ->where('created_at', '>=', '2025-11-25 00:00:00')
            ->where('created_at', '<=', '2025-12-25 23:59:59')
            ->orderByDesc('created_at')
            ->get();

        return view('core.devtest.devtest', ['pays' => $pays]);

        return GetcourseWebhookTicket::orderByDesc('created_at')->get();
    }

    public function paycounts() {

    }

}
