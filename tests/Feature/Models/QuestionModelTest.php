<?php

declare(strict_types=1);

namespace Feature\Models;

use Appleton\Faq\Models\Faq;
use Appleton\Faq\Models\Question;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(Question::class)]
class QuestionModelTest extends TestCase
{
    public function testCanGetFactory(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = Question::factory()->create([
            'faq_id' => $faq->id,
        ]);

        $this->assertNotNull($question);
    }

    public function testGetFaq(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $question = Question::factory()->create([
            'faq_id' => $faq->id,
        ]);

        $this->assertEquals($faq->id, $question->faq->id);
    }
}