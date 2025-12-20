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

        $A = explode('\r\n', $request->data);
        foreach ($A as $v) {
            return $v;
        }

    }

}
