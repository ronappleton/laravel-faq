<?php

declare(strict_types=1);

namespace Appleton\Faq\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

/**
 * @property-read string $id
 * @property string $question_id
 * @property array $answer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Question $question
 */
class Answer extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $fillable = [
        'question_id',
        'answer',
    ];

    public array $translatable = ['answer'];

    /**
     * @return BelongsTo<Question>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
