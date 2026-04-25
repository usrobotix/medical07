<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerificationChecklist extends Model
{
    protected $fillable = [
        'code',
        'name',
        'partner_type',
        'description',
        'sort_order',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(VerificationChecklistItem::class, 'checklist_id')->orderBy('sort_order');
    }

    public function partnerVerifications(): HasMany
    {
        return $this->hasMany(PartnerVerification::class, 'checklist_id');
    }
}
