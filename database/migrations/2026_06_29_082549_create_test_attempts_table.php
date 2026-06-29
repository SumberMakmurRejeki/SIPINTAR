<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->index()->constrained('tests')->restrictOnDelete();
            $table->foreignId('training_participant_id')->index()->constrained('training_participants')->cascadeOnDelete();
            $table->foreignId('employee_id')->index()->constrained('employees')->restrictOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->enum('status', ['in_progress', 'submitted', 'waiting_grading', 'graded', 'passed', 'failed', 'auto_submitted'])->default('in_progress')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable()->index();
            $table->timestamp('auto_submitted_at')->nullable();
            $table->decimal('multiple_choice_score', 5, 2)->nullable();
            $table->decimal('essay_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['test_id', 'training_participant_id', 'attempt_number'], 'tpa_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};
