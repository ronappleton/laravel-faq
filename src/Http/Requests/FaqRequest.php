<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'faqable_id' => ['required', 'string', 'morph_exists:faqable_type'],
            'faqable_type' => ['required', 'string'],
        ];
    }
}
