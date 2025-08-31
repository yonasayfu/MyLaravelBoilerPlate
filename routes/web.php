<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Rbac\DashboardController as RbacDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RBAC Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rbac/dashboard', [RbacDashboardController::class, 'index'])
        ->middleware('can:view-reports')
        ->name('rbac.dashboard');
    Route::get('/rbac/roles/{roleName}', [RbacDashboardController::class, 'showRole'])
        ->middleware('can:view-roles')
        ->name('rbac.roles.show');
});

require __DIR__ . '/auth.php';
