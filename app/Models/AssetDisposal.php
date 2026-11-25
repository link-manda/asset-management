<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssetDisposal extends Model
{
    use LogsActivity;

    protected $fillable = [
        'asset_item_id',
        'disposal_date',
        'reason',
        'selling_price',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'selling_price' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate Gain/Loss on Disposal.
     */
    public function getGainLossAttribute()
    {
        $bookValueAtDisposal = $this->item->calculateValueAt($this->disposal_date);
        return ($this->selling_price ?? 0) - $bookValueAtDisposal;
    }
}
