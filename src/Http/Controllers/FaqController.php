<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Controllers;

use Appleton\Faq\Http\Requests\FaqRequest;
use Appleton\Faq\Http\Requests\QuestionRequest;
use Appleton\Faq\Http\Resources\QuestionResource;
use Appleton\Faq\Services\Faq;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly Faq $faqService)
    {
    }

    public function showFaq(string $faqId): AnonymousResourceCollection
    {
        return QuestionResource::collection($this->faqService->getFaq($faqId)->questions);
    }

    public function storeFaq(FaqRequest $request): Response
    {
        $this->authorize('create', Faq::class);

        $faq = $this->faqService->createFaq(
            $request->validated('faqable_id'),
            $request->validated('faqable_type')
        );

        return response()->json(['id' => $faq->id], Response::HTTP_CREATED);
    }

    public function deleteFaq(string $faqId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('delete', [Faq::class, $faq]);

        $this->faqService->deleteFaq($faqId);
    }

    public function restoreFaq(string $faqId): void
    {
        $faq = $this->faqService->getFaq($faqId, true);

        $this->authorize('restore', [Faq::class, $faq]);

        $this->faqService->restoreFaq($faqId);
    }

    public function forceDeleteFaq(string $faqId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('forceDelete', [Faq::class, $faq]);

        $this->faqService->forceDeleteFaq($faqId);
    }

    public function addQuestion(QuestionRequest $request, string $faqId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('addQuestion', [Faq::class, $faq]);

        $this->faqService->addQuestion(
            $faqId,
            $request->validated('question'),
            $request->validated('answer'),
        );
    }

    public function updateQuestion(QuestionRequest $request, string $faqId, string $questionId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('updateQuestion', [Faq::class, $faq]);

        $this->faqService->updateQuestion(
            $questionId,
            $request->validated('question'),
            $request->validated('answer'),
        );
    }

    public function deleteQuestion(string $faqId, string $questionId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('deleteQuestion', [Faq::class, $faq]);

        $this->faqService->deleteQuestion($questionId);
    }

    public function restoreQuestion(string $faqId, string $questionId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('restoreQuestion', [Faq::class, $faq]);

        $this->faqService->restoreQuestion($questionId);
    }

    public function forceDeleteQuestion(string $faqId, string $questionId): void
    {
        $faq = $this->faqService->getFaq($faqId);

        $this->authorize('forceDeleteQuestion', [Faq::class, $faq]);

        $this->faqService->forceDeleteQuestion($questionId);
    }
}