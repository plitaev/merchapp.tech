<?php

namespace App\Http\Controllers\Core;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;

class MiniAppPageController
{
    public function mini_app_banner_page(int $id) {
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();

        return view('core.app1.app1', [
            'banners_big' => $miniAppBannerListByClassID->handle($id, 1),
            'banners_middle' => $miniAppBannerListByClassID->handle($id, 2),
            'banners_small' => $miniAppBannerListByClassID->handle($id, 3)
        ]);
    }
}
