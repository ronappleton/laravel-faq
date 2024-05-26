<?php

declare(strict_types=1);

namespace Appleton\Faq\Models;

use Carbon\Carbon;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property-read string $id
 * @property string $faq_id
 * @property array $question
 * @property array $answer
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Question extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'faq_id',
        'question',
        'answer',
    ];

    protected array $translatable = [
        'question',
        'answer',
    ];

    protected static function newFactory(): QuestionFactory
    {
        return QuestionFactory::new();
    }

    /**
     * @return BelongsTo<Faq>
     */
    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class);
    }
}
