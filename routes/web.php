<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BranchWebController;
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

    Route::resource('branches', BranchWebController::class);
    // UI Navigation Routes
    // Inventory & Procurement
    Route::resource('item-categories', \App\Http\Controllers\Web\ItemCategoryWebController::class);
    Route::resource('warehouses', \App\Http\Controllers\Web\WarehouseWebController::class);
    Route::resource('inventory-items', \App\Http\Controllers\Web\InventoryItemWebController::class);
    Route::resource('procurement', \App\Http\Controllers\Web\ProcurementWebController::class);
    Route::post('procurement/{procurement}/add-item', [\App\Http\Controllers\Web\ProcurementWebController::class, 'addItem'])->name('procurement.addItem');
    Route::post('procurement/{procurement}/approve', [\App\Http\Controllers\Web\ProcurementWebController::class, 'approve'])->name('procurement.approve');
    Route::post('procurement/{procurement}/reject', [\App\Http\Controllers\Web\ProcurementWebController::class, 'reject'])->name('procurement.reject');
    Route::post('procurement/{procurement}/receive', [\App\Http\Controllers\Web\ProcurementWebController::class, 'markAsReceived'])->name('procurement.receive');

    // Redirect legacy inventory to new generic items
    Route::get('/inventory', function () {
        return redirect()->route('inventory-items.index');
    });

    Route::resource('rooms', \App\Http\Controllers\Web\RoomWebController::class);
    Route::resource('maintenance', \App\Http\Controllers\Web\MaintenanceWebController::class);
    Route::resource('pm-schedules', \App\Http\Controllers\Web\PmScheduleWebController::class);
    Route::resource('alerts', \App\Http\Controllers\Web\AlertWebController::class);
    Route::resource('refrigeration-systems', \App\Http\Controllers\RefrigerationSystemController::class);
    Route::resource('assets', \App\Http\Controllers\AssetController::class);
    Route::resource('system-devices', \App\Http\Controllers\Web\SystemDeviceWebController::class);
    Route::resource('devices', \App\Http\Controllers\Web\DeviceWebController::class);
    Route::resource('room-layouts', \App\Http\Controllers\RoomLayoutController::class);
    Route::get('/settings', [\App\Http\Controllers\Web\SettingsWebController::class, 'index'])->name('settings.index');
});

require __DIR__ . '/auth.php';
