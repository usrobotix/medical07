<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::withCount(['directions', 'partners']);

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('name_ru', 'like', '%' . $request->name . '%')
                  ->orWhere('name_en', 'like', '%' . $request->name . '%')
                  ->orWhere('iso2', 'like', '%' . $request->name . '%');
            });
        }

        $countries = $query->orderBy('sort_order')->orderBy('name_ru')->paginate(20)->withQueryString();

        return view('kb.countries.index', compact('countries'));
    }

    public function show(Country $country)
    {
        $country->load(['directions.niche', 'partners']);

        return view('kb.countries.show', compact('country'));
    }
}
