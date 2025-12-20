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

        return $request->data;

        $A = explode('\n', $request->data);
        return $A;

        foreach ($A as $v) {
            return $v;
        }

    }

}
