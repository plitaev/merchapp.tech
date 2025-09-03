<?php
namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;

class App1Controller extends Controller
{
    public function app1() {
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        return view('project.app1.app1', [
            'banners_big' => $miniAppBannerListByClassID->handle(6, 1),
            'banners_small' => $miniAppBannerListByClassID->handle(6, 3)
        ]);
    }

    public function app1_guides() {
        return view('project.app1.guides');
    }

}
