<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned to the "api"
| middleware group. Make something great!
|
 */

Route::prefix('v1')->group(function () {
    // Test routes for API base class functionality
    Route::get('/test', [App\Http\Controllers\Api\V1\TestApiController::class, 'index'])->name('api.test.index');
    Route::get('/test/error', [App\Http\Controllers\Api\V1\TestApiController::class, 'error'])->name('api.test.error');
});
