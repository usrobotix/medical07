<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('sort_order')->orderBy('name_ru')->paginate(30);

        return view('kb.countries.index', compact('countries'));
    }

    public function show(Country $country)
    {
        $country->load(['directions.niche', 'partners']);

        return view('kb.countries.show', compact('country'));
    }
}
