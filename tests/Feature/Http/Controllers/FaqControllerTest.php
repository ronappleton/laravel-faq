<?php

declare(strict_types=1);

namespace Feature\Http\Controllers;

use Appleton\Faq\Http\Controllers\FaqController;
use Appleton\Faq\Http\Requests\FaqRequest;
use Appleton\Faq\Http\Requests\QuestionRequest;
use Appleton\Faq\Http\Resources\QuestionResource;
use Appleton\Faq\Models\Faq;
use Appleton\Faq\Models\Question;
use Appleton\Faq\Policies\FaqPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Spatie\TestTime\TestTime;
use Tests\TestCase;
use Appleton\Faq\Services\Faq as FaqService;

#[CoversClass(FaqController::class)]
#[CoversClass(Faq::class)]
#[CoversClass(FaqService::class)]
#[CoversClass(Question::class)]
#[CoversClass(FaqPolicy::class)]
#[CoversClass(FaqRequest::class)]
#[CoversClass(QuestionRequest::class)]
#[CoversClass(QuestionResource::class)]
class FaqControllerTest extends TestCase
{
    public function testShowFaqIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->json('get', route('faq.show', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'question',
                    'answer',
                ],
            ],
        ]);
    }

    public function testStoreFaqIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $response = $this->actingAs($user)->json('post', route('faq.store'), [
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'id',
        ]);
    }

    public function testStoreFaqUnauthenticatedIsUnauthorized(): void
    {
        $faqable = $this->getFaqable();

        $response = $this->json('post', route('faq.store'), [
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response->assertUnauthorized();
    }

    public function testDeleteFaqIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response = $this->actingAs($user)->json('delete', route('faq.delete', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function testDeleteFaqWithPermissionIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $adminUser = $this->getNewUser('faq.delete');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response = $this->actingAs($adminUser)->json('delete', route('faq.delete', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function testRestoreFaqIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->json('post', route('faq.restore', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'deleted_at' => null,
        ]);
    }

    public function testRestoreFaqWithPermissionIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $adminUser = $this->getNewUser('faq.restore');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($adminUser)->json('post', route('faq.restore', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'deleted_at' => null,
        ]);
    }

    public function testForceDeleteFaqIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response = $this->actingAs($user)->json('delete', route('faq.forceDelete', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseMissing('faqs', [
            'id' => $faq->id,
        ]);
    }

    public function testForceDeleteFaqWithPermissionIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();
        $adminUser = $this->getNewUser('faq.forceDelete');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response = $this->actingAs($adminUser)->json('delete', route('faq.forceDelete', ['faq' => $faq->id]));

        $response->assertSuccessful();

        $this->assertDatabaseMissing('faqs', [
            'id' => $faq->id,
        ]);
    }

    public function testAddQuestionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $response = $this->actingAs($user)->json('post', route('faq.addQuestion', ['faq' => $faq->id]), [
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response->assertSuccessful();

        $this->assertTrue(Question::where('faq_id', $faq->id)->exists());
    }

    public function testUpdateQuestionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->actingAs($user)->json('patch', route('faq.updateQuestion', ['faq' => $faq->id, 'question' => $question->id]), [
            'question' => 'What is the meaning of life?',
            'answer' => '40',
        ]);

        $response->assertSuccessful();

        $this->assertTrue(Question::where('faq_id', $faq->id)->where('answer', '{"en":"40"}')->exists());
    }

    public function testDeleteQuestionIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->actingAs($user)->json('delete', route('faq.deleteQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function testDeleteQuestionWithPermissionIsSuccessful(): void
    {
        TestTime::freeze(Carbon::now());

        $user = $this->getNewUser();

        $adminUser = $this->getNewUser('faq.deleteQuestion');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->actingAs($adminUser)->json('delete', route('faq.deleteQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function testRestoreQuestionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->json('post', route('faq.restoreQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'deleted_at' => null,
        ]);
    }

    public function testRestoreQuestionWithPermissionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $adminUser = $this->getNewUser('faq.restoreQuestion');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($adminUser)->json('post', route('faq.restoreQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'deleted_at' => null,
        ]);
    }

    public function testForceDeleteQuestionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->actingAs($user)->json('delete', route('faq.forceDeleteQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);
    }

    public function testForceDeleteQuestionWithPermissionIsSuccessful(): void
    {
        $user = $this->getNewUser();

        $adminUser = $this->getNewUser('faq.forceDeleteQuestion');

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = $faq->questions()->create([
            'question' => 'What is the meaning of life?',
            'answer' => '42',
        ]);

        $response = $this->actingAs($adminUser)->json('delete', route('faq.forceDeleteQuestion', ['faq' => $faq->id, 'question' => $question->id]));

        $response->assertSuccessful();

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);
    }
}