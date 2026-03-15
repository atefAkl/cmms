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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'temperature' => 'required|numeric',
            'humidity' => 'nullable|numeric',
        ]);

        $reading = TemperatureReading::create($validated);

        return response()->json([
            'message' => 'Temperature reading stored successfully',
            'data' => $reading
        ], 201);
    }
}
