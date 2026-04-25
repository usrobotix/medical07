<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerVerification extends Model
{
    protected $fillable = [
        'partner_id',
        'checklist_id',
        'status',
        'verified_at',
        'verified_by_user_id',
        'notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(VerificationChecklist::class, 'checklist_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PartnerVerificationItem::class, 'partner_verification_id');
    }
}
