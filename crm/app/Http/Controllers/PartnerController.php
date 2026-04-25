<?php

namespace App\Http\Controllers;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with(['layer', 'country'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('partners.index', compact('partners'));
    }
}
