<?php

namespace App\Actions\Core\MiniApp;

class MiniAppGetPlatform
{
    public function handle() {

        if (isset($_GET['platform'])) {

            $platform = str_replace('?WebAppStartParam=', '', $_GET['platform']);
            $A = json_decode($platform, true);

            $platform = (isset($A['platform'])?$A['platform']:'telegram');
        } else {
            $platform = 'telegram';
        }

        return $platform;
    }
}
