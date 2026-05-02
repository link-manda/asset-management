<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    protected $fillable = [
        'asset_item_id',
        'maintenance_date',
        'description',
        'cost',
        'status',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }
}
