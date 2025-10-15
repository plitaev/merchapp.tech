<?php
use App\Http\Controllers\Core\ProdamusController;
use Illuminate\Support\Facades\Route;

Route::controller(ProdamusController::class)->group(function() {
    Route::post('/prodamus/callback', 'callback');
});
