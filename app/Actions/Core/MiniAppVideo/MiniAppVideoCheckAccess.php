<?php

namespace App\Actions\Core\MiniAppVideo;

use App\Models\Core\Bot;
use App\Models\Core\MiniAppVideo;

class MiniAppVideoCheckAccess
{
    public function handle($bot_user, $mini_app, $mini_app_page) {
        if (!$bot_user) {
            $bot = Bot::find($mini_app->bot_id);
            return view('project.app2.need_buy', ['bot' => $bot, 'mini_app_page' => $mini_app_page]);
        }

        if (!$bot_user->date_end) return view('project.app2.need_buy', ['bot' => $bot_user->bot, 'mini_app_page' => $mini_app_page]);

        if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 1) {
            if (!$bot_user) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            if ($bot_user->date_end < date('Y-m-d', time())) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            return 1;
        }

        if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 2) {
            if (!isset($bot_user->date_start) || !isset($bot_user->date_end)) return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            return 2;
        }

        if (isset($mini_app_page->mini_app_page_access_id) && $mini_app_page->mini_app_page_access_id == 3) {
            if ($bot_user->access_bonus != "member") return view('core.mini-app.access_denied', ['mini_app_page' => $mini_app_page]);
            return 3;
        }

        return NULL;
    }
}
