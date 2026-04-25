<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Partner extends Model
{
    protected $fillable = [
        'partner_layer_id',
        'type',
        'name',
        'country_id',
        'city',
        'address',
        'languages',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_telegram',
        'website_url',
        'international_page_url',
        'sla_response_hours',
        'sla_result_days',
        'pricing_notes',
        'invoice_required',
        'status',
        'verification_score',
        'notes',
        'research_slug',
        'research_source_path',
        'research_imported_at',
        'last_checked_date',
    ];

    protected $casts = [
        'invoice_required'      => 'boolean',
        'research_imported_at'  => 'datetime',
        'last_checked_date'     => 'date',
    ];

    public function layer(): BelongsTo
    {
        return $this->belongsTo(PartnerLayer::class, 'partner_layer_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function niches(): BelongsToMany
    {
        return $this->belongsToMany(Niche::class, 'niche_partner');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_partner');
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(MedicalCase::class, 'case_partner', 'partner_id', 'medical_case_id')
            ->withPivot(['role', 'notes'])
            ->withTimestamps();
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(PartnerVerification::class);
    }

    public function researchProfile(): HasOne
    {
        return $this->hasOne(PartnerResearchProfile::class);
    }
}
