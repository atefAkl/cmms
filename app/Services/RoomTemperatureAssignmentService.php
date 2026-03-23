<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomTemperatureProfileAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomTemperatureAssignmentService
{
    public function assignProfile(Room $room, int $profileId, string $startDate, ?string $endDate = null): RoomTemperatureProfileAssignment
    {
        $start = Carbon::parse($startDate);
        $end = $endDate ? Carbon::parse($endDate) : null;

        if ($end && $end->isBefore($start)) {
            throw new \Exception("End date cannot be before start date.");
        }

        return DB::transaction(function () use ($room, $profileId, $start, $end) {
            // Auto-close active profile if starting a new sequential one
            $currentActive = $room->activeProfileAssignment;
            if ($currentActive) {
                if ($start->isAfter($currentActive->start_date) && !$end) {
                    $currentActive->update(['end_date' => $start]);
                }
            }

            // Check overlap: StartA < EndB AND EndA > StartB
            $overlapQuery = RoomTemperatureProfileAssignment::where('room_id', $room->id)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_date', '<', $end ?? '9999-12-31')
                          ->where(function ($subInner) use ($start) {
                              $subInner->where('end_date', '>', $start)
                                       ->orWhereNull('end_date');
                          });
                });

            if ($overlapQuery->exists()) {
                throw new \Exception("The proposed assignment overlaps with an existing historical schedule for this room.");
            }

            return RoomTemperatureProfileAssignment::create([
                'room_id' => $room->id,
                'temperature_profile_id' => $profileId,
                'start_date' => $start,
                'end_date' => $end,
            ]);
        });
    }

    public function endCurrentProfile(Room $room, ?string $endDate = null): bool
    {
        $currentActive = $room->activeProfileAssignment;
        if (!$currentActive) {
            return false;
        }

        $end = $endDate ? Carbon::parse($endDate) : now();
        
        if ($end->isBefore($currentActive->start_date)) {
            throw new \Exception("End date cannot be before the assignment start date.");
        }

        return $currentActive->update(['end_date' => $end]);
    }
    
    public function getTimeline(Room $room)
    {
        return $room->profileAssignments()
                    ->with('profile')
                    ->orderBy('start_date', 'desc')
                    ->get();
    }
}
