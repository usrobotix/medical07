<?php

namespace App\Http\Controllers;

use App\Models\PartnerVerification;

class PartnerVerificationController extends Controller
{
    public function index()
    {
        $verifications = PartnerVerification::with(['partner', 'checklist', 'verifiedBy'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('partner-verifications.index', compact('verifications'));
    }
}
