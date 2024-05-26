<?php

declare(strict_types=1);

namespace Appleton\Faq\Http\Controllers;

use Appleton\Faq\Answer;
use Appleton\Faq\Http\Requests\AnswerRequest;
use Appleton\Faq\Http\Resources\AnswerResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnswerController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Answer::class);

        return AnswerResource::collection(Answer::all());
    }

    public function store(AnswerRequest $request)
    {
        $this->authorize('create', Answer::class);

        return new AnswerResource(Answer::create($request->validated()));
    }

    public function show(Answer $answer)
    {
        $this->authorize('view', $answer);

        return new AnswerResource($answer);
    }

    public function update(AnswerRequest $request, Answer $answer)
    {
        $this->authorize('update', $answer);

        $answer->update($request->validated());

        return new AnswerResource($answer);
    }

    public function destroy(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        return response()->json();
    }
}
