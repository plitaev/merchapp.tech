<?php
namespace App\Http\Controllers\Core;

use Revolution\Google\Sheets\Facades\Sheets;

use App\Models\Core\BotUser;
use App\Models\Core\GetcourseEventWebhook;

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
        $date = '2025-10-10';
        $datetime_start = $date.' 00:00:00';
        $datetime_end = $date.' 23:59:59';

        $sheet_name = 'Неудачные рекурентные списания';

        $result = [];

        $res = GetcourseEventWebhook::with('bot')
            ->where('event', 'recurrent_fail')
            ->where('created_at', '>=', $datetime_start)
            ->where('created_at', '<=', $datetime_end)
            ->get();

        foreach ($res as $data) {
            $A = [
                (isset($data->bot->email)?$data->bot->email:''),
                ($data->bot->first_name != 'none'?$data->bot->first_name:''),
                ($data->bot->last_name != 'none'?$data->bot->last_name:''),
                date('d.m.Y', strtotime($date)),
                ($data->bot->username != 'none'?$data->bot->username:'')
            ];

            return $A;

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

}
