<?php

declare(strict_types=1);

namespace Feature\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use Appleton\Faq\Models\Faq;
use Tests\TestCase;

#[CoversClass(Faq::class)]
class FaqModelTest extends TestCase
{
    public function testCanGetFaqableRecord(): void
    {
        $user = $this->getNewUser();

        $faqable = $this->getFaqable();

        $faq = Faq::factory()->create([
            'user_id' => $user->id,
            'faqable_id' => $faqable->id,
            'faqable_type' => $faqable::class,
        ]);

        $this->assertEquals($faqable->id, $faq->faqable->id);
    }
}