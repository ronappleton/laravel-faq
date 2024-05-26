<?php

declare(strict_types=1);

namespace Appleton\Faq\Models;

use Carbon\Carbon;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property-read string $id
 * @property string $faqable_id
 * @property string $faqable_type
 * @property string $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Question> $questions
 */
class Faq extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasUuids;

    protected $with = ['questions'];

    protected $fillable = [
        'faqable_id',
        'faqable_type',
        'user_id',
    ];

    protected static function newFactory(): FaqFactory
    {
        return FaqFactory::new();
    }

    public function faqable(): MorphTo
    {
        return $this->morphTo('faqable');
    }

    /**
     * @return HasMany<Question>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
