<?php

namespace App\Actions\Core\MiniAppPage;

class TreeBuildHTMLPageNavigator
{
    public function handle($items, $level, $Aids) {
        $level=$level+1;
        $html='';
        foreach($items as $item) {
            $Aids[$level][]=$item->pos;

            $Apos=[];
            for ($i = 1; $i <= $level; $i++) {
                $el=count($Aids[$i])-1;
                $Apos[]=$Aids[$i][$el];
            }

            $parent_id=(isset($item->parent_id)?$item->parent_id:0);
            $html.= "<ul class='ul-cmt'>";
            $html.= "<li class='li-cmt' style='margin-left: ".(10*count($Apos))."px'>
            <a href='/admin/reference-books/".$item->id."/materials'>".$item->name."</a>
            </li>";

            if (isset($item->children)) {
                $html.=$this->handle($item->children, $level, $Aids);
            }
            $html.= "</ul>";
        }
        return $html;
    }
}
