<?php
namespace App\Actions\Core\MiniAppBanner;

use App\Models\Core\MiniAppBanner;
use App\Models\Core\MiniAppBannerLinkPage;

class MiniAppBannerSave
{
    public function handle(array $data) {

        if ($data["id"] > 0) {
            \App\Models\Core\MiniAppBanner::where('id', $data["id"])->update($data);
        } else {

            $posres = MiniAppBannerLinkPage::select('id', 'mini_app_banner_id', 'pos')->where('mini_app_page_id', $data['mini_app_page_id'])->where('pos', '>=', $data['pos'])->orderBy('pos')->get();
            $pos = $data['pos'];
            foreach ($posres as $posdata) {
                $pos = $pos + 1;
                MiniAppBannerLinkPage::where('id', $posdata->id)->update(['pos' => $pos]);
            }

            $databanner = $data;

            unset($databanner['mini_app_page_id']);
            unset($databanner['pos']);

            $new_banner = MiniAppBanner::create($data);

            MiniAppBannerLinkPage::create(['mini_app_page_id' => $data['mini_app_page_id'], 'mini_app_banner_id' => $new_banner->id, 'pos' => $data['pos']]);
        }
    }
}
