<?php
namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;

class App2Controller extends Controller
{
    public function app2(int $no_rdr = 0) {
        return $_SERVER['HTTP_HOST'];

        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        return view('project.app2.app2', [
            'banners_big' => $miniAppBannerListByClassID->handle(5, 1),
            'banners_medium' => $miniAppBannerListByClassID->handle(5, 2),
            'no_rdr' => $no_rdr
        ]);
    }

}
