<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use App\Repositories\CompressorRepository;
use App\Repositories\EvaporatorRepository;

class EquipmentService
{
    public function __construct(
        protected RoomRepository $roomRepository,
        protected CompressorRepository $compressorRepository,
        protected EvaporatorRepository $evaporatorRepository
    ) {}

    public function createRoomWithEquipment(array $roomData, array $compressors, array $evaporator)
    {
        $room = $this->roomRepository->create($roomData);

        foreach ($compressors as $compressorData) {
            $compressorData['room_id'] = $room->id;
            $this->compressorRepository->create($compressorData);
        }

        if (!empty($evaporator)) {
            $evaporator['room_id'] = $room->id;
            $this->evaporatorRepository->create($evaporator);
        }

        return $this->roomRepository->find($room->id);
    }
}
