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

    public function create()
    {
        return view('kb.niches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $niche = Niche::create($data);

        return redirect()->route('kb.niches.show', $niche)->with('success', 'Ниша добавлена.');
    }

    public function edit(Niche $niche)
    {
        return view('kb.niches.edit', compact('niche'));
    }

    public function update(Request $request, Niche $niche)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $niche->update($data);

        return redirect()->route('kb.niches.show', $niche)->with('success', 'Ниша обновлена.');
    }

    public function destroy(Niche $niche)
    {
        $niche->delete();

        return redirect()->route('kb.niches.index')->with('success', 'Ниша удалена.');
    }
}
