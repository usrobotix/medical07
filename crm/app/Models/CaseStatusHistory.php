<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStatusHistory extends Model
{
    protected $fillable = [
        'medical_case_id',
        'from_pipeline_status_id',
        'to_pipeline_status_id',
        'from_service_status_id',
        'to_service_status_id',
        'changed_by_user_id',
        'change_type',
        'note',
    ];

    public function medicalCase()
    {
        return $this->belongsTo(MedicalCase::class, 'medical_case_id');
    }

    public function fromPipelineStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'from_pipeline_status_id');
    }

    public function toPipelineStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'to_pipeline_status_id');
    }

    public function fromServiceStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'from_service_status_id');
    }

    public function toServiceStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'to_service_status_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
