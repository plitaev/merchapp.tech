<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;

class PayCountController
{
    public function load() {
        return view('core.paycount.paycount');
    }

    public function load_post(Request $request)
    {
        return $request;
    }

}
