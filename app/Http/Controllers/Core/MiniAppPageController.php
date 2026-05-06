<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\Bot;
use App\Models\Core\MiniApp;
use Carbon\Carbon;

use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;
use App\Actions\Core\MiniAppPage\MiniAppPageGetByURL;
use App\Actions\Core\MiniApp\MiniAppGetPlatform;
use App\Actions\Core\MiniAppVideo\MiniAppVideoCheckAccess;

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
        $miniAppGetPlatform = new MiniAppGetPlatform();
        $miniAppVideoCheckAccess = new MiniAppVideoCheckAccess();

        $mini_app_page = $miniAppPageGetByURL->handle();
        $mini_app_platform = $miniAppGetPlatform->handle();

        if ($mini_app_page->redirect_to_page) {
            return view('core.mini-app.mini-app-redirect-to-page', ['mini_app_page' => $mini_app_page, 'mini_app_platform' => $mini_app_platform]);
        }

        $telegram_chat_id = (isset($_GET['telegram_chat_id'])?$_GET['telegram_chat_id']:0);
        $max_user_id = (isset($_GET['max_user_id'])?$_GET['max_user_id']:0);

        $mini_app = MiniApp::select('bot_id')->find($mini_app_page->mini_app_id);

        $bot_user = BotUser::with('bot')
            ->select('id', 'bot_id', 'date_start', 'date_end', 'access_bonus')
            ->where('max_user_id', $max_user_id)
            ->where('bot_id', $mini_app->bot_id)
            ->get();

        $banners_big = $miniAppBannerListByClassID->handle($mini_app_page->id, 1);
        $banners_medium = $miniAppBannerListByClassID->handle($mini_app_page->id, 2);
        $banners_small = $miniAppBannerListByClassID->handle($mini_app_page->id, 3);

        if ($mini_app_page->miniapp->class_id == 1) {

            if (count($banners_big) == 0 && count($banners_medium) == 0 && count($banners_small) == 0) {
                return view('project.app2.access_denied', ['mini_app_page' => $mini_app_page]);
            }

            return view('core.mini-app.mini-app-banner-page', [
                'banners_big' => $banners_big,
                'banners_medium' => $banners_medium,
                'banners_small' => $banners_small,
                'mini_app_page' => $mini_app_page,
                'mini_app_platform' => $mini_app_platform
            ]);
        }

        if ($mini_app_page->miniapp->class_id == 2) {

            return $max_user_id;

            $restrict_access = $miniAppVideoCheckAccess->handle($bot_user, $mini_app, $mini_app_page);
            if ($restrict_access) return $restrict_access;

            $video_ids = MiniAppVideoLinkPage::select('mini_app_video_id')->where('mini_app_page_id', $mini_app_page->id)->pluck('mini_app_video_id')->toArray();
            $videos = MiniAppVideo::whereIn('id', $video_ids)->orderByDesc('created_at')->get();

            if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 2) {
                $videos = MiniAppVideo::whereIn('id', $video_ids)
                    ->where('date_open', '>=', $bot_user->date_start)
                    ->where('date_open', '<=', $bot_user->date_end)
                    ->orderByDesc('created_at')
                    ->get();
            }

            return view('core.mini-app.mini-app-video-list-page', [
                'mini_app_page' => $mini_app_page,
                'mini_app_platform' => $mini_app_platform,
                'videos' => $videos
            ]);

        }
    }

    public function mini_app_player_page(int $id, string $messenger, int $messenger_user_id, string $back_page)
    {
        $mini_app_token = hash('sha256', microtime());
        $mini_app_token_expiration = Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s');

        BotUser::query()
            ->when($messenger == 'telegram', function($query) use ($messenger_user_id) {
                return $query->where('telegram_chat_id', $messenger_user_id);
            })
            ->when($messenger == 'max', function($query) use ($messenger_user_id) {
                return $query->where('max_user_id', $messenger_user_id);
            })
            ->update(['mini_app_token' => $mini_app_token, 'mini_app_token_expiration' => $mini_app_token_expiration]);

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
        $bot_user = BotUser::select('id', 'mini_app_token_expiration')->where('mini_app_token', $mini_app_token)->first();
        if ($bot_user) {

            if ($bot_user->mini_app_token_expiration > date('Y-m-d H:i:s')) {

                BotUser::where('id', $bot_user->id)->update(['mini_app_token_expiration' => date('Y-m-d H:i:s', time())]);

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
