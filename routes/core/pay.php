<?php
use App\Http\Controllers\Core\PayController;
use Illuminate\Support\Facades\Route;

Route::controller(YookassaController::class)->group(function() {
    Route::post('/pay/create/{pay_system_alias}/{product_id}', 'create');
});
