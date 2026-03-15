<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TemperatureReading;
use Illuminate\Http\Request;

class TemperatureReadingController extends Controller
{
    /**
     * Store a newly created temperature reading in storage.
     */
    public function store(Request $request, \App\Services\TemperatureService $service)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'refrigeration_system_id' => 'nullable|exists:refrigeration_systems,id',
            'temperature' => 'required|numeric',
        ]);

        $reading = $service->logTemperature(
            \App\Models\Room::findOrFail($validated['room_id']), 
            $validated['temperature'], 
            null, // System/IoT user
            $validated['refrigeration_system_id'] ?? null
        );

        event(new \App\Events\IoTTemperatureReceived($reading));

        return response()->json([
            'message' => 'Temperature reading stored and analyzed successfully',
            'data' => $reading
        ], 201);
    }
}
