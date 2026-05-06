<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssetAssignment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'asset_item_id',
        'assigned_to',
        'assigned_date',
        'return_date',
        'condition_on_checkout',
        'condition_on_return',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'return_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the specific physical item that is assigned.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }

    /**
     * Get the user that the asset is assigned to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to search assignments.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->whereHas('item', function($sq) use ($term) {
                $sq->where('item_code', 'like', "%{$term}%");
            })
            ->orWhereHas('item.asset', function($sq) use ($term) {
                $sq->where('name', 'like', "%{$term}%")
                  ->orWhere('asset_code', 'like', "%{$term}%");
            })
            ->orWhereHas('user', function($sq) use ($term) {
                $sq->where('name', 'like', "%{$term}%");
            });
        });
    }
}
