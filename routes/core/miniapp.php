<?php
use App\Http\Controllers\Core\MiniAppPageController;

use Illuminate\Support\Facades\Route;

use App\Models\Core\MiniAppPage;

Route::controller(MiniAppPageController::class)->group(function() {

    $res = MiniAppPage::select('id', 'url')->get();
    foreach ($res as $data) {
        Route::get('/'.$data->url.'/'.$data->id, 'mini_app_banner_page');
    }

});
