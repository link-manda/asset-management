<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'address', 'parent_id'];

    /**
     * Get the parent location.
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Get the child locations.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Get the full path of the location (Recursive).
     */
    public function getFullPathAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_path . ' > ' . $this->name;
        }

        return $this->name;
    }

    /**
     * Get the physical items for the location.
     */
    public function items(): HasMany
    {
        return $this->hasMany(AssetItem::class);
    }

    /**
     * Get the master assets through items (Unique).
     */
    public function assets()
    {
        return $this->hasManyThrough(Asset::class, AssetItem::class, 'location_id', 'id', 'id', 'asset_id')->distinct();
    }

    /**
     * Get a flattened tree of locations for dropdowns.
     */
    public static function tree($parentId = null, $depth = 0)
    {
        $locations = self::where('parent_id', $parentId)->get();
        $tree = collect();

        foreach ($locations as $location) {
            $location->depth = $depth;
            $tree->push($location);
            $tree = $tree->merge(self::tree($location->id, $depth + 1));
        }

        return $tree;
    }
}
