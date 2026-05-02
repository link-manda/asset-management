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
        'purchase_price',
        'residual_value',
        'useful_life_months',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'residual_value' => 'decimal:2',
        'useful_life_months' => 'integer',
    ];

    /**
     * Calculate book value at a specific date using straight-line method.
     */
    public function calculateValueAt($date = null)
    {
        $date = $date ?: now();
        
        if (!$this->purchase_date || !$this->purchase_price || !$this->useful_life_months) {
            return $this->purchase_price ?? 0;
        }

        $purchaseDate = $this->purchase_date;

        if ($date->lessThan($purchaseDate)) {
            return $this->purchase_price;
        }

        $monthsPassed = $purchaseDate->diffInMonths($date);

        if ($monthsPassed >= $this->useful_life_months) {
            return $this->residual_value;
        }

        $depreciableAmount = $this->purchase_price - $this->residual_value;
        $depreciationPerMonth = $depreciableAmount / $this->useful_life_months;
        $totalDepreciation = $depreciationPerMonth * $monthsPassed;

        $currentValue = $this->purchase_price - $totalDepreciation;

        return max($currentValue, $this->residual_value);
    }

    public function getCurrentValueAttribute()
    {
        return $this->calculateValueAt(now());
    }

    /**
     * Get depreciation progress percentage for UI.
     */
    public function getDepreciationPercentageAttribute()
    {
        if (!$this->purchase_date || !$this->useful_life_months) {
            return 0;
        }

        $monthsPassed = $this->purchase_date->diffInMonths(now());
        $percentage = ($monthsPassed / $this->useful_life_months) * 100;

        return min(round($percentage), 100);
    }

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
