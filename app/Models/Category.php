<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'default_useful_life_months', 'default_residual_percentage'];

    /**
     * Get the assets for the category.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
