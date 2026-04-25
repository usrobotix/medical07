<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Niche;

class NicheController extends Controller
{
    public function index()
    {
        $niches = Niche::orderBy('sort_order')->orderBy('name')->paginate(30);

        return view('kb.niches.index', compact('niches'));
    }

    public function show(Niche $niche)
    {
        $niche->load(['partners', 'countryDirections.country']);

        return view('kb.niches.show', compact('niche'));
    }
}
