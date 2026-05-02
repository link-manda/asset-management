<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    use HasFactory;

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

    /**
     * Get the specific physical item that is assigned.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }

    /**
     * Get the master asset through the item.
     */
    public function asset()
    {
        return $this->item->asset();
    }

    /**
     * Get the user that the asset is assigned to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
