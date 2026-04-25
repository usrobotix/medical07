<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\VerificationChecklist;
use Illuminate\Http\Request;

class VerificationChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationChecklist::withCount('items');

        if ($request->filled('partner_type')) {
            $query->where('partner_type', $request->partner_type);
        }

        $checklists = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();

        return view('kb.verification-checklists.index', compact('checklists'));
    }

    public function show(VerificationChecklist $verificationChecklist)
    {
        $verificationChecklist->load('items');

        return view('kb.verification-checklists.show', compact('verificationChecklist'));
    }
}
