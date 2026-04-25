<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerVerification;
use App\Models\VerificationChecklist;
use Illuminate\Http\Request;

class PartnerVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = PartnerVerification::with(['partner', 'checklist', 'verifiedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('checklist_id')) {
            $query->where('checklist_id', $request->checklist_id);
        }

        if ($request->filled('partner_type')) {
            $query->whereHas('partner', fn($q) => $q->where('type', $request->partner_type));
        }

        $verifications = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $checklists = VerificationChecklist::orderBy('name')->get();

        return view('kb.partner-verifications.index', compact('verifications', 'checklists'));
    }

    public function show(PartnerVerification $partnerVerification)
    {
        $partnerVerification->load([
            'partner',
            'checklist',
            'verifiedBy',
            'items.checklistItem',
        ]);

        return view('kb.partner-verifications.show', compact('partnerVerification'));
    }
}
