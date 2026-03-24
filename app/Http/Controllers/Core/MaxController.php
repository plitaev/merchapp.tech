<?php

namespace App\Http\Controllers\Core;

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;
use App\Models\Core\BotMessage;

class MaxController
{
    public function send_file(int $bot_id, int $bot_message_id) {

        $maxQuery = new MaxQuery();

        $bot_message = BotMessage::with('bot')->find($bot_message_id);

        $result = '';
        $type = '';
        $file = '';
        $mime_type = '';

        if ($bot_message->bot_message_type_id == 3) {
            $type = 'video';
            $file = $bot_message->video;
        }

        if ($bot_message->bot_message_type_id == 4) {
            $type = 'audio';
            $file = $bot_message->audio;
        }

        if ($bot_message->bot_message_type_id == 5) {
            $type = 'file';
            $file = $bot_message->file;
        }

        /*
        if ($type != '') {

            $file_path = public_path().'/content/'.$file;
            $mime_type = mime_content_type($file_path);

            $file_name = explode('/', $file);
            $file_name = $file_name[(count($file_name)-1)];

            $upload_url = $maxQuery->handle($bot_message->bot, 'POST', 'uploads', [], true, ['type' => $type]);
            $upload_url = $upload_url['url'];

            $cfile = curl_file_create(public_path().'/content/bot_message_videos/01KMFDV813EMZMHCJ1EV86CXVE.mp4', 'video/mp4', '01KMFDV813EMZMHCJ1EV86CXVE.mp4');

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $upload_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['data' => $cfile]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

        }

        return $result;
        */

        $maxQuery = new MaxQuery();

        $bot = Bot::find(2);
        $upload_url = $maxQuery->handle($bot, 'POST', 'uploads', [], true, ['type' => 'file']);
        $upload_url = $upload_url['url'];

        $cfile = curl_file_create(public_path().'/content/bot_message_videos/01KMFDV813EMZMHCJ1EV86CXVE.mp4', 'video/mp4', '01KMFDV813EMZMHCJ1EV86CXVE.mp4');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['data' => $cfile]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
