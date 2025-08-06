<?php
namespace App\Actions\Core\MiniAppBanner;

use App\Models\Core\MiniAppBannerLinkPage;

class MiniAppBannerBuildPosList
{
    public function handle(int $mini_app_page_id, int $banner_id) {
        $res = MiniAppBannerLinkPage::with('miniapp_banner')->where('mini_app_page_id', $mini_app_page_id)->orderBy('pos')->get();
        $last_pos = 0;

        $k = [];
        $v = [];

        foreach ($res as $data) {
            $k[] = $data->pos;
            $v[] = $data->pos." - ".$data->miniapp_banner->name;
            $last_pos = $data->pos;
        }

        if ($banner_id==0) {
            $next_pos = $last_pos + 1;
            $k[] = $next_pos;
            $v[] = $next_pos.' - Новый баннер';
        } else {
            $next_pos = 1;
        }

        $result = array_combine($k, $v);

        return [$result, $next_pos];
    }
}
