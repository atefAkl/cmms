<?php

namespace App\Repositories;

use App\Models\Compressor;

class CompressorRepository
{
    public function create(array $data)
    {
        return Compressor::create($data);
    }

    public function update(Compressor $compressor, array $data)
    {
        $compressor->update($data);
        return $compressor;
    }

    public function delete(Compressor $compressor)
    {
        return $compressor->delete();
    }
}
