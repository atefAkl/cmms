<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentInstallLog extends Model
{
    protected $fillable = [
        'system_device_id',
        'old_product_id',
        'new_product_id',
        'install_type',
        'installed_at',
        'performed_by',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
    ];

    public function component()
    {
        return $this->belongsTo(SystemDevice::class, 'system_device_id');
    }

    public function oldProduct()
    {
        return $this->belongsTo(InventoryItem::class, 'old_product_id');
    }

    public function newProduct()
    {
        return $this->belongsTo(InventoryItem::class, 'new_product_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
