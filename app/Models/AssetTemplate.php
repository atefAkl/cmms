<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(AssetTemplate::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AssetTemplate::class, 'parent_id');
    }
}
