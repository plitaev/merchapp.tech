<?php

use App\Http\Controllers\Core\GetCourseController;
use Illuminate\Support\Facades\Route;

Route::controller(GetCourseController::class)->group(function() {
    Route::get('/getcourse_webhook/{product_id}/{getcourse_user_id}/{getcourse_user_name}/{email}/{is_recurrent}/{recurrent_status}', 'getcourse_webhook');
});
