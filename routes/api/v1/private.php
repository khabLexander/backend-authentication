<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ProjectsController;
use App\Http\Controllers\V1\AuthorsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('projects', ProjectsController::class);

Route::prefix('project')->group(function () {
    Route::prefix('{project}')->group(function () {
        Route::patch('state', [ProjectsController::class, 'updateState']);
    });
    Route::prefix('')->group(function () {
        Route::get('state', [ProjectsController::class, 'updateState']);
    });
});

Route::apiResource('projects.authors', AuthorsController::class);

Route::prefix('project/{project}/authors')->group(function () {
    Route::prefix('{author}')->group(function () {
        Route::patch('state', [ProjectsController::class, 'updateState']);
    });
    Route::prefix('')->group(function () {
        Route::get('state', [ProjectsController::class, 'updateState']);
    });
});


