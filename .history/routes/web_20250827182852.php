<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Test routes for base class functionality
Route::get('/test', [App\Http\Controllers\TestController::class, 'index'])->name('test.index');
Route::get('/test/error', [App\Http\Controllers\TestController::class, 'error'])->name('test.error');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
