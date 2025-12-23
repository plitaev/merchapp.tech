<?php
namespace App\Http\Controllers\Core;
use App\Models\Core\BotUserTicket;
use App\Models\Core\GetcourseWebhookTicket;
use Illuminate\Http\Request;

use App\Actions\Core\Pay\PayCreateIntoBot;
use App\Actions\Core\Pay\PayMakeSuccessful;

use App\Models\Core\BotUser;
use App\Models\Core\Product;

class PayCountController
{
    public function load() {
        return view('core.paycount.paycount');
    }

    public function load_post(Request $request) {
        $payCreateIntoBot = new PayCreateIntoBot();
        $payMakeSuccessful = new PayMakeSuccessful();

        $not_founds = [];
        $product = Product::find(8);
        $additional_data['pay_system_id'] = 3;

        $A = explode(PHP_EOL, $request->data);
        foreach ($A as $value) {
            $value = str_replace('\r', '', $value);
            $AA = explode(';', $value);

            $bot_user = BotUser::where('email', $AA[0])->where('bot_id', 25)->first();
            if ($bot_user) {
                $email = $AA[0];
                $phone = $AA[1];
                $price = round($AA[2], 0);

                if ($price == 400) $paycount = 1;
                if ($price == 1200) $paycount = 3;
                if ($price == 2000) $paycount = 5;
                if ($price == 2800) $paycount = 7;
                if ($price == 4000) $paycount = 10;
                if ($price == 6000) $paycount = 15;

                BotUser::where('id', $bot_user->id)->update(['pay_count' => $paycount, 'phone' => $phone]);

                $new_pay = $payCreateIntoBot->handle($bot_user, $product, $additional_data);
                $payMakeSuccessful->handle('{"source":"crafted_by_hand"}', $new_pay->id, 3, NULL, NULL);

            } else {
                $not_founds[] = $value;
            }
        }

        return view('core.paycount.result', ['results' => $not_founds]);
    }

    public function list() {
        $Atickets = [];
        $Abotusers = [];

        $tickets = BotUserTicket::orderBy('id')->get();
        foreach ($tickets as $ticket) {
            $Atickets[$ticket->bot_user_id][] = $ticket->id;
            $Abotusers[] = $ticket->bot_user_id;
        }

        $Abotusers = array_unique($Abotusers);

        $bot_users = BotUser::whereIn('id', $Abotusers)->where('bot_id', 25)->get();

        $big_number = BotUserTicket::select('id')->orderByDesc('id')->first();

        return view('core.paycount.list', ['bot_users' => $bot_users, 'tickets' => $Atickets, 'big_number' => $big_number]);
    }

    public function callback(string $email, string $phone, string $price) {
        $price = rawurldecode($price);
        $price = str_replace(' ', '', $price);
        $price = str_replace('руб.', '', $price);
        $price = str_replace('+', '', $price);

        GetcourseWebhookTicket::create(['email' => $email, 'phone' => $phone, 'price' => $price, 'status' => 0]);
    }

    public function callback_run() {
        $payCreateIntoBot = new PayCreateIntoBot();
        $payMakeSuccessful = new PayMakeSuccessful();

        $product = Product::find(8);
        $additional_data['pay_system_id'] = 3;

        $res = GetcourseWebhookTicket::where('status', 0)->where('email', 'I.I.kodorova@mail.ru')->get();

        foreach ($res as $data) {

            $bot_user = BotUser::where('email', $data->email)->where('bot_id', 25)->first();
            return $bot_user;

            if ($bot_user) {
                if ($data->price == 400) $paycount = 1;
                if ($data->price == 1200) $paycount = 3;
                if ($data->price == 2000) $paycount = 5;
                if ($data->price == 2800) $paycount = 7;
                if ($data->price == 4000) $paycount = 10;
                if ($data->price == 6000) $paycount = 15;

                BotUser::where('id', $bot_user->id)->update(['pay_count' => $paycount, 'phone' => $data->phone]);

                $new_pay = $payCreateIntoBot->handle($bot_user, $product, $additional_data);
                $payMakeSuccessful->handle('{"source":"crafted_by_hand"}', $new_pay->id, 3, NULL, NULL);

                GetcourseWebhookTicket::where('id', $data->id)->update(['status' => 1]);
            }

        }

    }

}
