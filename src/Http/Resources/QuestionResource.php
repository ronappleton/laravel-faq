<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Resources;

use Appleton\Faq\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Question

 */
class QuestionResource extends JsonResource
{
    /**
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        return [
            'question_uuid' => $this->resource['question']['id'],
            'question' => $this->resource['question'],
            'answer' => $this->resource['answer'],
        ];
    }
}
