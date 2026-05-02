<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category_id',
        'uom_id',
        'price',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($asset) {
            foreach ($asset->images as $image) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        });
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AssetItem::class);
    }

    public function getTotalQuantityAttribute()
    {
        return $this->items()->count();
    }

    /**
     * Calculate total asset value based on price per unit * total quantity.
     */
    public function getTotalValueAttribute()
    {
        return ($this->price ?? 0) * $this->total_quantity;
    }

    /**
     * Get the category that owns the asset.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the assignments for all items of this asset.
     */
    public function assignments(): HasManyThrough
    {
        return $this->hasManyThrough(AssetAssignment::class, AssetItem::class);
    }

    /**
     * Get the maintenance history for all items of this asset.
     */
    public function maintenances(): HasManyThrough
    {
        return $this->hasManyThrough(AssetMaintenance::class, AssetItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AssetImage::class);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $image = $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
        return $image ? $image->url : 'https://placehold.co/400x400?text=No+Image';
    }
}
