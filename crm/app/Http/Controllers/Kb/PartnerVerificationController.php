<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerVerification;
use App\Models\VerificationChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = PartnerVerification::with(['partner', 'checklist', 'verifiedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('checklist_id')) {
            $query->where('checklist_id', $request->checklist_id);
        }

        if ($request->filled('partner_type')) {
            $query->whereHas('partner', fn($q) => $q->where('type', $request->partner_type));
        }

        $verifications = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $checklists = VerificationChecklist::orderBy('name')->get();

        return view('kb.partner-verifications.index', compact('verifications', 'checklists'));
    }

    public function show(PartnerVerification $partnerVerification)
    {
        $partnerVerification->load([
            'partner',
            'checklist',
            'verifiedBy',
            'items.checklistItem',
        ]);

        return view('kb.partner-verifications.show', compact('partnerVerification'));
    }

    public function create(Request $request)
    {
        $partners = Partner::orderBy('name')->get();
        $checklists = VerificationChecklist::orderBy('name')->get();
        $selectedPartnerId = $request->partner_id;
        $selectedChecklistId = $request->checklist_id;

        return view('kb.partner-verifications.create', compact('partners', 'checklists', 'selectedPartnerId', 'selectedChecklistId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_id'   => 'required|exists:partners,id',
            'checklist_id' => 'required|exists:verification_checklists,id',
        ]);

        $checklist = VerificationChecklist::with('items')->findOrFail($data['checklist_id']);

        $verification = PartnerVerification::firstOrCreate(
            ['partner_id' => $data['partner_id'], 'checklist_id' => $data['checklist_id']],
            ['status' => 'not_started']
        );

        foreach ($checklist->items as $item) {
            $verification->items()->firstOrCreate(
                ['checklist_item_id' => $item->id],
                ['is_checked' => false]
            );
        }

        return redirect()->route('kb.partner-verifications.edit', $verification)
            ->with('success', 'Верификация создана. Выполните проверку ниже.');
    }

    public function edit(PartnerVerification $partnerVerification)
    {
        $partnerVerification->load([
            'partner',
            'checklist',
            'verifiedBy',
            'items.checklistItem',
        ]);

        $users = \App\Models\User::orderBy('name')->get();

        return view('kb.partner-verifications.edit', compact('partnerVerification', 'users'));
    }

    public function update(Request $request, PartnerVerification $partnerVerification)
    {
        $data = $request->validate([
            'status'              => 'required|in:not_started,in_progress,passed,failed',
            'verified_at'         => 'nullable|date',
            'verified_by_user_id' => 'nullable|exists:users,id',
            'notes'               => 'nullable|string',
        ]);

        $partnerVerification->update($data);

        return redirect()->route('kb.partner-verifications.show', $partnerVerification)
            ->with('success', 'Верификация обновлена.');
    }

    public function destroy(PartnerVerification $partnerVerification)
    {
        $partnerVerification->delete();

        return redirect()->route('kb.partner-verifications.index')->with('success', 'Верификация удалена.');
    }

    public function updateItems(Request $request, PartnerVerification $partnerVerification)
    {
        $data = $request->validate([
            'items'             => 'nullable|array',
            'items.*.id'        => 'required|exists:partner_verification_items,id',
            'items.*.is_checked' => 'boolean',
            'items.*.notes'     => 'nullable|string|max:1000',
        ]);

        foreach ($data['items'] ?? [] as $itemData) {
            $item = $partnerVerification->items()->findOrFail($itemData['id']);
            $isChecked = (bool) ($itemData['is_checked'] ?? false);
            $item->update([
                'is_checked' => $isChecked,
                'checked_at' => $isChecked && !$item->checked_at ? now() : ($isChecked ? $item->checked_at : null),
                'notes'      => $itemData['notes'] ?? null,
            ]);
        }

        // Also update overall verification fields if provided
        $overallData = $request->validate([
            'status'              => 'nullable|in:not_started,in_progress,passed,failed',
            'verified_at'         => 'nullable|date',
            'verified_by_user_id' => 'nullable|exists:users,id',
            'notes'               => 'nullable|string',
        ]);

        $updateFields = array_filter($overallData, fn($v) => $v !== null);
        if (!empty($updateFields)) {
            $partnerVerification->update($updateFields);
        }

        return redirect()->route('kb.partner-verifications.edit', $partnerVerification)
            ->with('success', 'Пункты проверки сохранены.');
    }
}
