<?php

namespace App\Actions\Core\MiniAppPage;

use Illuminate\Support\Collection;

class TreeBuildItemPageNavigator
{
    public function handle($items) {
        $item_by_id = collect();
        foreach ($items as $item) $item_by_id->put($item->id, $item);

        foreach ($items as $key => $item) {
            $item_by_id->get($item->id)->children = new Collection;
            if ($item->parent_id != 0) {

                if (isset($item_by_id->get($item->parent_id)->children)) {
                    $item_by_id->get($item->parent_id)->children->push($item);
                    unset($items[$key]);
                }

            }
        }

        return $items;
    }
}
