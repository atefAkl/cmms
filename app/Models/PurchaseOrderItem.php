<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderItemFactory> */
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'inventory_item_id', 'quantity', 'unit_cost', 'serial_number', 'is_under_edit'];

    protected $casts = [
        'is_under_edit' => 'boolean',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
