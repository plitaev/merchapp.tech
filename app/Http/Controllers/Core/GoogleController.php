<?php
namespace App\Http\Controllers\Core;

use Revolution\Google\Sheets\Facades\Sheets;
use Carbon\Carbon;

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
        $date = date('Y-m-d', time());
        $datetime_start = $date.' 00:00:00';
        $datetime_end = $date.' 23:59:59';

        $sheet_name = 'Неудачные рекурентные списания';

        $result = [];

        $res = GetcourseEventWebhook::with('bot_user')
            ->where('event', 'recurrent_fail')
            ->where('created_at', '>=', $datetime_start)
            ->where('created_at', '<=', $datetime_end)
            ->get();

        foreach ($res as $data) {
            $A = [
                $data->email,
                (isset($data->bot_user->first_name) && $data->bot_user->first_name != 'none'?$data->bot_user->first_name:''),
                (isset($data->bot_user->last_name) && $data->bot_user->last_name != 'none'?$data->bot_user->last_name:''),
                date('d.m.Y', strtotime($date)),
                (isset($data->bot_user->username) && $data->bot_user->username != 'none'?$data->bot_user->username:'')
            ];

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

    public function send_non_active() {
        $date = Carbon::now()->subDays(2)->format('Y-m-d');
        $datetime_start = $date.' 00:00:00';
        $datetime_end = $date.' 23:59:59';

        $sheet_name = 'Неактивные пользователи';

        $result = [];

        $res = BotUser::query()
            ->where('created_at', '>=', $datetime_start)
            ->where('created_at', '<=', $datetime_end)
            ->whereNotNull('email')
            ->whereNull('date_end')
            ->get();

        foreach ($res as $data) {
            $A = [
                (isset($data->email)?$data->email:''),
                ($data->first_name != 'none'?$data->first_name:''),
                ($data->last_name != 'none'?$data->last_name:''),
                date('d.m.Y', strtotime($data->date_end)),
                ($data->username != 'none'?$data->username:'')
            ];

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

    public function send_recurrent_plan() {
        $date_next = Carbon::now()->addMonth(1)->format('Y-m-d');
        return $date_next;

        $sheet_name = 'План рекуррентов';

        $result = [];

        $res = BotUser::query()
            ->where('date_end', '>=', $date_start)
            ->where('date_end', '<=', $date_end)
            ->where('recurrent', 1)
            ->orderBy('date_end')
            ->get();

        foreach ($res as $data) {
            $A = [
                (isset($data->email)?$data->email:''),
                ($data->first_name != 'none'?$data->first_name:''),
                ($data->last_name != 'none'?$data->last_name:''),
                ($data->username != 'none'?$data->username:''),
                date('d.m.Y', strtotime($data->date_end))." 12:00:00"
            ];

            $result[] = $A;
        }

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet($sheet_name)->append($result);
    }

}
