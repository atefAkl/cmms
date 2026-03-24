<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemDevice extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'refrigeration_system_id', 'name', 'device_id', 'installed',
        'product_id', 'parent_id', 'level', 'component_type', 'install_type',
        'status', 'last_status_ts', 'metadata', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'installed' => 'date',
        'last_status_ts' => 'datetime',
        'metadata' => 'array',
    ];

    // Accessors and Mutators for backward compatibility / alias mapping
    public function getSystemIdAttribute()
    {
        return $this->refrigeration_system_id;
    }

    public function setSystemIdAttribute($value)
    {
        $this->attributes['refrigeration_system_id'] = $value;
    }

    public function getInstalledAtAttribute()
    {
        return $this->installed;
    }

    public function setInstalledAtAttribute($value)
    {
        $this->attributes['installed'] = $value;
    }

    // Relations
    public function refrigerationSystem()
    {
        return $this->belongsTo(RefrigerationSystem::class, 'refrigeration_system_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'system_device_id');
    }

    public function product()
    {
        return $this->belongsTo(InventoryItem::class, 'product_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
