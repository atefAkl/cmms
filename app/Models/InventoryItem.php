<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    /** @use HasFactory<\Database\Factories\SparePartFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'image',
        'brand',
        'reference_number', 
        'part_number', 
        'model_number',
        'type', 
        'uom', 
        'stock', 
        'cost', 
        'tech_specs',
        'min_stock_level', 
        'supplier_id', 
        'is_active'
    ];

    protected $casts = [
        'tech_specs' => 'array',
        'is_active' => 'boolean',
        'stock' => 'float',
        'cost' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function maintenanceParts()
    {
        return $this->hasMany(MaintenancePart::class, 'inventory_item_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_item_id');
    }
}
