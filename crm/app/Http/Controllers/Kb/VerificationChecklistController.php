<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\VerificationChecklist;

class VerificationChecklistController extends Controller
{
    public function index()
    {
        $checklists = VerificationChecklist::orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('kb.verification-checklists.index', compact('checklists'));
    }

    public function show(VerificationChecklist $verificationChecklist)
    {
        $verificationChecklist->load(['items']);

        return view('kb.verification-checklists.show', compact('verificationChecklist'));
    }
}
