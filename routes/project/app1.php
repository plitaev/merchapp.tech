<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\App1Controller;

Route::controller(App1Controller::class)->group(function() {
    Route::get('/app1', 'app1');
    Route::get('/app1_guides', 'app1_guides');
    Route::post('/pdf/app1_pdf_rights_check', 'app1_pdf_rights_check');
});
