<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
