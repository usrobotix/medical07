<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Niche;
use App\Models\Partner;
use App\Models\PartnerLayer;
use App\Models\PartnerVerification;
use App\Models\VerificationChecklist;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::with(['layer', 'country', 'niches']);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('niche_id')) {
            $query->whereHas('niches', fn($q) => $q->where('niches.id', $request->niche_id));
        }

        $partners = $query->orderBy('name')->paginate(20)->withQueryString();
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.partners.index', compact('partners', 'countries', 'niches'));
    }

    public function show(Partner $partner)
    {
        $partner->load(['layer', 'country', 'countries', 'niches', 'verifications.checklist', 'researchProfile']);

        return view('kb.partners.show', compact('partner'));
    }

    public function create()
    {
        $layers = PartnerLayer::orderBy('sort_order')->orderBy('name')->get();
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.partners.create', compact('layers', 'countries', 'niches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_layer_id' => 'nullable|exists:partner_layers,id',
            'type'             => 'required|in:clinic,translator,curator',
            'name'             => 'required|string|max:255',
            'country_id'       => 'nullable|exists:countries,id',
            'city'             => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:255',
            'contact_name'     => 'nullable|string|max:255',
            'contact_email'    => 'nullable|email|max:255',
            'contact_phone'    => 'nullable|string|max:100',
            'contact_whatsapp' => 'nullable|string|max:100',
            'contact_telegram' => 'nullable|string|max:100',
            'website_url'      => 'nullable|url|max:500',
            'sla_response_hours' => 'nullable|integer|min:0',
            'sla_result_days'    => 'nullable|integer|min:0',
            'pricing_notes'    => 'nullable|string',
            'invoice_required' => 'boolean',
            'status'           => 'required|in:new,verified,active,frozen',
            'verification_score' => 'nullable|numeric|min:0|max:100',
            'notes'            => 'nullable|string',
            'niche_ids'        => 'nullable|array',
            'niche_ids.*'      => 'exists:niches,id',
            'country_ids'      => 'nullable|array',
            'country_ids.*'    => 'exists:countries,id',
        ]);

        $nicheIds = $data['niche_ids'] ?? [];
        $countryIds = $data['country_ids'] ?? [];
        unset($data['niche_ids'], $data['country_ids']);
        $data['invoice_required'] = $request->boolean('invoice_required');

        $partner = Partner::create($data);
        $partner->niches()->sync($nicheIds);
        $partner->countries()->sync($countryIds);

        return redirect()->route('kb.partners.show', $partner)->with('success', 'Партнёр добавлен.');
    }

    public function edit(Partner $partner)
    {
        $layers = PartnerLayer::orderBy('sort_order')->orderBy('name')->get();
        $countries = Country::orderBy('name_ru')->get();
        $niches = Niche::orderBy('name')->get();

        return view('kb.partners.edit', compact('partner', 'layers', 'countries', 'niches'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'partner_layer_id' => 'nullable|exists:partner_layers,id',
            'type'             => 'required|in:clinic,translator,curator',
            'name'             => 'required|string|max:255',
            'country_id'       => 'nullable|exists:countries,id',
            'city'             => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:255',
            'contact_name'     => 'nullable|string|max:255',
            'contact_email'    => 'nullable|email|max:255',
            'contact_phone'    => 'nullable|string|max:100',
            'contact_whatsapp' => 'nullable|string|max:100',
            'contact_telegram' => 'nullable|string|max:100',
            'website_url'      => 'nullable|url|max:500',
            'sla_response_hours' => 'nullable|integer|min:0',
            'sla_result_days'    => 'nullable|integer|min:0',
            'pricing_notes'    => 'nullable|string',
            'invoice_required' => 'boolean',
            'status'           => 'required|in:new,verified,active,frozen',
            'verification_score' => 'nullable|numeric|min:0|max:100',
            'notes'            => 'nullable|string',
            'niche_ids'        => 'nullable|array',
            'niche_ids.*'      => 'exists:niches,id',
            'country_ids'      => 'nullable|array',
            'country_ids.*'    => 'exists:countries,id',
        ]);

        $nicheIds = $data['niche_ids'] ?? [];
        $countryIds = $data['country_ids'] ?? [];
        unset($data['niche_ids'], $data['country_ids']);
        $data['invoice_required'] = $request->boolean('invoice_required');

        $partner->update($data);
        $partner->niches()->sync($nicheIds);
        $partner->countries()->sync($countryIds);

        return redirect()->route('kb.partners.show', $partner)->with('success', 'Партнёр обновлён.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('kb.partners.index')->with('success', 'Партнёр удалён.');
    }

    public function startVerification(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'checklist_id' => 'required|exists:verification_checklists,id',
        ]);

        $checklist = VerificationChecklist::with('items')->findOrFail($data['checklist_id']);

        $verification = PartnerVerification::firstOrCreate(
            ['partner_id' => $partner->id, 'checklist_id' => $checklist->id],
            ['status' => 'not_started']
        );

        // Populate items if they don't exist yet
        foreach ($checklist->items as $item) {
            $verification->items()->firstOrCreate(
                ['checklist_item_id' => $item->id],
                ['is_checked' => false]
            );
        }

        return redirect()->route('kb.partner-verifications.edit', $verification)
            ->with('success', 'Верификация создана. Выполните проверку и сохраните результат.');
    }
}
