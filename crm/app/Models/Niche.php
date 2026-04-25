<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niche extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
    ];

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'niche_partner');
    }

    public function countryDirections(): HasMany
    {
        return $this->hasMany(CountryDirection::class);
    }
}
