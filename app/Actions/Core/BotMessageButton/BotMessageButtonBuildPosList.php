<?php
namespace App\Actions\Core\BotMessageButton;

use App\Models\Core\BotMessageButton;

class BotMessageButtonBuildPosList
{
    public function handle(int $bot_message_id, $id) {
        $res = BotMessageButton::where('bot_message_id', $bot_message_id)->orderBy('pos')->get();
        $last_pos = 0;

        $k = [];
        $v = [];

        foreach ($res as $data) {
            $k[] = $data->pos;
            $v[] = $data->pos." - ".$data->name;
            $last_pos = $data->pos;
        }

        if ($id == 0) {
            $next_pos = $last_pos + 1;
            $k[] = $next_pos;
            $v[] = $next_pos.' - Новая кнопка';
        } else {
            $next_pos = 1;
        }

        $result = array_combine($k, $v);

        return [$result, $next_pos];
    }
}
