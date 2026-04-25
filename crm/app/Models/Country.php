<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'iso2',
        'name_ru',
        'name_en',
        'sort_order',
    ];

    public function directions(): HasMany
    {
        return $this->hasMany(CountryDirection::class);
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'country_partner');
    }
}
