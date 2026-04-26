<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateResearchProfileRequest;
use App\Models\Partner;
use App\Models\PartnerResearchProfile;

class PartnerResearchController extends Controller
{
    public function edit(Partner $partner)
    {
        $rp = $partner->researchProfile ?? new PartnerResearchProfile(['partner_id' => $partner->id]);

        return view('kb.partners.research-edit', compact('partner', 'rp'));
    }

    public function update(UpdateResearchProfileRequest $request, Partner $partner)
    {
        $data = $request->validated();

        // Filter empty strings from array fields, keep nulls clean
        foreach (['key_services', 'doctors', 'prices', 'reviews', 'sources'] as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = array_values(
                    array_filter($data[$field], fn ($v) => $v !== null && $v !== '')
                );
                if (empty($data[$field])) {
                    $data[$field] = null;
                }
            }
        }

        // Normalize empty strings to null for scalar fields
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        PartnerResearchProfile::updateOrCreate(
            ['partner_id' => $partner->id],
            $data
        );

        return redirect()
            ->route('kb.partners.show', $partner)
            ->with('success', 'Данные исследования сохранены.');
    }
}
