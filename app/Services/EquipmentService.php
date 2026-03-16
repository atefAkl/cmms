<?php

namespace App\Services;

class EquipmentService
{
    public function createRoomWithAssets(array $roomData, array $assetsData)
    {
        $room = \App\Models\Room::create($roomData);

        foreach ($assetsData as $assetData) {
            $assetData['refrigeration_system_id'] = $assetData['system_id'];
            \App\Models\Asset::create($assetData);
        }

        return $room->load('refrigerationSystems.assets');
    }
}
