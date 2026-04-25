<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Niche;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::with(['layer', 'country', 'niches']);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('niche_id')) {
            $query->whereHas('niches', fn($q) => $q->where('niches.id', $request->niche_id));
        }

        $partners = $query->orderBy('name')->paginate(20)->withQueryString();
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.partners.index', compact('partners', 'countries', 'niches'));
    }

    public function show(Partner $partner)
    {
        $partner->load(['layer', 'country', 'countries', 'niches', 'verifications.checklist']);

        return view('kb.partners.show', compact('partner'));
    }
}
