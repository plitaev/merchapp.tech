<?php

namespace App\Actions\Core\MiniAppPage;

use App\Models\Core\MiniAppPage;

class MiniAppPageGetByURL
{
    public function handle() {
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/', '', $url);
        $url = explode('?', $url);
        $url = $url[0];

        return MiniAppPage::with('miniapp:id,class_id')->where('url', $url)->first();
    }
}
