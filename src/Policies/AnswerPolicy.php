<?php

declare(strict_types=1);

namespace Appleton\Faq\Policies;

use Appleton\Faq\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class AnswerPolicy
{
    use HandlesAuthorization;

    public function create(Authenticatable $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, Answer $answer): bool
    {
        /** @phpstan-ignore-next-line  */
        return $user->id === $answer->user_id;
    }

    public function delete(Authenticatable $user, Answer $answer): bool
    {
        /** @phpstan-ignore-next-line  */
        return $user->id === $answer->user_id;
    }

    public function restore(Authenticatable $user, Answer $answer): bool
    {
        /** @phpstan-ignore-next-line  */
        return $user->id === $answer->user_id;
    }

    public function forceDelete(Authenticatable $user, Answer $answer): bool
    {
        /** @phpstan-ignore-next-line  */
        return $user->id === $answer->user_id;
    }
}
