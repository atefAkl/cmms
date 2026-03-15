<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'compressor_id' => $this->compressor_id,
            'issue_description' => $this->issue_description,
            'root_cause' => $this->root_cause,
            'repair_action' => $this->repair_action,
            'technician_id' => $this->technician_id,
            'status' => $this->status,
            'cost' => $this->cost,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
