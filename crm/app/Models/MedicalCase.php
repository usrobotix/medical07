<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'patient_id',
        'case_status_id',
        'assigned_to_user_id',
        'title',
        'problem',
        'priority',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function status()
    {
        return $this->belongsTo(CaseStatus::class, 'case_status_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}