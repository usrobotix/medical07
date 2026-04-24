<?php

namespace App\Http\Controllers;

use App\Models\CaseStatus;
use App\Models\MedicalCase;
use Illuminate\Http\Request;

class CaseStatusController extends Controller
{
    /**
     * PATCH /cases/{case}/pipeline-status
     * Move case to a new pipeline stage (used by Kanban drag&drop).
     */
    public function updatePipeline(Request $request, MedicalCase $case)
    {
        $data = $request->validate([
            'pipeline_status_id' => ['required', 'exists:case_statuses,id'],
        ]);

        $newStatus = CaseStatus::findOrFail($data['pipeline_status_id']);

        if ($newStatus->is_service) {
            return response()->json(['message' => 'Target must be a pipeline status.'], 422);
        }

        // Authorization via policy
        $this->authorize('movePipeline', [$case, $newStatus]);

        $case->movePipeline($newStatus, auth()->id());

        return response()->json([
            'message'            => 'Pipeline status updated.',
            'pipeline_status_id' => $case->pipeline_status_id,
            'closed_at'          => $case->closed_at?->toISOString(),
        ]);
    }

    /**
     * PATCH /cases/{case}/service-status
     * Set or clear the service/pause overlay on a case.
     */
    public function updateService(Request $request, MedicalCase $case)
    {
        $data = $request->validate([
            // null means "clear service status"
            'service_status_id' => ['nullable', 'exists:case_statuses,id'],
        ]);

        $newStatus = null;
        if (!empty($data['service_status_id'])) {
            $newStatus = CaseStatus::findOrFail($data['service_status_id']);
            if (!$newStatus->is_service) {
                return response()->json(['message' => 'Target must be a service status.'], 422);
            }
        }

        // Authorization via policy
        $this->authorize('setServiceStatus', $case);

        $case->setServiceStatus($newStatus, auth()->id());

        return response()->json([
            'message'           => 'Service status updated.',
            'service_status_id' => $case->service_status_id,
            'service_status_name' => $case->serviceStatus?->name,
        ]);
    }
}
