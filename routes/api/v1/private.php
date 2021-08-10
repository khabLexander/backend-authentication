<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\FileController;

Route::apiResource('users', UserController::class);

Route::apiResource('files', FileController::class)->except(['index', 'store']);

Route::prefix('user')->group(function () {
    Route::patch('destroys', [UserController::class, 'destroys']);
});

Route::prefix('user/{user}')->group(function () {
    Route::prefix('file')->group(function () {
        Route::get('', [UserController::class, 'indexFiles']);
        Route::post('', [UserController::class, 'uploadFile']);
    });
});

Route::prefix('file')->group(function () {
    Route::patch('destroys', [FileController::class, 'destroys']);
});

Route::prefix('file/{file}')->group(function () {
    Route::get('download', [FileController::class, 'download']);
});
