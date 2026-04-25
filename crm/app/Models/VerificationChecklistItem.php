<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerificationChecklistItem extends Model
{
    protected $fillable = [
        'checklist_id',
        'code',
        'text',
        'sort_order',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(VerificationChecklist::class, 'checklist_id');
    }

    public function verificationItems(): HasMany
    {
        return $this->hasMany(PartnerVerificationItem::class, 'checklist_item_id');
    }
}
