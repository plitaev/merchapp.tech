<?php
namespace App\Actions\Core\VariablesSystem;

class VariablesSystemGetValue
{
    public function handle($data) {
        $A=[];

        if (isset($data->value_string)) {
            $A['type']='string';
            $A['value']=$data->value_string;
        }

        if (isset($data->value_integer)) {
            $A['type']='integer';
            $A['value']=$data->value_integer;
        }

        if (isset($data->value_date)) {
            $A['type']='date';
            $A['value']=$data->value_date;
        }

        if (isset($data->value_text)) {
            $A['type']='text';
            $A['value']=$data->value_text;
        }

        return $A;
    }
}
