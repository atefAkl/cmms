<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetComponent extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'type', 'name', 'last_status', 'last_status_ts'];

    protected $casts = [
        'last_status_ts' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function workRegistries()
    {
        return $this->morphMany(ItemWorkRegistry::class, 'item');
    }
}
