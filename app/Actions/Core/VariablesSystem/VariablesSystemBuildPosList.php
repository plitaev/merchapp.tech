<?php
namespace App\Actions\Core\VariablesSystem;

class VariablesSystemBuildPosList
{
    public function handle(int $id, int $variables_group_id) {
        $res = \App\Models\Core\VariablesSystem::where('id', $id)->get();
        $last_pos = 0;

        $k = [];
        $v = [];

        foreach ($res as $data) {
            $k[] = $data->pos;
            $v[] = $data->pos." - ".$data->name;
            $last_pos = $data->pos;
        }

        if ($variables_group_id == 0) {
            $next_pos = $last_pos + 1;
            $k[] = $next_pos;
            $v[] = $next_pos.' - Новая переменная';
        } else {
            $next_pos = 1;
        }

        $result = array_combine($k, $v);

        return [$result, $next_pos];
    }
}
