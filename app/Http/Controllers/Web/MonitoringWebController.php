<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\SystemSetting;
use App\Models\TemperatureReading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MonitoringWebController extends Controller
{
    public function temperature()
    {
        $rooms = Room::with(['activeProfileAssignment.profile', 'sensors' => function($query) {
            $query->latest('recorded_at');
        }])->get();

        return view('monitoring.temperature', compact('rooms'));
    }

    public function storeTemperature(Request $request)
    {
        $request->validate([
            'readings' => 'required|array',
            'common_recorded_at' => 'nullable|date',
        ]);

        $readings = $request->input('readings');
        $commonRecordedAt = $request->input('common_recorded_at');

        // Fetch settings from Database
        $allowUserTime = SystemSetting::get('temp_allow_user_time', false);
        $cooldownMinutes = SystemSetting::get('temp_cooldown_minutes', 60);

        // Determine the common timestamp
        if (!$allowUserTime || !$commonRecordedAt) {
            $recordedAt = now();
        } else {
            $recordedAt = Carbon::parse($commonRecordedAt);
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        DB::beginTransaction();
        try {
            foreach ($readings as $roomId => $temp) {
                if ($temp === null || $temp === '') continue;

                $latest = TemperatureReading::where('room_id', $roomId)
                    ->latest('recorded_at')
                    ->first();

                if ($latest) {
                    $newerExists = TemperatureReading::where('room_id', $roomId)
                        ->where('recorded_at', '>', $recordedAt)
                        ->exists();

                    if ($newerExists) {
                        $errors[] = "Room #{$roomId}: The next reading has been recorded.";
                        $errorCount++;
                        continue;
                    }

                    $diffInMinutes = Carbon::parse($latest->recorded_at)->diffInMinutes($recordedAt);

                    if ($diffInMinutes < $cooldownMinutes) {
                        $latest->update([
                            'temperature' => $temp,
                            'recorded_by' => auth()->id(),
                            'recorded_at' => $recordedAt,
                        ]);
                        $successCount++;
                        continue;
                    }
                }

                TemperatureReading::create([
                    'room_id' => $roomId,
                    'temperature' => $temp,
                    'recorded_by' => auth()->id(),
                    'recorded_at' => $recordedAt,
                ]);
                $successCount++;
            }
            
            DB::commit();

            if ($errorCount > 0) {
                return back()->with('success', "{$successCount} readings saved successfully.")
                             ->with('error', "Failed to save {$errorCount} readings: " . implode(', ', $errors));
            }

            return back()->with('success', "All readings ({$successCount}) saved successfully.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save readings: ' . $e->getMessage());
        }
    }

    public function humidity()
    {
        return view('monitoring.humidity');
    }
}
