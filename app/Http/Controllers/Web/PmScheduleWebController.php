<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PmSchedule;
use App\Models\Room;
use App\Models\Asset;
use App\Http\Requests\StorePmScheduleRequest;
use App\Http\Requests\UpdatePmScheduleRequest;
use Illuminate\Http\Request;

class PmScheduleWebController extends Controller
{
    public function index()
    {
        $schedules = PmSchedule::with('equipment')->latest()->paginate(10);
        return view('pm-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $rooms = Room::all();
        $assets = Asset::all();
        return view('pm-schedules.create', compact('rooms', 'assets'));
    }

    public function store(StorePmScheduleRequest $request)
    {
        PmSchedule::create($request->validated());
        return redirect()->route('pm-schedules.index')->with('success', 'PM Schedule created successfully.');
    }

    public function edit(PmSchedule $pm_schedule)
    {
        $rooms = Room::all();
        $assets = Asset::all();
        return view('pm-schedules.edit', [
            'schedule' => $pm_schedule,
            'rooms' => $rooms,
            'assets' => $assets
        ]);
    }

    public function update(UpdatePmScheduleRequest $request, PmSchedule $pm_schedule)
    {
        $pm_schedule->update($request->validated());
        return redirect()->route('pm-schedules.index')->with('success', 'PM Schedule updated successfully.');
    }

    public function destroy(PmSchedule $pm_schedule)
    {
        $pm_schedule->delete();
        return redirect()->route('pm-schedules.index')->with('success', 'PM Schedule deleted successfully.');
    }
}
