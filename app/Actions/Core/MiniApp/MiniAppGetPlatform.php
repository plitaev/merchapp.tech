<?php

namespace App\Actions\Core\MiniApp;

class MiniAppGetPlatform
{
    public function handle() {

        if (isset($_GET['platform'])) {
            $platform = str_replace('?WebAppStartParam=', '', $_GET['platform']);
        } else {
            $platform = 'telegram';
        }

        return $platform;
    }
}
