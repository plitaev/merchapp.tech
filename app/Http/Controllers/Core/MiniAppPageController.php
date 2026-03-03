<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\MiniApp;
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

        $telegram_chat_id = (isset($_GET['telegram_chat_id'])?$_GET['telegram_chat_id']:0);

        $mini_app = MiniApp::select('bot_id')->find($mini_app_page->mini_app_id);
        $bot_user = BotUser::select('date_start', 'date_end', 'access_bonus')->where('telegram_chat_id', $telegram_chat_id)->where('bot_id', $mini_app->bot_id)->first();

        if ($mini_app_page->miniapp->class_id == 1) {
            return view('core.mini-app.mini-app-banner-page', [
                'banners_big' => $miniAppBannerListByClassID->handle($mini_app_page->id, 1),
                'banners_medium' => $miniAppBannerListByClassID->handle($mini_app_page->id, 2),
                'banners_small' => $miniAppBannerListByClassID->handle($mini_app_page->id, 3),
                'mini_app_page' => $mini_app_page
            ]);
        }

        if ($mini_app_page->miniapp->class_id == 2) {

            if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 2) {
                return $bot_user;
                if (!$bot_user) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
                if ($bot_user->date_end < date('Y-m-d', time())) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            }

            $video_ids = MiniAppVideoLinkPage::select('mini_app_video_id')->where('mini_app_page_id', $mini_app_page->id)->pluck('mini_app_video_id')->toArray();
            $videos = MiniAppVideo::whereIn('id', $video_ids)->get();

            if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 1) {

                if (!isset($bot_user->date_start) || !isset($bot_user->date_end)) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);

                $videos = MiniAppVideo::whereIn('id', $video_ids)
                    ->where('date_open', '>=', $bot_user->date_start)
                    ->where('date_open', '<=', $bot_user->date_end)
                    ->get();
            }

            if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 3) {
                if ($bot_user->access_bonus != "member") return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            }

            return view('core.mini-app.mini-app-video-list-page', [
                'mini_app_page' => $mini_app_page,
                'videos' => $videos
            ]);

        }
    }

    public function mini_app_player_page(int $id, int $messenger_user_id, string $back_page)
    {
        $mini_app_token = hash('sha256', microtime());
        $mini_app_token_expiration = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        BotUser::where('telegram_chat_id', $messenger_user_id)->update(['mini_app_token' => $mini_app_token, 'mini_app_token_expiration' => $mini_app_token_expiration]);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os = "Unknown";

        if (strpos($user_agent, 'Win') !== false) {
            $os = "Windows";
        } elseif (strpos($user_agent, 'Mac') !== false) {
            $os = "MacOS";
        } elseif (strpos($user_agent, 'Android') !== false) {
            $os = "Android";
        } elseif (strpos($user_agent, 'Linux') !== false) {
            $os = "Linux";
        } elseif (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false) {
            $os = "iOS";
        }

        $video = MiniAppVideo::find($id);

        if ($video->edgecenter_hls_url) {
            $master = file_get_contents($video->edgecenter_hls_url);
            $Amaster = explode(PHP_EOL, $master);
        } else {
            $Amaster = [];
        }

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
                        if (isset($A2[0])) {
                            $A3 = explode('x', $A2[0]);
                            if (isset($A3[1])) {
                                $tracknames_edgecenter[] = $A3[1].'p';
                            }
                        }
                    }

                }

            }
        }

        $timepoints = MiniAppVideoTimePoint::where('mini_app_video_id', $video->id)->get();

        return view('core.mini-app.mini-app-player-page', [
            'back_page' => $back_page,
            'mini_app_token' => $mini_app_token,
            'os' => $os,
            'timepoints' => $timepoints,
            'tracks_edgecenter' => $tracks_edgecenter,
            'tracknames_edgecenter' => $tracknames_edgecenter,
            'video' => $video
        ]);
    }

    public function mini_app_player_external(int $id, string $mini_app_token)
    {
        $bot_user = BotUser::select('mini_app_token_expiration')->where('mini_app_token', $mini_app_token)->first();
        if ($bot_user) {

            if ($bot_user->mini_app_token_expiration > date('Y-m-d H:i:s')) {

                $video = MiniAppVideo::find($id);

                if ($video->edgecenter_hls_url) {
                    $master = file_get_contents($video->edgecenter_hls_url);
                    $Amaster = explode(PHP_EOL, $master);
                } else {
                    $Amaster = [];
                }

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
                                if (isset($A2[0])) {
                                    $A3 = explode('x', $A2[0]);
                                    if (isset($A3[1])) {
                                        $tracknames_edgecenter[] = $A3[1].'p';
                                    }
                                }
                            }

                        }

                    }
                }

                $timepoints = MiniAppVideoTimePoint::where('mini_app_video_id', $video->id)->get();

                return view('core.mini-app.mini-app-external-page', [
                    'tracks_edgecenter' => $tracks_edgecenter,
                    'tracknames_edgecenter' => $tracknames_edgecenter,
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
