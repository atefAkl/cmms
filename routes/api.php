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
    Route::get('/maintenance-tasks', [MaintenanceTaskController::class, 'index']);
});

// Optionally public for IoT sensors
Route::post('/temperature-readings', [TemperatureReadingController::class, 'store']);
