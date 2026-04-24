<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'patient_id',
        'pipeline_status_id',
        'service_status_id',
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

    /** Current pipeline stage (non-service status). */
    public function pipelineStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'pipeline_status_id');
    }

    /** Optional service/pause overlay status. */
    public function serviceStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'service_status_id');
    }

    /** Backward compat alias for places that still call ->status. */
    public function status()
    {
        return $this->pipelineStatus();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(CaseStatusHistory::class, 'medical_case_id')->orderByDesc('id');
    }

    /**
     * Update pipeline status, log history, and handle closed_at.
     * Returns $this for chaining.
     */
    public function movePipeline(CaseStatus $newStatus, ?int $byUserId = null): self
    {
        $old = $this->pipeline_status_id;

        $this->pipeline_status_id = $newStatus->id;

        if ($newStatus->is_terminal) {
            $this->closed_at = $this->closed_at ?? now();
        } else {
            $this->closed_at = null;
        }

        $this->save();

        CaseStatusHistory::create([
            'medical_case_id'        => $this->id,
            'from_pipeline_status_id' => $old,
            'to_pipeline_status_id'   => $newStatus->id,
            'from_service_status_id'  => null,
            'to_service_status_id'    => null,
            'changed_by_user_id'      => $byUserId,
            'change_type'             => 'pipeline',
        ]);

        return $this;
    }

    /**
     * Update service overlay status (set or clear), log history.
     * Pass null $newStatus to clear.
     */
    public function setServiceStatus(?CaseStatus $newStatus, ?int $byUserId = null): self
    {
        $old = $this->service_status_id;
        $this->service_status_id = $newStatus?->id;
        $this->save();

        CaseStatusHistory::create([
            'medical_case_id'        => $this->id,
            'from_pipeline_status_id' => null,
            'to_pipeline_status_id'   => null,
            'from_service_status_id'  => $old,
            'to_service_status_id'    => $newStatus?->id,
            'changed_by_user_id'      => $byUserId,
            'change_type'             => 'service',
        ]);

        return $this;
    }
}