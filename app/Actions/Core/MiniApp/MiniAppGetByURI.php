<?php

namespace App\Actions\Core\MiniApp;

use App\Models\Core\MiniApp;

class MiniAppGetByURI
{
    public function handle() {
        return MiniApp::with('bot')->where('url', $_SERVER['REQUEST_URI'])->first();
    }
}
