<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\GetcourseWebhook;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\Pay;
use App\Models\Core\PayGuest;

use App\Actions\Core\Pay\PayCreateByEmail;
use App\Actions\Core\DateEnd\DateEnd;

class ConverterController extends Controller
{
    public function load_users() {
        /*
        $in_new = BotUser::select('telegram_chat_id')->pluck('telegram_chat_id')->toArray();

        //$res = DB::table('secondbot.telegram_chats')->where('chat_id', '>', 0)->get();
        $res = DB::table('secondbot.telegram_chats')->whereNotIn('chat_id', $in_new)->get();
        foreach ($res as $data) {

            if ($data->chat_id > 0) {
                BotUser::create([
                    'telegram_chat_id' => $data->chat_id,
                    'bot_id' => 1,
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                    'username' => $data->username,
                    'email' => $data->email,
                    'language_code' => $data->language_code
                ]);
            }
        }
        */
    }

    public function load_getcourse_webhooks() {
        /*
        $products = Product::all();
        $Aproducts = [];

        foreach ($products as $product) {
            $Aproducts[$product->days] = $product->id;
        }

        $res = DB::table('secondbot.getcourse_callback')->where('days', '>', 0)->where('created_at', '>', '2025-09-17 15:49:57')->get();
        foreach ($res as $data) {
            GetcourseWebhook::insert([
                'product_id' => $Aproducts[$data->days],
                'getcourse_id' => $data->getcourse_id,
                'name' => $data->name,
                'email' => $data->email,
                'recurrent' => $data->recurrent,
                'recurrent_status' => $data->recurrent_status,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ]);
        }
        */
    }

    public function create_pays_from_webhook() {
        /*
        $payCreateByEmail = new PayCreateByEmail();

        $res = GetcourseWebhook::where('created_at', '>', '2025-09-17 15:49:57')->get();
        foreach ($res as $data) {

            $bot_user = BotUser::where('email', $data->email)->first();
            $product = Product::select('bot_id', 'price', 'days')->find($data->product_id);

            if ($bot_user) {
                $new = \App\Models\Core\Pay::insert([
                    'product_id' => $data->product_id,
                    'gift' => 0,
                    'bot_user_id' => $bot_user->id,
                    'price' => $product->price,
                    'days' => $product->days,
                    'status' => 1,
                    'recurrent' => $data->recurrent,
                    'recurrent_status' => $data->recurrent_status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ]);
            } else {
                $new = PayGuest::insert([
                    'product_id' => $data->product_id,
                    'email' => $data->email,
                    'price' => $product->price,
                    'days' => $product->days,
                    'gift' => 0,
                    'status' => 0,
                    'recurrent' => $data->recurrent,
                    'recurrent_status' => $data->recurrent_status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ]);
            }
        }
        */
    }

    public function cache_date_end() {

        $dateEnd = new DateEnd();

        $res = BotUser::where('run_status', 0)->get();
        foreach ($res as $data) {
            $dateEnd->handle($data, 'Y-m-d');
            BotUser::where('id', $data->id)->update(['run_status' => 1]);
        }

    }

    public function clean() {
        /*
        DB::table('bot_message_button_clicks')->truncate();
        DB::table('bot_users')->truncate();
        DB::table('bot_user_ban_schedules')->truncate();
        DB::table('bot_user_unban_schedules')->truncate();
        DB::table('getcourse_event_webhooks')->truncate();
        DB::table('getcourse_webhooks')->truncate();
        DB::table('pays')->truncate();
        DB::table('pay_guests')->truncate();
        DB::table('telegram_ban_schedule_error_logs')->truncate();
        DB::table('telegram_ban_schedule_logs')->truncate();
        DB::table('telegram_chat_join_request_logs')->truncate();
        DB::table('telegram_chat_member_error_logs')->truncate();
        DB::table('telegram_chat_member_logs')->truncate();
        DB::table('telegram_schedule_delete_messages')->truncate();
        DB::table('telegram_schedule_edit_messages')->truncate();
        DB::table('telegram_send_message_error_logs')->truncate();
        DB::table('telegram_send_message_logs')->truncate();
        DB::table('telegram_send_message_schedules')->truncate();
        DB::table('telegram_unban_schedule_error_logs')->truncate();
        DB::table('telegram_unban_schedule_logs')->truncate();
        DB::table('telegram_webhooks')->truncate();
        */
    }

}
