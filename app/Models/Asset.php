<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'refrigeration_system_id',
        'system_device_id',
        'item_category_id',
        'inventory_item_id',
        'status',
        'manufacturer',
        'model',
        'serial_number',
        'install_date',
        'notes'
    ];

    protected $casts = [
        'install_date' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(Asset::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Asset::class, 'parent_id');
    }

    public function refrigerationSystem()
    {
        return $this->belongsTo(RefrigerationSystem::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function systemDevice()
    {
        return $this->belongsTo(SystemDevice::class);
    }

    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function components()
    {
        return $this->hasMany(AssetComponent::class);
    }

    public function workRegistries()
    {
        return $this->morphMany(ItemWorkRegistry::class, 'item');
    }
}
