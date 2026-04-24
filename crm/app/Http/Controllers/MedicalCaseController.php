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
            ->with(['patient', 'status', 'assignedTo'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('cases.index', compact('cases'));
    }

    public function create()
    {
        $patients = Patient::query()->orderBy('full_name')->get();
        $statuses = CaseStatus::query()->orderBy('sort_order')->get();

        return view('cases.create', compact('patients', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'case_status_id' => ['required', 'exists:case_statuses,id'],
            'title' => ['nullable', 'string', 'max:191'],
            'problem' => ['nullable', 'string'],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $data['assigned_to_user_id'] = auth()->id();
        $data['opened_at'] = now();

        MedicalCase::create($data);

        return redirect()->route('cases.index');
    }
}