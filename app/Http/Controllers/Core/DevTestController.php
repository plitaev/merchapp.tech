<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Http\Controllers\Controller;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserSupergroupStatus;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;


class DevTestController extends Controller
{
    public function devtest() {
    }
}
