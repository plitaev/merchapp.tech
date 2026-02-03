<?php
use App\Http\Controllers\Core\MiniAppPageController;

use Illuminate\Support\Facades\Route;

use App\Models\Core\MiniAppPage;

Route::controller(MiniAppPageController::class)->group(function() {

    $res = MiniAppPage::select('id', 'url')->get();
    foreach ($res as $data) {
        Route::get('/'.$data->url, 'mini_app_banner_page');
    }

    Route::get('/miniapp/player/{id}', 'mini_app_player_page');

});
