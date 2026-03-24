<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\SystemSetting;
use App\Models\TemperatureReading;
use App\Models\AssetComponent;
use App\Models\ItemWorkRegistry;
use App\Models\RefrigerationSystem;
use App\Models\Asset;
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
            'humidities' => 'nullable|array',
            'save_snapshots' => 'nullable|array',
            'registered_by_ids' => 'nullable|array',
            'common_recorded_at' => 'nullable|date',
        ]);

        $readings = $request->input('readings');
        $humidities = $request->input('humidities', []);
        $saveSnapshots = $request->input('save_snapshots', []);
        $registeredByFields = $request->input('registered_by_ids', []);
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

                $humidity = $humidities[$roomId] ?? null;
                $saveSnapshot = isset($saveSnapshots[$roomId]) && $saveSnapshots[$roomId] == true;
                $registeredBy = $registeredByFields[$roomId] ?? auth()->id();

                $latest = TemperatureReading::where('room_id', $roomId)
                    ->latest('recorded_at')
                    ->first();

                $readingData = [
                    'room_id' => $roomId,
                    'temperature' => $temp,
                    'humidity' => $humidity,
                    'save_status_snapshot' => $saveSnapshot,
                    'registered_by' => $registeredBy,
                    'recorded_by' => auth()->id(),
                    'recorded_at' => $recordedAt,
                ];

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
                        $latest->update($readingData);
                        $this->handlePostReadingActions($latest);
                        $successCount++;
                        continue;
                    }
                }

                $newReading = TemperatureReading::create($readingData);
                $this->handlePostReadingActions($newReading);
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

    protected function handlePostReadingActions(TemperatureReading $reading)
    {
        // 1. Check profile range
        $room = Room::with('activeProfileAssignment.profile')->find($reading->room_id);
        $profile = $room->activeProfileAssignment->profile ?? null;
        
        $isWithinRange = true;
        if ($profile) {
            if ($reading->temperature < $profile->min_temp || $reading->temperature > $profile->max_temp) {
                $isWithinRange = false;
                // Create Alert
                \App\Models\Alert::create([
                    'type' => 'temperature_threshold',
                    'message' => "Temperature out of range in {$room->name}: {$reading->temperature}°C",
                    'room_id' => $room->id,
                    'refrigeration_system_id' => $reading->refrigeration_system_id,
                    'severity' => 'critical'
                ]);
            }
        }

        // 2. Save snapshot if requested
        if ($reading->save_status_snapshot) {
            $this->saveComponentSnapshots($reading, $isWithinRange);
        }
    }

    protected function saveComponentSnapshots(TemperatureReading $reading, $isWithinRange)
    {
        // Get refrigeration systems for this room
        $systems = RefrigerationSystem::where('room_id', $reading->room_id)->get();
        
        foreach ($systems as $system) {
            $assets = Asset::where('refrigeration_system_id', $system->id)->get();
            foreach ($assets as $asset) {
                $components = AssetComponent::where('asset_id', $asset->id)->get();
                foreach ($components as $component) {
                    $status = $isWithinRange ? 'working' : 'check_required';
                    
                    ItemWorkRegistry::create([
                        'item_id' => $component->id,
                        'item_type' => AssetComponent::class,
                        'status' => $status,
                        'shift' => $this->getCurrentShift(),
                        'register_type' => 'auto',
                        'created_by' => auth()->id(),
                    ]);

                    $component->update([
                        'last_status' => $status,
                        'last_status_ts' => now(),
                    ]);
                }
            }
        }
    }

    protected function getCurrentShift()
    {
        $hour = now()->hour;
        if ($hour >= 6 && $hour < 14) return 'mss'; 
        if ($hour >= 14 && $hour < 22) return 'mes';
        if ($hour >= 22 || $hour < 2) return 'ess';
        return 'ees';
    }

    public function humidity()
    {
        return view('monitoring.humidity');
    }
}
