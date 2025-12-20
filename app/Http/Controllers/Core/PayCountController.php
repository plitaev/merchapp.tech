<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\BotUser;
use Illuminate\Http\Request;

class PayCountController
{
    public function load() {
        return view('core.paycount.paycount');
    }

    public function load_post(Request $request) {
        $not_founds = [];

        $A = explode(PHP_EOL, $request->data);
        foreach ($A as $value) {
            $value = str_replace('\r', '', $value);
            $AA = explode(';', $value);

            $bot_user = BotUser::where('email', $AA[0])->first();
            if ($bot_user) {
                $email = $AA[0];
                $price = round($AA[1], 0);
            } else {
                $not_founds[] = $value;
            }
        }

        return view('core.paycount.result', ['results' => $not_founds]);
    }

}
