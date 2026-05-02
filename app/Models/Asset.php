<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category_id',
        'uom_id',
        'location_id',
        'purchase_date',
        'price',
        'status',
        'notes',
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

    public function stocks(): HasMany
    {
        return $this->hasMany(AssetStock::class);
    }

    public function getTotalQuantityAttribute()
    {
        return $this->stocks()->sum('quantity');
    }

    /**
     * Calculate total asset value based on price per unit * total quantity.
     * Price is treated as UNIT PRICE.
     */
    public function getTotalValueAttribute()
    {
        return ($this->price ?? 0) * $this->total_quantity;
    }

    /**
     * Get the stock record with the highest quantity (Main Location).
     */
    public function getPrimaryStockAttribute()
    {
        return $this->stocks()->orderByDesc('quantity')->first();
    }

    /**
     * Get the category that owns the asset.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the location that owns the asset.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the assignments for the asset.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    /**
     * Get the current active assignment.
     */
    public function currentAssignment(): HasOne
    {
        return $this->hasOne(AssetAssignment::class)->whereNull('return_date');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AssetImage::class);
    }
}
