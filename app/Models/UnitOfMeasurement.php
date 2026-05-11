<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'symbol'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'uom_id');
    }
}
