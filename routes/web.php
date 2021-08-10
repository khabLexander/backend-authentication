<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;

Route::prefix('login')->group(function () {
    Route::get('{driver}', [AuthController::class, 'redirectToProvider']);
    Route::get('{driver}/callback', [AuthController::class, 'handleProviderCallback']);
});



