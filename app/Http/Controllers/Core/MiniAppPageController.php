<?php

namespace App\Http\Controllers\Core;

use Carbon\Carbon;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;
use App\Actions\Core\MiniAppPage\MiniAppPageGetByURL;

use App\Models\Core\BotUser;
use App\Models\Core\MiniAppVideo;
use App\Models\Core\MiniAppVideoLinkPage;
use App\Models\Core\MiniAppVideoTimePoint;

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

    public function mini_app_player_page(int $id, int $messenger_user_id)
    {
        $mini_app_token = hash('sha256', microtime());
        $mini_app_token_expiration = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        BotUser::where('telegram_chat_id', $messenger_user_id)->update(['mini_app_token' => $mini_app_token, 'mini_app_token_expiration' => $mini_app_token_expiration]);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os = "Unknown";

        if (strpos($user_agent, 'Win') !== false) {
            $os = "Windows";
        } elseif (strpos($user_agent, 'Macintosh') !== false) {
            $os = "Mac OS";
        } elseif (strpos($user_agent, 'Android') !== false) {
            $os = "Android";
        } elseif (strpos($user_agent, 'Linux') !== false) {
            $os = "Linux";
        } elseif (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false) {
            $os = "iOS";
        }

        $video = MiniAppVideo::find($id);

        $master = file_get_contents($video->edgecenter_hls_url);
        $Amaster = explode(PHP_EOL, $master);

        $tracks_edgecenter = [];
        $tracknames_edgecenter = [];

        foreach ($Amaster as $master) {
            if (substr($master, -4) == 'm3u8') {
                $tracks_edgecenter[] = $master;
            } else {

                if ($master != '#EXTM3U') {

                    $A1 = explode('RESOLUTION=', $master);
                    if (isset($A1[1])) {
                        $A2 = explode(',', $A1[1]);
                        return implode('||', $A2);
                        if (isset($A2[0])) {
                            $A3 = explode('x', $A2[1]);
                            if (isset($A3[1])) $tracknames_edgecenter[] = $A3[1].'p';
                        }
                    }

                }

            }
        }

        $timepoints = MiniAppVideoTimePoint::where('mini_app_video_id', $video->id)->get();

        return view('core.mini-app.mini-app-player-page', [
            'os' => $os,
            'mini_app_token' => $mini_app_token,
            'tracks_edgecenter' => $tracks_edgecenter,
            'tracknames_edgecenter' => $tracknames_edgecenter,
            'timepoints' => $timepoints,
            'video' => $video
        ]);
    }

    public function mini_app_player_external(int $id, string $mini_app_token)
    {
        $bot_user = BotUser::select('mini_app_token_expiration')->where('mini_app_token', $mini_app_token)->first();
        if ($bot_user) {

            if ($bot_user->mini_app_token_expiration > date('Y-m-d H:i:s')) {

                $video = MiniAppVideo::find($id);

                $master = file_get_contents($video->edgecenter_hls_url);
                $Amaster = explode(PHP_EOL, $master);

                $tracks_edgecenter = [];
                foreach ($Amaster as $master) {
                    if (substr($master, -4) == 'm3u8') $tracks_edgecenter[] = $master;
                }

                $timepoints = MiniAppVideoTimePoint::where('mini_app_video_id', $video->id)->get();

                return view('core.mini-app.mini-app-external-page', [
                    'tracks_edgecenter' => $tracks_edgecenter,
                    'timepoints' => $timepoints,
                    'video' => $video
                ]);

            } else {
                return "Токен истёк";
            }

        } else {
            return 'Отсутствует токен';
        }

    }

}
