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

    public function create()
    {
        return view('kb.countries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'iso2'       => 'required|string|size:2|unique:countries,iso2',
            'name_ru'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $country = Country::create($data);

        return redirect()->route('kb.countries.show', $country)->with('success', 'Страна добавлена.');
    }

    public function edit(Country $country)
    {
        return view('kb.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'iso2'       => 'required|string|size:2|unique:countries,iso2,' . $country->id,
            'name_ru'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $country->update($data);

        return redirect()->route('kb.countries.show', $country)->with('success', 'Страна обновлена.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('kb.countries.index')->with('success', 'Страна удалена.');
    }
}
