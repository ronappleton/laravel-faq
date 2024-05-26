<?php

declare(strict_types=1);

namespace Appleton\Faq\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property-read string $id
 * @property string $owner_id
 * @property string $owner_type
 * @property string $name
 * @property array $question
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
        'owner_id',
        'owner_type',
        'name',
        'question',
    ];

    protected array $translatable = ['name', 'question'];

    public function owner(): MorphTo
    {
        return $this->morphTo('owner');
    }

    /**
     * @return HasOne<Answer>
     */
    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class);
    }
}
