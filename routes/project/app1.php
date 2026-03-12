<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\App1Controller;

Route::controller(App1Controller::class)->group(function() {
    Route::get('/app1_guides', 'app1_guides');
});
