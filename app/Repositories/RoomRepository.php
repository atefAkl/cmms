<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function getAll()
    {
        return Room::with(['compressors', 'evaporator'])->get();
    }

    public function find($id)
    {
        return Room::with(['compressors', 'evaporator'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data)
    {
        $room->update($data);
        return $room;
    }

    public function delete(Room $room)
    {
        return $room->delete();
    }
}
