<?php
use App\Http\Controllers\Core\PayController;
use Illuminate\Support\Facades\Route;

Route::controller(YookassaController::class)->group(function() {
    Route::get('/pay/create/{pay_system_alias}/{bot_user_id}/{product_id}', 'create');
});
