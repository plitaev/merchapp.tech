<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\App2Controller;

Route::controller(App2Controller::class)->group(function() {
    Route::get('/app2/{nordr?}', 'app2');
    Route::post('/app2/pdf_rights_check', 'pdf_rights_check');
});
