<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceTask;
use App\Models\SparePart;

class MaintenancePart extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenancePartFactory> */
    use HasFactory;

    protected $fillable = ['maintenance_task_id', 'inventory_item_id', 'quantity', 'unit_cost', 'notes'];

    public function task() { return $this->belongsTo(MaintenanceTask::class, 'maintenance_task_id'); }
    public function item() { return $this->belongsTo(InventoryItem::class, 'inventory_item_id'); }
}
