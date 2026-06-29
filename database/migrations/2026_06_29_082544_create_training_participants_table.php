<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('progress_status', ['not_started', 'in_progress', 'waiting_grading', 'passed', 'failed', 'retake'])->default('not_started')->index();
            $table->enum('pre_test_status', ['locked', 'not_started', 'in_progress', 'completed'])->default('locked')->index();
            $table->enum('material_status', ['locked', 'not_started', 'accessed', 'completed'])->default('locked')->index();
            $table->enum('post_test_status', ['locked', 'not_started', 'in_progress', 'waiting_grading', 'completed', 'failed', 'retake'])->default('locked')->index();
            $table->enum('grading_status', ['none', 'waiting', 'graded'])->default('none')->index();
            $table->decimal('pre_test_score', 5, 2)->nullable();
            $table->decimal('post_test_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['training_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
