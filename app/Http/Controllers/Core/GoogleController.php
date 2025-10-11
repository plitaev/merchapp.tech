<?php
namespace App\Http\Controllers\Core;

use App\Models\Core\GetcourseEventWebhook;
use Revolution\Google\Sheets\Facades\Sheets;

use App\Models\Core\BotUser;

class GoogleController
{
    public function send_banneds() {
        $date_end = date('Y-m-d', time());
        $sheet_name = 'Отписавшиеся пользователи (удалены из клуба)';

        $result = [];

        $res = BotUser::where('date_end', $date_end)->get();
        foreach ($res as $data) {
            $A = [
                (isset($data->email)?$data->email:''),
                ($data->first_name != 'none'?$data->first_name:''),
                ($data->last_name != 'none'?$data->last_name:''),
                date('d.m.Y', strtotime($date_end)),
                ($data->username != 'none'?$data->username:'')
            ];

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

    public function send_recurrent_fail() {
        $date_end = '2025-10-10';
        $sheet_name = 'Неудачные  рекурентные списания';

        $result = [];

        $res = GetcourseEventWebhook::with('bot')->where('date_end', $date_end)->get();
        foreach ($res as $data) {
            $A = [
                (isset($data->bot->email)?$data->bot->email:''),
                ($data->bot->first_name != 'none'?$data->bot->first_name:''),
                ($data->bot->last_name != 'none'?$data->bot->last_name:''),
                date('d.m.Y', strtotime($date_end)),
                ($data->bot->username != 'none'?$data->bot->username:'')
            ];

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

}
