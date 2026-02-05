<?php

namespace App\Http\Controllers\Core;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;
use App\Actions\Core\MiniAppPage\MiniAppPageGetByURL;
use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoLinkPage;

class MiniAppPageController
{
    public function mini_app_banner_page()
    {
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();
        $miniAppPageGetByURL = new MiniAppPageGetByURL();

        $mini_app_page = $miniAppPageGetByURL->handle();

        if ($mini_app_page->miniapp->class_id == 1) {
            return view('core.mini-app.mini-app-banner-page', [
                'banners_big' => $miniAppBannerListByClassID->handle($mini_app_page->id, 1),
                'banners_medium' => $miniAppBannerListByClassID->handle($mini_app_page->id, 2),
                'banners_small' => $miniAppBannerListByClassID->handle($mini_app_page->id, 3)
            ]);
        }

        if ($mini_app_page->miniapp->class_id == 2) {
            $video_ids = MiniAppVideoLinkPage::select('mini_app_video_id')->where('mini_app_page_id', $mini_app_page->id)->pluck('mini_app_video_id')->toArray();
            $videos = MiniAppVideo::whereIn('id', $video_ids)->get();

            return view('core.mini-app.mini-app-video-list-page', [
                'videos' => $videos
            ]);

        }
    }

    public function mini_app_player_page(int $id)
    {
        $video = MiniAppVideo::find($id);

        $master = file_get_contents($video->hls_url);
        $Amaster = explode(PHP_EOL, $master);

        return view('core.mini-app.mini-app-player-page', [
            'video' => $video
        ]);
    }
}
