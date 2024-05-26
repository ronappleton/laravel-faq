<?php

declare(strict_types=1);

namespace Appleton\Faq\Services;

use Appleton\Faq\Models\Question;
use Appleton\Faq\Models\Faq as FaqModel;
use Illuminate\Support\Facades\Auth;

class Faq
{
    public function getFaq(string $id, bool $withTrashed = false): FaqModel
    {
        if ($withTrashed) {
            $faq = FaqModel::withTrashed()->where('id', $id)->firstOrFail();
        } else {
            $faq = FaqModel::query()->findOrFail($id);
        }

        /** @var FaqModel $faq */
        return $faq;
    }

    public function addQuestion(string $faqId, string $question, string $answer): void
    {
        $this->getFaq($faqId)->questions()->create([
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function getQuestion(string $questionId): Question
    {
        return Question::query()->findOrFail($questionId);
    }

    public function updateQuestion(string $questionId, string $question, string $answer): void
    {
        $this->getQuestion($questionId)->update([
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function deleteQuestion(string $questionId): void
    {
        $this->getQuestion($questionId)->delete();
    }

    public function restoreQuestion(string $questionId): void
    {
        $this->getQuestion($questionId)->restore();
    }

    public function forceDeleteQuestion(string$questionId): void
    {
        $this->getQuestion($questionId)->forceDelete();
    }

    public function createFaq(string $faqableId, string $faqableType): FaqModel
    {
        return FaqModel::query()->create([
            'user_id' => Auth::id(),
            'faqable_id' => $faqableId,
            'faqable_type' => $faqableType,
        ]);
    }

    public function deleteFaq(string $faqId): void
    {
        $this->getFaq($faqId)->delete();
    }

    public function restoreFaq(string $faqId): void
    {
        $this->getFaq($faqId, true)->restore();
    }

    public function forceDeleteFaq(string $faqId): void
    {
        $this->getFaq($faqId, true)->forceDelete();
    }
}