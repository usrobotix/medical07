<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerResearchProfile extends Model
{
    protected $fillable = [
        'partner_id',
        'address',
        'direction',
        'key_services',
        'international_page_url',
        'accepts_foreigners_status',
        'accepts_foreigners_source_url',
        'accepts_ru_status',
        'accepts_ru_source_url',
        'working_hours',
        'doctors',
        'prices',
        'reviews',
        'sources',
        'last_checked_at',
        'source_path',
        'raw_clinic_yaml',
        'review_markdown',
        'imported_at',
    ];

    protected $casts = [
        'key_services'  => 'array',
        'doctors'       => 'array',
        'prices'        => 'array',
        'reviews'       => 'array',
        'sources'       => 'array',
        'last_checked_at' => 'date',
        'imported_at'   => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
