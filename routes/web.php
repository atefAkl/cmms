<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/reports/temperature', [\App\Http\Controllers\ReportController::class, 'exportTemperaturePDF'])->name('reports.temperature');
    Route::get('/reports/maintenance', [\App\Http\Controllers\ReportController::class, 'exportMaintenanceCSV'])->name('reports.maintenance');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // UI Navigation Routes
    Route::resource('rooms', \App\Http\Controllers\Web\RoomWebController::class);
    Route::resource('maintenance', \App\Http\Controllers\Web\MaintenanceWebController::class);
    Route::resource('pm-schedules', \App\Http\Controllers\Web\PmScheduleWebController::class);
    Route::resource('inventory', \App\Http\Controllers\Web\InventoryWebController::class);
    Route::resource('alerts', \App\Http\Controllers\Web\AlertWebController::class);
});

require __DIR__.'/auth.php';
