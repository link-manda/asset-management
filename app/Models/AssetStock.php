<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetStock extends Model
{
    protected $fillable = ['asset_id', 'location_id', 'quantity', 'status'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
