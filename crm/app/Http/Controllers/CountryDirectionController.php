<?php

namespace App\Http\Controllers;

use App\Models\CountryDirection;

class CountryDirectionController extends Controller
{
    public function index()
    {
        $directions = CountryDirection::with(['country', 'niche'])
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('country-directions.index', compact('directions'));
    }
}
