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

    public function item()
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }
}
