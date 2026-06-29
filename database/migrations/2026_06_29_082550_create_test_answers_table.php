<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_attempt_id')->index()->constrained('test_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->index()->constrained('questions')->restrictOnDelete();
            $table->foreignId('selected_option_id')->index()->nullable()->constrained('question_options')->restrictOnDelete();
            $table->longText('essay_answer')->nullable();
            $table->decimal('auto_score', 5, 2)->nullable();
            $table->decimal('manual_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->index()->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable()->index();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
            $table->unique(['test_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
