<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryDirection extends Model
{
    protected $fillable = [
        'country_id',
        'niche_id',
        'title',
        'what_to_look_for',
        'search_queries',
        'notes',
        'sort_order',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function niche(): BelongsTo
    {
        return $this->belongsTo(Niche::class);
    }
}
