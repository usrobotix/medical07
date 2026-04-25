<?php

namespace App\Http\Controllers;

use App\Models\VerificationChecklist;

class VerificationChecklistController extends Controller
{
    public function index()
    {
        $checklists = VerificationChecklist::withCount('items')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('verification-checklists.index', compact('checklists'));
    }
}
