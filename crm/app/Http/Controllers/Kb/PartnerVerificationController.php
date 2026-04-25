<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\PartnerVerification;

class PartnerVerificationController extends Controller
{
    public function index()
    {
        $verifications = PartnerVerification::with(['partner', 'checklist', 'verifiedBy'])
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('kb.partner-verifications.index', compact('verifications'));
    }

    public function show(PartnerVerification $partnerVerification)
    {
        $partnerVerification->load(['partner', 'checklist.items', 'verifiedBy', 'items.checklistItem']);

        return view('kb.partner-verifications.show', compact('partnerVerification'));
    }
}
