<?php

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\AuthorsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

Route::get('init', function (Request $request) {

    if (env('APP_ENV') != 'local') {
        return response()->json('El sistema se encuentra en producción.', 500);
    }

    DB::select('drop schema if exists public cascade;');
    DB::select('drop schema if exists authentication cascade;');
    DB::select('drop schema if exists app cascade;');

    DB::select('create schema authentication;');
    DB::select('create schema app;');
    DB::select('create schema job_board;');

    Artisan::call('migrate', ['--seed' => true]);

    Artisan::call('passport:client', [
        '--password' => true,
        '--name' => 'Password-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    Artisan::call('passport:client', [
        '--personal' => true,
        '--name' => 'Client-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    $clientSecret = DB::select("select secret from oauth_clients where name='" . 'Password-' . $request->input('client_name') . "'");

    return response()->json([
        'msg' => [
            'Los esquemas fueron creados correctamente.',
            'Las migraciones fueron creadas correctamente',
            'Cliente para la aplicación creado correctamente',
        ],
        'client' => $clientSecret[0]->secret
    ]);
});


