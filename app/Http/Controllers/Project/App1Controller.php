<?php
namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;

class App1Controller extends Controller
{
    public function app1() {
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        return view('project.app1.app1', [
            'banners_big' => $miniAppBannerListByClassID->handle(1, 1),
            'banners_small' => $miniAppBannerListByClassID->handle(1, 3)
        ]);
    }

    public function app1_guides() {
        return view('project.app1.guides');
    }

    public function app1_pdf_rights_check() {
        $chat_id = implode(',', $_POST);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://kovalchuk.tech/app1/pdf_rights_check/".$chat_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
