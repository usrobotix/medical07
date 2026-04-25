<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\VerificationChecklist;
use App\Models\VerificationChecklistItem;
use Illuminate\Http\Request;

class VerificationChecklistItemController extends Controller
{
    public function store(Request $request, VerificationChecklist $verificationChecklist)
    {
        $data = $request->validate([
            'text'       => 'required|string|max:1000',
            'sort_order' => 'nullable|integer',
        ]);

        $data['checklist_id'] = $verificationChecklist->id;

        $verificationChecklist->items()->create($data);

        return redirect()->route('kb.verification-checklists.edit', $verificationChecklist)
            ->with('success', 'Пункт добавлен.');
    }

    public function update(Request $request, VerificationChecklist $verificationChecklist, VerificationChecklistItem $item)
    {
        $data = $request->validate([
            'text'       => 'required|string|max:1000',
            'sort_order' => 'nullable|integer',
        ]);

        $item->update($data);

        return redirect()->route('kb.verification-checklists.edit', $verificationChecklist)
            ->with('success', 'Пункт обновлён.');
    }

    public function destroy(VerificationChecklist $verificationChecklist, VerificationChecklistItem $item)
    {
        $item->delete();

        return redirect()->route('kb.verification-checklists.edit', $verificationChecklist)
            ->with('success', 'Пункт удалён.');
    }
}
