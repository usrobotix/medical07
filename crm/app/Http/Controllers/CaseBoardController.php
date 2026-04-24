<?php

namespace App\Http\Controllers;

use App\Models\CaseStatus;
use App\Models\MedicalCase;

class CaseBoardController extends Controller
{
    public function index()
    {
        $statuses = CaseStatus::query()
            ->orderBy('sort_order')
            ->get();

        $cases = MedicalCase::query()
            ->with(['patient', 'status'])
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('case_status_id');

        return view('cases.board', compact('statuses', 'cases'));
    }
}