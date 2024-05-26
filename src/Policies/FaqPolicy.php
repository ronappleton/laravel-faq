<?php

declare(strict_types=1);

namespace Appleton\Faq\Policies;

use Appleton\Faq\Models\Faq;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class FaqPolicy
{
    use HandlesAuthorization;

    public function create(Authenticatable $user): bool
    {
        return true;
    }

    public function delete(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.delete')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }

    public function restore(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.restore')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }

    public function forceDelete(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.forceDelete')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }

    public function addQuestion(Authenticatable $user, Faq $faq): bool
    {
        return $user->id === $faq->user_id;
    }

    public function updateQuestion(Authenticatable $user, Faq $faq): bool
    {
        return $user->id === $faq->user_id;
    }

    public function deleteQuestion(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.deleteQuestion')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }

    public function restoreQuestion(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.restoreQuestion')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }

    public function forceDeleteQuestion(Authenticatable $user, Faq $faq): bool
    {
        if ($user->can('faq.forceDeleteQuestion')) {
            return true;
        }

        return $user->id === $faq->user_id;
    }
}
