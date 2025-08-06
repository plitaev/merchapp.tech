<?php
namespace App\Http\Controllers\Core;
use App\Http\Controllers\Controller;
use App\Models\Core\TelegramWebhook;

class DevTestController extends Controller
{
    public function devtest() {
        $res = TelegramWebhook::orderBy('created_at')->skip(0)->take(5)->get();
        foreach ($res as $data) {
            $res = TelegramWebhook::orderBy('created_at')->skip(0)->take(2)->get();
        }

        return $res;
    }
}
