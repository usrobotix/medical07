<?php

namespace App\Policies;

use App\Models\CaseStatus;
use App\Models\MedicalCase;
use App\Models\User;

class MedicalCasePolicy
{
    /**
     * Admins and coordinators can do everything.
     * Intake users only if the gate below allows.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin') || $user->hasRole('coordinator')) {
            return true;
        }

        return null; // fall through to specific methods
    }

    /**
     * Can the user move a case to a different pipeline status?
     *
     * Conservative rule for intake:
     *   - current pipeline sort_order must be <= 4
     *   - target pipeline sort_order must be <= 4
     */
    public function movePipeline(User $user, MedicalCase $case, CaseStatus $targetStatus): bool
    {
        if (!$user->hasRole('intake')) {
            return false;
        }

        $currentSortOrder = $case->pipelineStatus?->sort_order ?? 1;

        return $currentSortOrder <= 4 && $targetStatus->sort_order <= 4;
    }

    /**
     * Can the user set/clear the service overlay status?
     *
     * For intake: allowed only if pipeline is still in stages 1-4.
     */
    public function setServiceStatus(User $user, MedicalCase $case): bool
    {
        if (!$user->hasRole('intake')) {
            return false;
        }

        $currentSortOrder = $case->pipelineStatus?->sort_order ?? 1;

        return $currentSortOrder <= 4;
    }
}
