<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\PermissionController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
});

Route::prefix('role/{role}')->group(function () {
    Route::post('give-permissions', [RoleController::class, 'givePermissions']);
    Route::post('sync-permissions', [RoleController::class, 'syncPermissions']);
    Route::patch('revoke-permissions', [RoleController::class, 'revokePermissions']);
});

Route::prefix('permission/{permission}')->group(function () {
    Route::post('assign-roles', [PermissionController::class, 'assignRoles']);
    Route::post('sync-roles', [PermissionController::class, 'syncRoles']);
    Route::patch('remove-roles', [PermissionController::class, 'removeRoles']);
});

Route::get('init', function () {
    if (env('APP_ENV') != 'local') {
        return response()->json('El sistema se encuentra en producción.', 500);
    }

    DB::select('drop schema if exists public cascade;');
    DB::select('drop schema if exists authentication cascade;');
    DB::select('drop schema if exists app cascade;');

    DB::select('create schema authentication;');
    DB::select('create schema app;');


    Artisan::call('migrate', ['--seed' => true]);

    return response()->json([
        'msg' => [
            'Los esquemas fueron creados correctamente.',
            'Las migraciones fueron creadas correctamente'
        ]
    ]);
})->withoutMiddleware(['auth:sanctum']);
