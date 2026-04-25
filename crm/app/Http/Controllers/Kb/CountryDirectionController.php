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

    public function create()
    {
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.country-directions.create', compact('countries', 'niches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'country_id'      => 'required|exists:countries,id',
            'niche_id'        => 'required|exists:niches,id',
            'title'           => 'required|string|max:500',
            'what_to_look_for' => 'nullable|string',
            'search_queries'  => 'nullable|string',
            'notes'           => 'nullable|string',
            'sort_order'      => 'nullable|integer',
        ]);

        $direction = CountryDirection::create($data);

        return redirect()->route('kb.country-directions.show', $direction)->with('success', 'Направление добавлено.');
    }

    public function edit(CountryDirection $countryDirection)
    {
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.country-directions.edit', compact('countryDirection', 'countries', 'niches'));
    }

    public function update(Request $request, CountryDirection $countryDirection)
    {
        $data = $request->validate([
            'country_id'      => 'required|exists:countries,id',
            'niche_id'        => 'required|exists:niches,id',
            'title'           => 'required|string|max:500',
            'what_to_look_for' => 'nullable|string',
            'search_queries'  => 'nullable|string',
            'notes'           => 'nullable|string',
            'sort_order'      => 'nullable|integer',
        ]);

        $countryDirection->update($data);

        return redirect()->route('kb.country-directions.show', $countryDirection)->with('success', 'Направление обновлено.');
    }

    public function destroy(CountryDirection $countryDirection)
    {
        $countryDirection->delete();

        return redirect()->route('kb.country-directions.index')->with('success', 'Направление удалено.');
    }
}
