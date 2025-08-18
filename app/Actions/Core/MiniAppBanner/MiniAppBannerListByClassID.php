<?php
namespace App\Actions\Core\MiniAppBanner;

use App\Models\Core\MiniAppBannerLinkPage;

class MiniAppBannerListByClassID
{
    public function handle(int $mini_app_page_id, int $banner_class_id) {
        $banners = MiniAppBannerLinkPage::query()
            ->with('miniapp')
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
                $pdf = explode('/', $banner->miniapp_banner->button_pdf);
                $pdf = $pdf[1];
                $pdf = str_replace('.pdf', '', $pdf);
                $pdf = env("APP_URL")."/pdf/web/viewer.html?bot_id=".$banner->miniapp->bot_id."&doc=".$pdf;

                $button_url = $pdf;
            } else {
                $button_url = $banner->miniapp_banner->button_url;
            }
            $banner->miniapp_banner->button_url = $button_url;

            $result[] = $banner;
        }

        return $result;
    }
}
