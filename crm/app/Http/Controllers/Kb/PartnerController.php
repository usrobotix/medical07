<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with(['layer', 'country', 'niches'])
            ->orderBy('name')
            ->paginate(20);

        return view('kb.partners.index', compact('partners'));
    }

    public function show(Partner $partner)
    {
        $partner->load(['layer', 'country', 'countries', 'niches', 'verifications.checklist']);

        return view('kb.partners.show', compact('partner'));
    }
}
