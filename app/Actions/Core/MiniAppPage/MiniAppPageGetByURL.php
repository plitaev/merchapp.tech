<?php

namespace App\Actions\Core\MiniAppPage;

use App\Models\Core\MiniAppPage;

class MiniAppPageGetByURL
{
    public function handle() {
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/', '', $url);

        return MiniAppPage::select('id')->where('url', $url)->first();
    }
}
