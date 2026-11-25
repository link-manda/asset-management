<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetMaintenance extends Model
{
    use LogsActivity;

    protected $fillable = [
        'asset_item_id',
        'maintenance_date',
        'type',
        'description',
        'cost',
        'status',
        'previous_status',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function item()
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }
}
