<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'branch_id',
        'max_room_count',
        'max_path_count',
        'diameter',
        'diameter_unit',
        'door_dimensions',
        'is_active'
    ];

    public $timestamps = true;
    public $casts = [
        'is_active' => 'boolean',
        'diameter' => 'json',
        'door_dimensions' => 'json',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
