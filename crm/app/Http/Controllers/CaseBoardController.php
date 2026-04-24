<?php

namespace App\Http\Controllers;

use App\Models\CaseStatus;
use App\Models\MedicalCase;

class CaseBoardController extends Controller
{
    public function index()
    {
        // Only pipeline (non-service) statuses as columns
        $statuses = CaseStatus::pipeline()->get();

        // Service statuses for the overlay dropdown on each card
        $serviceStatuses = CaseStatus::service()->get();

        $cases = MedicalCase::query()
            ->with(['patient', 'pipelineStatus', 'serviceStatus'])
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('pipeline_status_id');

        return view('cases.board', compact('statuses', 'serviceStatuses', 'cases'));
    }
}