<?php

use App\Http\Controllers\DashboardController;

Route::get('', fn () => redirect(route("admin.dashboard")));

Route::get('dashboard', [DashboardController::class, 'index'])->name("admin.dashboard");
Route::get('get-data/{action}', [DashboardController::class, 'getData']);
