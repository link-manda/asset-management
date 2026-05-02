<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    protected $fillable = ['name', 'symbol'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'uom_id');
    }
}
