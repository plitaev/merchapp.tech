<?php

namespace App\Http\Controllers\Core;

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
            return $value;
        }

    }

}
