<?php
namespace App\Actions\Core\MiniAppBanner;

use App\Models\Core\BotUser;
use App\Models\Core\MiniAppBannerLinkPage;
use App\Actions\Core\MiniAppVideo\MiniAppVideoCheckAccess;

class MiniAppBannerListByClassID
{
    public function handle(int $mini_app_page_id, int $banner_class_id) {
        $miniAppVideoCheckAccess = new MiniAppVideoCheckAccess();

        $banners = MiniAppBannerLinkPage::query()
            ->with('miniapp')
            ->with('miniapp_page')
            ->with('miniapp_banner')
            ->with('miniapp_banner_class')
            ->whereHas('miniapp_banner_class', function ($query) use ($banner_class_id) {
                $query->where('banner_class_id', $banner_class_id);
            })
            ->where('mini_app_page_id', $mini_app_page_id)
            ->orderBy('pos')
            ->get();

        $result = [];

        foreach ($banners as $banner) {
            if ($banner->miniapp_banner->button_pdf) {

                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                $pdf = explode('/', $banner->miniapp_banner->button_pdf);
                $pdf = $pdf[1];
                $pdf = str_replace('.pdf', '', $pdf);

                if (preg_match('/macintosh|mac os/i', $user_agent) && !preg_match('/iPhone/i', $user_agent) && !preg_match('/iPad/i', $user_agent)) {
                    $button_url = '/pdf/native/'.$pdf;
                } elseif (preg_match('/iPhone OS 15/i', $user_agent) || preg_match('/iPhone OS 14/i', $user_agent) || preg_match('/iPhone OS 13/i', $user_agent) || preg_match('/iPad; CPU OS 15/i', $user_agent) || preg_match('/iPad; CPU OS 14/i', $user_agent) || preg_match('/iPad; CPU OS 13/i', $user_agent)) {
                    $button_url = env("APP_URL")."/content/miniapp_pdf/".$pdf.".pdf";
                } else {
                    $pdf = env("APP_URL")."/pdf/web/viewer.html?bot_id=".$banner->miniapp->bot_id."&doc=".$pdf;
                    $button_url = $pdf;
                }

            } else {
                $button_url = $banner->miniapp_banner->button_url;
            }
            $banner->miniapp_banner->button_url = $button_url;

            return $_GET['telegram_chat_id'];

            if (isset($_GET['telegram_chat_id']) && isset($banner->miniapp_banner->mini_app_page_with_video_id) && $banner->miniapp_banner->mini_app_page_with_video_show_banner == 0) {

                return $_GET['telegram_chat_id'];


                $bot_user = BotUser::with('bot')
                    ->select('id', 'bot_id', 'date_start', 'date_end', 'access_bonus')
                    ->where('telegram_chat_id', $_GET['telegram_chat_id'])
                    ->where('bot_id', $banner->miniapp->bot_id)
                    ->first();

                $restrict_access = $miniAppVideoCheckAccess->handle($bot_user, $banner->miniapp, $banner->miniapp_page);
                if (!$restrict_access) $result[] = $banner;

            } else {
                $result[] = $banner;
            }

        }

        return $result;
    }
}
