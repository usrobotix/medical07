<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResearchProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'manager']) ?? false;
    }

    public function rules(): array
    {
        return [
            'address'                       => 'nullable|string|max:500',
            'direction'                     => 'nullable|string|max:255',
            'international_page_url'        => 'nullable|url|max:2048',
            'accepts_foreigners_status'     => 'nullable|string|max:255',
            'accepts_foreigners_source_url' => 'nullable|url|max:2048',
            'accepts_ru_status'             => 'nullable|string|max:255',
            'accepts_ru_source_url'         => 'nullable|url|max:2048',
            'working_hours'                 => 'nullable|string|max:500',
            'source_path'                   => 'nullable|string|max:1024',
            'last_checked_at'               => 'nullable|date',
            'raw_clinic_yaml'               => 'nullable|string',
            'review_markdown'               => 'nullable|string',

            // JSON array fields submitted as repeated text inputs
            'key_services'                  => 'nullable|array',
            'key_services.*'                => 'nullable|string|max:500',
            'doctors'                       => 'nullable|array',
            'doctors.*'                     => 'nullable|string|max:500',
            'prices'                        => 'nullable|array',
            'prices.*'                      => 'nullable|string|max:500',
            'reviews'                       => 'nullable|array',
            'reviews.*'                     => 'nullable|string|max:1000',
            'sources'                       => 'nullable|array',
            'sources.*'                     => 'nullable|string|max:1000',
        ];
    }
}
