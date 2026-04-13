<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\BotSupergroup\BotSupergroupsAll;
use App\Actions\Core\BotUser\BotUserUnban;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\BotMessageButton;
use App\Models\Core\GetcourseWebhook;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramSupergroup;
use Carbon\Carbon;

use App\Http\Controllers\Controller;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserPrice;
use App\Models\Core\GetcourseWebhookTicket;
use App\Models\Core\Product;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

use App\Models\Core\Pay;
use App\Models\Core\PayGuest;
use App\Models\Core\PaySystemCallback;

use App\Actions\Core\DateEnd\DateEndNew;
use App\Actions\Core\DateEnd\DateEnd;
use App\Models\Core\TelegramBanScheduleLogs;

use App\Actions\Core\Max\MaxQuery;

use App\Models\Core\Bot;

class DevTestController extends Controller
{
    public function devtest() {

        $maxQuery = new MaxQuery();

        $res = TelegramSupergroup::where('bot_id', 9)->get();
        foreach ($res as $data) {
            $bot_user = BotUser::with('bot')->find(1841);

            $A = [];
            $A['user_ids'] = 'user_id_'.$bot_user->max_user_id;

            return json_encode($A);

            return $maxQuery->handle($bot_user->bot, 'POST', 'chats/'.$data->max_id.'/members', $A, false, ['user_id' => $bot_user->max_user_id]);
        }

        /*
        $telegram = new Api('7427797340:AAEZd2WfiGalZ7EvAdRv2yCNkgTDwM7nVhY');

        $A['text'] = 'Тест';
        $A['chat_id'] = 247632034;
        $A['parse_mode'] = 'HTML';
        $A['protect_content'] = false;

        return $telegram->sendMessage($A);
        */
        /*
        $headers = [
            'Content-Type: application/json',
            'Authorization: f9LHodD0cOLzjVI0rmnmVIJCE_3fDQvCRPUlLPf4r-7ofkDgoh7ouGs60-KEnWukmyZIsztJzsLqNB1MtWBp'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://platform-api.max.ru/videos/f9LHodD0cOKYxMdl1Mo1V5zovUykULdnadWoYX-arCYMh8wXjqj7TbcvaHI4YSA3f37aUJGflvdKEswqH-EI");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;


        $headers = [
            'Content-Type: application/json',
            'Authorization: f9LHodD0cOLzjVI0rmnmVIJCE_3fDQvCRPUlLPf4r-7ofkDgoh7ouGs60-KEnWukmyZIsztJzsLqNB1MtWBp'
        ];

        (string) $api_url = 'https://platform-api.max.ru/messages?user_id=5206051';

        $request_data = '
        {
  "text": "Это сообщение с кнопкой-ссылкой",
  "attachments": [
    {
      "type": "video",
  "payload": {
    "token": "f9LHodD0cOJZc0me8e6b2tEYCV6IsgF1mgmAGlT8wwPEZngYN84ZJqRsPCSIFkI7ZcBB9J7gSJ9COgIP1UAC",
    "caption": "Пример видео-сообщения"
  }
    }
  ]
}';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

        */
    }
}
