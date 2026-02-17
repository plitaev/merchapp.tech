<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\App2Controller;

Route::controller(App2MaxController::class)->group(function() {
    Route::get('/app2max/{nordr?}', 'app2');
    Route::post('/pdf/rights_check', 'pdf_rights_check');
    Route::get('/pdf/access_denied/{bot_id}', 'access_denied');
    Route::get('/pdf/native/{pdf}', 'pdf_native');
});
