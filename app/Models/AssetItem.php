<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssetItem extends Model
{
    protected $fillable = [
        'asset_id',
        'item_code',
        'serial_number',
        'location_id',
        'condition',
        'status',
        'purchase_date',
        'purchase_price'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class, 'asset_item_id');
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(AssetAssignment::class, 'asset_item_id')->whereNull('return_date');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_item_id');
    }

    public function disposal(): HasOne
    {
        return $this->hasOne(AssetDisposal::class, 'asset_item_id');
    }
}
