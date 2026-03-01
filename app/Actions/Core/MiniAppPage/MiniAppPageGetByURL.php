<?php

namespace App\Actions\Core\MiniAppPage;

use App\Models\Core\MiniAppPage;

class MiniAppPageGetByURL
{
    public function handle() {
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('/', '', $url);

        return MiniAppPage::with('miniapp:id,class_id')->select('id', 'mini_app_id', 'url', 'mini_app_page_access_id', 'back_button_url')->where('url', $url)->first();
    }
}
