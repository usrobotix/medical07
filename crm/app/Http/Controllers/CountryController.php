<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('sort_order')->orderBy('name_ru')->paginate(50);

        return view('countries.index', compact('countries'));
    }
}
