<?php

declare(strict_types=1);

namespace Appleton\Faq\Policies;

use Appleton\Faq\Question;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function create(Authenticatable $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, Question $question): bool
    {
        /** @phpstan-ignore-next-line */
        return $user->id === $question->user_id;
    }

    public function delete(Authenticatable $user, Question $question): bool
    {
        /** @phpstan-ignore-next-line */
        return $user->id === $question->user_id;
    }

    public function restore(Authenticatable $user, Question $question): bool
    {
        /** @phpstan-ignore-next-line */
        return $user->id === $question->user_id;
    }

    public function forceDelete(Authenticatable $user, Question $question): bool
    {
        /** @phpstan-ignore-next-line */
        return $user->id === $question->user_id;
    }
}
