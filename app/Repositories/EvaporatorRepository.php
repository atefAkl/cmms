<?php

namespace App\Repositories;

use App\Models\Evaporator;

class EvaporatorRepository
{
    public function create(array $data)
    {
        return Evaporator::create($data);
    }

    public function update(Evaporator $evaporator, array $data)
    {
        $evaporator->update($data);
        return $evaporator;
    }

    public function delete(Evaporator $evaporator)
    {
        return $evaporator->delete();
    }
}
