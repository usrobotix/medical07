<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CountryDirection;
use App\Models\Niche;
use Illuminate\Http\Request;

class CountryDirectionController extends Controller
{
    public function index(Request $request)
    {
        $query = CountryDirection::with(['country', 'niche']);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('niche_id')) {
            $query->where('niche_id', $request->niche_id);
        }

        $directions = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.country-directions.index', compact('directions', 'countries', 'niches'));
    }

    public function show(CountryDirection $countryDirection)
    {
        $countryDirection->load(['country', 'niche']);

        return view('kb.country-directions.show', compact('countryDirection'));
    }
}
