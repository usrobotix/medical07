<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Niche;
use Illuminate\Http\Request;

class NicheController extends Controller
{
    public function index(Request $request)
    {
        $query = Niche::withCount('partners');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $niches = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();

        return view('kb.niches.index', compact('niches'));
    }

    public function show(Niche $niche)
    {
        $niche->load(['partners.country', 'countryDirections.country']);

        return view('kb.niches.show', compact('niche'));
    }
}
