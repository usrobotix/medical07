<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerResearchProfile extends Model
{
    protected $fillable = [
        'partner_id',
        'key_services',
        'accepts_foreigners',
        'accepts_russians',
        'working_hours',
        'doctors',
        'prices',
        'payment_info',
        'reviews',
        'accreditations',
        'sources',
        'review_md',
    ];

    protected $casts = [
        'key_services'      => 'array',
        'accepts_foreigners' => 'array',
        'accepts_russians'  => 'array',
        'working_hours'     => 'array',
        'doctors'           => 'array',
        'prices'            => 'array',
        'payment_info'      => 'array',
        'reviews'           => 'array',
        'accreditations'    => 'array',
        'sources'           => 'array',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
