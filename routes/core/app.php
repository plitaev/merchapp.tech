<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Core\App\AppController;

Route::controller(AppController::class)->group(function() {
    Route::get('/app/preview/{mini_app_page_id}', 'mini_app_page_preview');
});
