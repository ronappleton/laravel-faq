<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'unique:questions,question'],
            'answer' => ['required', 'string'],
        ];
    }
}
