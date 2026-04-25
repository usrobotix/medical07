<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerLayer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
    ];

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }
}
