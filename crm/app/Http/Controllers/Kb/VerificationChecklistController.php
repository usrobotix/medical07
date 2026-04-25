<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\VerificationChecklist;
use Illuminate\Http\Request;

class VerificationChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationChecklist::withCount('items');

        if ($request->filled('partner_type')) {
            $query->where('partner_type', $request->partner_type);
        }

        $checklists = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();

        return view('kb.verification-checklists.index', compact('checklists'));
    }

    public function show(VerificationChecklist $verificationChecklist)
    {
        $verificationChecklist->load('items');

        return view('kb.verification-checklists.show', compact('verificationChecklist'));
    }

    public function create()
    {
        return view('kb.verification-checklists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'partner_type' => 'required|in:clinic,translator,curator',
            'description'  => 'nullable|string',
            'sort_order'   => 'nullable|integer',
        ]);

        $checklist = VerificationChecklist::create($data);

        return redirect()->route('kb.verification-checklists.show', $checklist)->with('success', 'Чек-лист создан.');
    }

    public function edit(VerificationChecklist $verificationChecklist)
    {
        $verificationChecklist->load('items');

        return view('kb.verification-checklists.edit', compact('verificationChecklist'));
    }

    public function update(Request $request, VerificationChecklist $verificationChecklist)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'partner_type' => 'required|in:clinic,translator,curator',
            'description'  => 'nullable|string',
            'sort_order'   => 'nullable|integer',
        ]);

        $verificationChecklist->update($data);

        return redirect()->route('kb.verification-checklists.show', $verificationChecklist)->with('success', 'Чек-лист обновлён.');
    }

    public function destroy(VerificationChecklist $verificationChecklist)
    {
        $verificationChecklist->delete();

        return redirect()->route('kb.verification-checklists.index')->with('success', 'Чек-лист удалён.');
    }
}
