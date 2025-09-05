<?php
namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Actions\Core\Bot\BotGetByID;
use App\Actions\Core\MiniApp\MiniAppGetByURI;
use App\Actions\Core\MiniAppBanner\MiniAppBannerListByClassID;
use App\Models\Core\BotUser;

class App2Controller extends Controller
{
    public function app2(int $no_rdr = 0) {
        $miniAppGetByURI = new MiniAppGetByURI();
        $miniAppBannerListByClassID = new MiniAppBannerListByClassID();

        $mini_app = $miniAppGetByURI->handle();
        return $mini_app;

        return view('project.app2.app2', [
            'mini_app' => $mini_app,
            'banners_big' => $miniAppBannerListByClassID->handle(5, 1),
            'banners_medium' => $miniAppBannerListByClassID->handle(5, 2),
            'no_rdr' => $no_rdr
        ]);
    }

    public function pdf_rights_check() {
        $source = file_get_contents('php://input');
        $params = json_decode($source, true);

        return BotUser::where('telegram_chat_id', $params['chat_id'])
            ->where('bot_id', $params['bot_id'])
            ->whereNotNull('date_end')
            ->where('date_end', '>=', date('Y-m-d'))
            ->count();
    }

    public function access_denied(int $bot_id) {
        $botGetByID = new BotGetByID();

        $bot = $botGetByID->handle($bot_id);

        return view("project.app2.access_denied", ['bot' => $bot]);
    }

    public function pdf_native(string $pdf) {
        return view("project.app2.pdf_native", ['pdf' => $pdf]);
    }

}
