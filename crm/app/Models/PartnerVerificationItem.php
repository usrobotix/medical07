<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerVerificationItem extends Model
{
    protected $fillable = [
        'partner_verification_id',
        'checklist_item_id',
        'is_checked',
        'checked_at',
        'notes',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function verification(): BelongsTo
    {
        return $this->belongsTo(PartnerVerification::class, 'partner_verification_id');
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(VerificationChecklistItem::class, 'checklist_item_id');
    }
}
