<?php

namespace App\Actions\Core\MiniAppPage;

use App\Models\Core\MiniAppPage;

class MiniAppPageGetByURL
{
    public function handle() {
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/', '', $url);

        return MiniAppPage::with('mini_app:id,class_id')->select('id', 'mini_app_id')->where('url', $url)->first();
    }
}
