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
        return __DIR__;

        if (file_exists(__DIR__.'/merchapp_local/club_access.php')) {
            return 'yes';
        } else {
            return 'no';
        }
    }
}
