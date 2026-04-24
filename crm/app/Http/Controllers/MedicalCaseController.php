<?php

namespace App\Http\Controllers;

use App\Models\CaseStatus;
use App\Models\MedicalCase;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicalCaseController extends Controller
{
    public function index()
    {
        $cases = MedicalCase::query()
            ->with(['patient', 'pipelineStatus', 'serviceStatus', 'assignedTo'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('cases.index', compact('cases'));
    }

    public function create()
    {
        $patients = Patient::query()->orderBy('full_name')->get();
        // Only pipeline statuses for creating a new case
        $statuses = CaseStatus::pipeline()->get();

        return view('cases.create', compact('patients', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'         => ['required', 'exists:patients,id'],
            'pipeline_status_id' => ['required', 'exists:case_statuses,id'],
            'title'              => ['nullable', 'string', 'max:191'],
            'problem'            => ['nullable', 'string'],
            'priority'           => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // Validate chosen status is actually a pipeline status
        $status = CaseStatus::findOrFail($data['pipeline_status_id']);
        abort_if($status->is_service, 422, 'Cannot create case with a service status.');

        $data['assigned_to_user_id'] = auth()->id();
        $data['opened_at']           = now();
        $data['service_status_id']   = null;

        // Log terminal state on creation
        if ($status->is_terminal) {
            $data['closed_at'] = now();
        }

        $case = MedicalCase::create($data);

        // Log initial status in history
        \App\Models\CaseStatusHistory::create([
            'medical_case_id'        => $case->id,
            'from_pipeline_status_id' => null,
            'to_pipeline_status_id'   => $status->id,
            'from_service_status_id'  => null,
            'to_service_status_id'    => null,
            'changed_by_user_id'      => auth()->id(),
            'change_type'             => 'pipeline',
            'note'                    => 'Case created',
        ]);

        return redirect()->route('cases.index');
    }
}