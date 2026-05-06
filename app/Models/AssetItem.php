<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssetItem extends Model
{
    use LogsActivity;

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
        'fiscal_group',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    const FISCAL_GROUPS = [
        'Group 1'            => 48,  // 4 Years
        'Group 2'            => 96,  // 8 Years
        'Group 3'            => 192, // 16 Years
        'Group 4'            => 240, // 20 Years
        'Permanent Building'     => 240, // 20 Years
        'Non-Permanent Building' => 120, // 10 Years
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'residual_value' => 'decimal:2',
        'useful_life_months' => 'integer',
    ];

    /**
     * Get the current commercial book value (Alias).
     */
    public function getCommercialValueAttribute()
    {
        return $this->current_value;
    }

    /**
     * Get the current fiscal book value.
     */
    public function getFiscalValueAttribute()
    {
        if (!$this->purchase_date || !$this->purchase_price) {
            return (float) $this->purchase_price;
        }

        $usefulLife = $this->fiscal_useful_life;
        if ($usefulLife <= 0) return (float) $this->purchase_price;

        $purchaseDate = \Carbon\Carbon::parse($this->purchase_date);
        $now = now();

        if ($now->lessThan($purchaseDate)) {
            return (float) $this->purchase_price;
        }

        $monthsPassed = $purchaseDate->diffInMonths($now);

        if ($monthsPassed >= $usefulLife) {
            return 0.0;
        }

        // Aturan Pajak: Nilai Sisa 0
        $depreciationPerMonth = (float) $this->purchase_price / $usefulLife;
        $currentValue = (float) $this->purchase_price - ($depreciationPerMonth * $monthsPassed);

        return max($currentValue, 0.0);
    }

    /**
     * Calculate book value at a specific date using straight-line method.
     */
    public function calculateValueAt($date = null)
    {
        $date = $date ?: now();
        
        if (!$this->purchase_date || !$this->purchase_price || !$this->useful_life_months) {
            return $this->purchase_price ?? 0;
        }

        $purchaseDate = \Carbon\Carbon::parse($this->purchase_date);

        if ($date->lessThan($purchaseDate)) {
            return (float) $this->purchase_price;
        }

        $monthsPassed = $purchaseDate->diffInMonths($date);

        if ($monthsPassed >= $this->useful_life_months) {
            return (float) ($this->residual_value ?? 0);
        }

        $price = (float) $this->purchase_price;
        $residual = (float) ($this->residual_value ?? 0);
        $depreciableAmount = $price - $residual;
        $depreciationPerMonth = $depreciableAmount / $this->useful_life_months;
        $totalDepreciation = $depreciationPerMonth * $monthsPassed;

        $currentValue = $price - $totalDepreciation;

        return max($currentValue, $residual);
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

    /**
     * Get effective fiscal group (own or from category)
     */
    public function getEffectiveFiscalGroupAttribute()
    {
        return $this->fiscal_group ?: $this->asset->category->fiscal_group;
    }

    /**
     * Get fiscal useful life based on group
     */
    public function getFiscalUsefulLifeAttribute()
    {
        $group = $this->effective_fiscal_group;
        return self::FISCAL_GROUPS[$group] ?? 0;
    }

    /**
     * Generate depreciation schedule
     * @param string $type 'commercial' or 'fiscal'
     */
    public function generateSchedule($type = 'commercial')
    {
        if (!$this->purchase_date || !$this->purchase_price) {
            return [];
        }

        $price = (float) $this->purchase_price;
        $startDate = \Carbon\Carbon::parse($this->purchase_date);

        if ($type === 'fiscal') {
            $usefulLife = $this->fiscal_useful_life;
            $residual = 0; // Aturan Pajak: Nilai Sisa 0
        } else {
            $usefulLife = (int) $this->useful_life_months;
            $residual = (float) ($this->residual_value ?? 0);
        }

        if ($usefulLife <= 0) return [];

        $schedule = [];
        $depreciationPerMonth = ($price - $residual) / $usefulLife;
        $currentBookValue = $price;
        $accumulated = 0;

        for ($i = 1; $i <= $usefulLife; $i++) {
            $periodDate = $startDate->copy()->addMonths($i);
            
            $accumulated += $depreciationPerMonth;
            $beginningValue = $currentBookValue;
            $currentBookValue -= $depreciationPerMonth;

            $schedule[] = [
                'period' => $i,
                'month_year' => $periodDate->translatedFormat('F Y'),
                'month_year_key' => $periodDate->format('Y-m'),
                'beginning_value' => $beginningValue,
                'depreciation_expense' => $depreciationPerMonth,
                'accumulated_depreciation' => $accumulated,
                'ending_book_value' => max($currentBookValue, $residual),
            ];
        }

        return $schedule;
    }
}
