<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('faqable');
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['faqable_id', 'faqable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
