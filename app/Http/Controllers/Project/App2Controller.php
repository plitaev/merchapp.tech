<?php
namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Actions\Core\MiniApp\MiniAppGetByURI;
use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;

class App2Controller extends Controller
{
    public function app2(int $no_rdr = 0) {
        $miniAppGetByURI = new MiniAppGetByURI();
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        $mini_app = $miniAppGetByURI->handle();

        return view('project.app2.app2', [
            'mini_app' => $mini_app,
            'banners_big' => $miniAppBannerListByClassID->handle(5, 1),
            'banners_medium' => $miniAppBannerListByClassID->handle(5, 2),
            'no_rdr' => $no_rdr
        ]);
    }

}
