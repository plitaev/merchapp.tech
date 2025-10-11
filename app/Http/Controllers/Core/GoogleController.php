<?php
namespace App\Http\Controllers\Core;

use Revolution\Google\Sheets\Facades\Sheets;

use App\Models\Core\BotUser;

class GoogleController
{
    public function send_banneds() {
        $date_end = '2025-10-10';
        $sheet_name = 'Отписавшиеся пользователи (удалены из клуба)';

        $data = [];

        $res = BotUser::where('date_end', $date_end)->get();
        foreach ($res as $data) {
            $data[] = [
                (isset($data->email)?$data->email:''),
                ($data->first_name != 'none'?$data->first_name:''),
                ($data->last_name != 'none'?$data->last_name:''),
                date('d.m.Y', strtotime($date_end)),
                ($data->username != 'none'?$data->username:''),
            ];
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($data);
    }
}
