<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = ['name', 'slug', 'level', 'parent_id', 'description'];

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(ItemCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id');
    }

    public static function calcLevel($parent_id)
    {
        if (!$parent_id) {
            return 0;
        }
        $parent = ItemCategory::find($parent_id);
        return $parent->level + 1;
    }
}
