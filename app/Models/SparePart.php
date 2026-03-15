<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    /** @use HasFactory<\Database\Factories\SparePartFactory> */
    use HasFactory;

    protected $fillable = ['name', 'part_number', 'stock', 'cost', 'min_stock_level', 'supplier_id'];

    public function maintenanceParts()
    {
        return $this->hasMany(MaintenancePart::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
