<?php
namespace App\Http\Controllers\Core;
use App\Actions\Core\Auto\BotUserSetBanSchedulerCreate;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Core\GetCourseWebhook\GetCourseWebhookCreate;
use App\Actions\Core\Telegram\TelegramChatJoinRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\HMACController;
use App\Models\Core\Bot;
use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\Pay;
use App\Models\Core\Product;
use App\Models\Core\TelegramScheduleDeleteMessage;
use App\Models\Core\TelegramSendMessageLog;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramUnbanSchedule;
use App\Models\Core\TelegramWebhook;
use App\Models\Core\Sending;

use App\Actions\Core\BotSendMessage\BotSendMessage;

use App\Models\Core\User;
use YooKassa\Client;
use Telegram\Bot\Api;

use App\Models\Core\TelegramChatJoinRequestLog;
use App\Models\Core\GetcourseWebhook;

use Revolution\Google\Sheets\Facades\Sheets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Carbon\Carbon;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\RoleHasPermission;
use Spatie\Permission\Models\Permission;


class DevTestController extends Controller
{
    public function devtest() {

        $permissions = Permission::where('created_at', '>', '2025-11-08 11:11:00')->get();
        foreach ($permissions as $permission) {
            RoleHasPermission::create(['role_id' => 1, 'permission_id' => $permission->id]);
        }


        /*
        $result = [];

        $emails = BotUser::select('email')->groupBy('email')->orderBy('email')->pluck('email')->toArray();
        foreach ($emails as $email) {
            $chk = BotUser::where('email', $email)->count();
            if ($chk > 1) $result[] = $email;
        }

        return $result;
        */
        /*
        $bot_users = BotUser::select('id')->pluck('id')->toArray();
        $pays = Pay::select('bot_user_id')->whereIn('bot_user_id', $bot_users)->where('status', 1)->where('product_id', 27)->where('created_at', '>=', '2025-10-17 10:00:00')->pluck('bot_user_id')->toArray();
        $ids = Pay::select('bot_user_id')->whereIn('bot_user_id', $pays)->where('status', 1)->where('product_id', 3)->where('created_at', '>=', '2025-10-17 10:00:00')->pluck('bot_user_id')->toArray();
        $bot_users = BotUser::select('email')->whereIn('id', $ids)->orderBy('email')->pluck('email')->toArray();
        return implode('<br>', $bot_users);
        */
        /*
        $pays = Pay::with('bot_user')->with('product')->where('created_at', '>=', '2025-09-24 00:00:00')->where('status', 1)->get();
        return view('core.devtest.devtest', ['pays' => $pays]);
        */
        /*
        $dateEnd = new DateEnd(); /

        $bot_users = BotUser::where('run_status', 0)->take(1000)->get();
        foreach ($bot_users as $bot_user) {
            $dateEnd->handle($bot_user, 'Y-m-d');
            BotUser::where('id', $bot_user->id)->update(['run_status' => 1]);
        }
        */

        //$role = Role::create(['name' => 'bots']);
        //Permission::create(['name' => 'view bots']);
        //Permission::create(['name' => 'add bots']);
        //Permission::create(['name' => 'edit bots']);
        //Permission::create(['name' => 'delete bots']);

        //$user = User::find(1);
        //return $user->givePermissionTo('view bots');
        //return $user->permissions;

        //$bot_users = BotUser::select('id')->pluck('id')->toArray();
        //return BotUserUnbanSchedule::select('bot_user_id')->whereNotIn('bot_user_id', $bot_users)->pluck('bot_user_id')->toArray();

        /*
        $olds = Pay::select('bot_user_id')->where('status', 1)->where('created_at', '<=', '2025-10-17 10:00:00')->pluck('bot_user_id')->toArray();
        $pays = Pay::select('bot_user_id')->whereNotIn('bot_user_id', $olds)->where('status', 1)->where('product_id', 27)->where('created_at', '>=', '2025-10-17 10:00:00')->groupBy('bot_user_id')->pluck('bot_user_id')->toArray();
        return $pays;
        */
        /*
        $bot_users = BotUser::select('id')->pluck('id')->toArray();
        $pays = Pay::select('bot_user_id')->whereIn('bot_user_id', $bot_users)->where('status', 1)->where('product_id', 27)->where('created_at', '>=', '2025-10-17 10:00:00')->pluck('bot_user_id')->toArray();
        return Pay::whereIn('bot_user_id', $pays)->where('status', 1)->whereIn('product_id', [1, 2, 3])->where('created_at', '>=', '2025-10-17 10:00:00')->get();
        */
        //Выборки
        /*
        $bot_users = BotUser::select('id')->pluck('id')->toArray();
        $pays = Pay::select('bot_user_id')->where('status', 1)->where('product_id', 27)->where('created_at', '>=', '2025-10-17 10:00:00')->pluck('bot_user_id')->toArray();
        return $pays;
        */

        // === Не оплатившие из 17.10 - Рассылка на их третий день
        /*
        $datetime_start = '2025-10-17 00:00:00';
        $datetime_end = '2025-10-17 23:59:59';

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays)
            ->where('updated_at', '>=', $datetime_start)
            ->where('updated_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        return $bot_users;
        */
        // === Не оплатившие из 18.10 - Рассылка на их второй день
        /*
        $datetime_start = '2025-10-18 00:00:00';
        $datetime_end = '2025-10-18 23:59:59';

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays)
            ->where('updated_at', '>=', $datetime_start)
            ->where('updated_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        return $bot_users;
        */

        // === Купившие из 18.10 за 150 и не купившие полный - Рассылка на их третий день
        /*
        $datetime_start = '2025-10-18 00:00:00';
        $datetime_end = '2025-10-18 23:59:59';

        $pays_full = Pay::select('bot_user_id')->where('status', 1)->whereIn('product_id', [1, 2, 3])->where('created_at', '>=', $datetime_start)->pluck('bot_user_id')->toArray();

        $pays = Pay::select('bot_user_id')->where('status', 1)->where('product_id', 27)->whereNotIn('bot_user_id', $pays_full)->pluck('bot_user_id')->toArray();

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays_full)
            ->whereIn('id', $pays)
            ->where('created_at', '>=', $datetime_start)
            ->where('created_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        return $bot_users;
        */
        // === Купившие из 17.10 за 150 и не купившие полный - Рассылка на их третий день
        /*
        $datetime_start = '2025-10-17 00:00:00';
        $datetime_end = '2025-10-17 23:59:59';

        $pays_full = Pay::select('bot_user_id')->where('status', 1)->whereIn('product_id', [1, 2, 3])->where('created_at', '>=', $datetime_start)->pluck('bot_user_id')->toArray();

        $pays = Pay::select('bot_user_id')->where('status', 1)->where('product_id', 27)->whereNotIn('bot_user_id', $pays_full)->pluck('bot_user_id')->toArray();

        $bot_users = BotUser::select('id')
            ->where('bot_branch_id', 2)
            ->whereNotIn('id', $pays_full)
            ->whereIn('id', $pays)
            ->where('created_at', '>=', $datetime_start)
            ->where('created_at', '<=', $datetime_end)
            ->pluck('id')
            ->toArray();

        return $bot_users;
        */
    }
}
