<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'temperature' => $this->temperature,
            'target_temperature' => $this->target_temperature,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
