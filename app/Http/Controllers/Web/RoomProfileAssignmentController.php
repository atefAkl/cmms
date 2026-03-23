<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\RoomTemperatureAssignmentService;
use Illuminate\Http\Request;

class RoomProfileAssignmentController extends Controller
{
    protected $service;

    public function __construct(RoomTemperatureAssignmentService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'temperature_profile_id' => 'required|exists:temperature_profiles,id',
            'start_date' => 'required|date',
        ]);


        try {
            $this->service->assignProfile(
                $room,
                $validated['temperature_profile_id'],
                $validated['start_date'],
                null
            );
            return back()->with('success', 'Temperature Profile successfully assigned to Room.');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
