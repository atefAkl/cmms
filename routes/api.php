<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\AlertController;
use App\Http\Controllers\API\MaintenanceTaskController;
use App\Http\Controllers\API\TemperatureReadingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/alerts', [AlertController::class, 'index']);
    
    // Maintenance Tasks (Technician Focus)
    Route::get('/maintenance-tasks', [MaintenanceTaskController::class, 'index']);
    Route::get('/maintenance-tasks/my', [MaintenanceTaskController::class, 'myTasks']);
    Route::patch('/maintenance-tasks/{maintenanceTask}/status', [MaintenanceTaskController::class, 'updateStatus']);
    Route::post('/maintenance-tasks/{maintenanceTask}/parts', [MaintenanceTaskController::class, 'addPart']);

    // Procurement (Manager Focus)
    Route::get('/procurement', [\App\Http\Controllers\API\ProcurementApiController::class, 'index']);
    Route::post('/procurement/{purchaseRecord}/approve', [\App\Http\Controllers\API\ProcurementApiController::class, 'approve']);
    Route::post('/procurement/{purchaseRecord}/reject', [\App\Http\Controllers\API\ProcurementApiController::class, 'reject']);

    // Inventory Lookup
    Route::get('/inventory', [\App\Http\Controllers\API\InventoryApiController::class, 'index']);
});

// Optionally public for IoT sensors
Route::post('/temperature-readings', [TemperatureReadingController::class, 'store']);
