<?php

namespace App\Http\Controllers;

use App\Models\Niche;

class NicheController extends Controller
{
    public function index()
    {
        $niches = Niche::orderBy('sort_order')->orderBy('name')->paginate(50);

        return view('niches.index', compact('niches'));
    }
}
