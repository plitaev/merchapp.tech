<?php
use App\Http\Controllers\Core\PayController;
use Illuminate\Support\Facades\Route;

Route::controller(PayController::class)->group(function() {
    Route::get('/pay/create/{pay_system_alias}/{bot_user_id}/{product_id}', 'create');
    Route::get('/pay/create_prodamus/{pay_system_alias}/{bot_user_id}/{product_id}', 'create_prodamus');
    Route::get('/thank-you/{bot_id}', 'thank_you');
});
