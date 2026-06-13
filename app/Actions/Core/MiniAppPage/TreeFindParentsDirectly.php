<?php

namespace App\Actions\Core\MiniAppPage;

use App\Models\Core\MiniAppPage;

class TreeFindParentsDirectly
{
    public function handle($items, int $id) {
        $result = [];

        foreach ($items as $item) {

            if ($item->id == $id && $item->back_button_url) {
                $back_button_url = str_replace(env('APP_URL').'/', '', $item->back_button_url);
                $parent_page = MiniAppPage::select('id', 'back_button_url')->where('url', $back_button_url)->first();
                $result[] = $parent_page->id;
                if ($parent_page->back_button_url) $result[] = $this->handle($items, $parent_page->id);
            }

        }

        return $result;
    }
}
