<?php
namespace App\Actions\Core\VariablesSystem;

use App\Models\Core\VariablesSystem;

class SysVar
{
    public function handle($group_alias)
    {
        $variableGetValue = new VariablesSystemGetValue();

        $res = VariablesSystem::select('variable_groups.id','variables_systems.name', 'alias', 'value_string', 'value_integer', 'value_date', 'value_text')
            ->join('variable_groups', 'variable_groups.id', '=', 'variables_systems.variable_group_id')
            ->whereIn('alias', $group_alias)->get();


        $A = [];

        foreach ($res as $data) {
            (object)$value = $variableGetValue->handle($data);

            $A[$data->alias][$data->name] = $value['value'];
        }

        return (object)$A;
    }
}
