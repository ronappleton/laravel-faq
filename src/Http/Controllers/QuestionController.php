<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Controllers;

use Appleton\Faq\Http\Requests\QuestionRequest;
use Appleton\Faq\Http\Resources\QuestionResource;
use Appleton\Faq\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuestionController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Question::class);

        return QuestionResource::collection(Question::all());
    }

    public function store(QuestionRequest $request)
    {
        $this->authorize('create', Question::class);

        return new QuestionResource(Question::create($request->validated()));
    }

    public function show(Question $question)
    {
        $this->authorize('view', $question);

        return new QuestionResource($question);
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $this->authorize('update', $question);

        $question->update($request->validated());

        return new QuestionResource($question);
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        $question->delete();

        return response()->json();
    }
}
