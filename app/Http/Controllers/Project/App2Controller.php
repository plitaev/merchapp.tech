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

    public function pdf_rights_check() {
        $chat_id = implode(',', $_POST);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "/app2/pdf_rights_check");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
