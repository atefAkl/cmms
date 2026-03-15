<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    /** @use HasFactory<\Database\Factories\SparePartFactory> */
    use HasFactory;

    protected $fillable = ['name', 'part_number', 'stock', 'cost'];

    public function maintenanceParts()
    {
        return $this->hasMany(MaintenancePart::class);
    }
}
