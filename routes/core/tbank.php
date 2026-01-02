<?php
use App\Http\Controllers\Core\TbankController;
use Illuminate\Support\Facades\Route;

Route::controller(TbankController::class)->group(function() {
    Route::post('/tbank/callback', 'callback');
});
