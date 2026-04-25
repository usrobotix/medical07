<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\CountryDirection;

class CountryDirectionController extends Controller
{
    public function index()
    {
        $directions = CountryDirection::with(['country', 'niche'])
            ->orderBy('sort_order')
            ->paginate(20);

        return view('kb.country-directions.index', compact('directions'));
    }

    public function show(CountryDirection $countryDirection)
    {
        $countryDirection->load(['country', 'niche']);

        return view('kb.country-directions.show', compact('countryDirection'));
    }
}
