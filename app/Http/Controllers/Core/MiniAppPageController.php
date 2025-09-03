<?php

namespace App\Http\Controllers\Core;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;
use App\Actions\Core\MiniAppPage\MiniAppPageGetByURL;

class MiniAppPageController
{
    public function mini_app_banner_page() {
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        $miniAppPageGetByURL = new MiniAppPageGetByURL();

        $mini_app_page = $miniAppPageGetByURL->handle();

        return view('core.mini-app.mini-app-banner-page', [
            'banners_big' => $miniAppBannerListByClassID->handle($mini_app_page->id, 1),
            'banners_middle' => $miniAppBannerListByClassID->handle($mini_app_page->id, 2),
            'banners_small' => $miniAppBannerListByClassID->handle($mini_app_page->id, 3)
        ]);

    }
}
