<?php

namespace App\Actions\Core\MiniAppBanner;

class MiniAppBannerGetLinkForRecord
{
    public function handle($record) {
        if ($record->miniapp_banner->button_pdf) {

            $pdf = explode('/', $record->miniapp_banner->button_pdf);
            $pdf = $pdf[1];
            $pdf = str_replace('.pdf', '', $pdf);
            $pdf = env("APP_URL")."/pdf/web/viewer.html?doc=".$pdf;

            $button_url = $pdf;
        } else {
            $button_url = $record->miniapp_banner->button_url;
        }

        return $button_url;
    }
}
